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

// This is a script which just emits events slowly while ever it is open

define('NO_OUTPUT_BUFFERING', true);

require_once(__DIR__ . '/../../../config.php');

$delay = optional_param('delay', 1, PARAM_INT);

echo 'Fill your moodle log with SPAM! :)<br>';

// This forces events to be flushed right away rather than at the end of the shutdown handler.
$CFG->forced_plugin_settings['logstore_standard']['buffersize'] = 1;

$offset = rand(10000,20000); // make it easier to see runs of events

$context = context_system::instance();
for($c = 0; $c < 50; $c++) {
    $num = $offset + $c;
    $event = \tool_testtasks\event\spam_spammed::create([
        'context' => $context,
        'other' => [
            'spamnumber' => $num,
            'delay' => $delay,
        ],
    ]);
    sleep($delay); // The delay must be between when the event is made and when it is triggered.
    $event->trigger();
    echo "logged event {$num} delay $delay<br>";
}
