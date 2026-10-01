<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Generate fake logstore_standard_log rows for load/archiving testing.
 *
 * This writes raw rows directly into logstore_standard_log, bypassing the event/trigger
 * APIs entirely, so that large volumes of realistic-looking log data can be generated
 * quickly (e.g. for testing tool_s3logs archiving).
 *
 * @package     tool_testtasks
 * @copyright   2026 Catalyst IT
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('CLI_SCRIPT', true);

require(__DIR__.'/../../../../config.php');
require_once($CFG->libdir.'/clilib.php');

$usage = "Generate fake log events for testing, e.g. archiving of old logs.

Rows are inserted directly into logstore_standard_log (no events are actually triggered),
with a random timecreated somewhere in the older half of the given range, e.g. --days=200
generates events randomly between 200 and 100 days ago.

Options:
    -c --course=id|shortname  Course to generate 'course viewed' events for.
                               If omitted, 'dashboard viewed' events are generated instead.
    -d --days=n                How many days back in time to spread events over. Default 365.
    -h --help                  Print this help.
    -n --count=n                Number of events to generate. Default 100.
    -u --user=id|username       User the events belong to. Defaults to the primary admin.

php generate_log_events.php --days=730 --count=50000 --course=42
php generate_log_events.php --count=1000 --user=admin

";

list($options, $unrecognized) = cli_get_params(
    [
        'course' => false,
        'days' => 365,
        'help' => false,
        'count' => 100,
        'user' => false,
    ], [
        'c' => 'course',
        'd' => 'days',
        'h' => 'help',
        'n' => 'count',
        'u' => 'user',
    ]
);

if ($unrecognized || $options['help']) {
    cli_writeln($usage);
    exit($unrecognized ? 1 : 0);
}

$days = (float)$options['days'];
$count = (int)$options['count'];

if ($days <= 0 || $count <= 0) {
    cli_error('Both --days and --count must be positive numbers.');
}

// Resolve the user these events belong to.
try {
    if ($options['user']) {
        if (is_numeric($options['user'])) {
            $userid = $DB->get_field('user', 'id', ['id' => (int)$options['user'], 'deleted' => 0], MUST_EXIST);
        } else {
            $userid = $DB->get_field('user', 'id', ['username' => $options['user'], 'deleted' => 0], MUST_EXIST);
        }
    } else {
        // Default to the primary admin, without needing to call any admin API.
        $adminids = explode(',', $CFG->siteadmins);
        $userid = (int)reset($adminids);
    }
} catch (dml_missing_record_exception $e) {
    cli_error("Could not find user '{$options['user']}'.");
}

// Resolve the course (and therefore event type) these events are for.
try {
    if ($options['course']) {
        if (is_numeric($options['course'])) {
            $courseid = $DB->get_field('course', 'id', ['id' => (int)$options['course']], MUST_EXIST);
        } else {
            $courseid = $DB->get_field('course', 'id', ['shortname' => $options['course']], MUST_EXIST);
        }

        $contextlevel = CONTEXT_COURSE;
        $contextinstanceid = $courseid;
        $eventname = '\core\event\course_viewed';
        $target = 'course';
        $edulevel = 2; // \core\event\base::LEVEL_PARTICIPATING.
    } else {
        $courseid = 0;
        $contextlevel = CONTEXT_USER;
        $contextinstanceid = $userid;
        $eventname = '\core\event\dashboard_viewed';
        $target = 'dashboard';
        $edulevel = 0; // \core\event\base::LEVEL_OTHER.
    }
} catch (dml_missing_record_exception $e) {
    cli_error("Could not find course '{$options['course']}'.");
}

$contextid = $DB->get_field('context', 'id', ['contextlevel' => $contextlevel, 'instanceid' => $contextinstanceid], MUST_EXIST);

$now = time();
// Spread events over the older half of the requested range, e.g. --days=200 generates
// events randomly between 200 and 100 days ago, rather than 200 days ago to now.
$from = $now - (int)round($days * 24 * 60 * 60);
$to = $now - (int)round($days / 2 * 24 * 60 * 60);
$other = serialize([]);

cli_writeln("Generating " . number_format($count) . " '$eventname' events for user $userid" .
    ($courseid ? " in course $courseid" : '') .
    ", timestamped randomly between " . date('Y-m-d H:i:s', $from) . " and " . date('Y-m-d H:i:s', $to) . "...");

// Generate the random timestamps up front and sort them before inserting, so that rows are
// written to the table in timecreated order. This mirrors how logs are created in a real
// site (newer records get higher IDs), which tool_s3logs's progress reporting relies on.
$timestamps = [];
for ($i = 0; $i < $count; $i++) {
    $timestamps[] = random_int($from, $to);
}
sort($timestamps);

$batchsize = 500;
$batch = [];
$inserted = 0;

$progressbar = new \core\output\progress_bar();
$progressbar->create();

foreach ($timestamps as $timecreated) {
    $batch[] = (object)[
        'eventname' => $eventname,
        'component' => 'core',
        'action' => 'viewed',
        'target' => $target,
        'objecttable' => null,
        'objectid' => null,
        'crud' => 'r',
        'edulevel' => $edulevel,
        'contextid' => $contextid,
        'contextlevel' => $contextlevel,
        'contextinstanceid' => $contextinstanceid,
        'userid' => $userid,
        'courseid' => $courseid,
        'relateduserid' => null,
        'anonymous' => 0,
        'other' => $other,
        'timecreated' => $timecreated,
        'origin' => 'cli',
        'ip' => null,
        'realuserid' => null,
    ];

    if (count($batch) >= $batchsize) {
        $DB->insert_records('logstore_standard_log', $batch);
        $inserted += count($batch);
        $batch = [];
        $progressbar->update($inserted, $count, number_format($inserted) . ' / ' . number_format($count) . ' inserted');
    }
}

if (!empty($batch)) {
    $DB->insert_records('logstore_standard_log', $batch);
    $inserted += count($batch);
}

$progressbar->update_full(100, number_format($inserted) . ' / ' . number_format($count) . ' inserted');
cli_writeln('Done. Inserted ' . number_format($inserted) . ' events.');
