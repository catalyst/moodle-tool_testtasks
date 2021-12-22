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

define('CLI_SCRIPT', true);

require(__DIR__.'/../../../../config.php');
require_once($CFG->libdir.'/clilib.php');

list($options, $unrecognized) = cli_get_params(
    [
        'numberoftasks' => 1,
        'classes' => 'tool_testtasks\task\two_second_task,' .
                     'tool_testtasks\task\five_second_task,' .
                     'tool_testtasks\task\ten_second_task,' .
                     'tool_testtasks\task\one_hundred_second_task,' .
                     'tool_testtasks\task\one_thousand_second_task'
    ], [
        'n' => 'numberoftasks',
        'c' => 'classes'
    ]
);

$classes = explode(',', $options['classes']);

$tasks = array_merge(
    ...array_map(
        fn($c) => array_fill(0, $options['numberoftasks'], trim($c)),
        $classes
    )
);
shuffle($tasks);

$records = array_map(
    function($t) {
        $task = new $t();
        $record = \core\task\manager::record_from_adhoc_task($task);

        if (!$task->get_next_run_time()) {
            $record->nextruntime = time() -1;
        }

        return $record;
    },
    $tasks
);

$DB->insert_records('task_adhoc', $records);

print "Queued up " . count($classes) . " x " . $options['numberoftasks'] . " = "  . count($records) . " tasks\n";

