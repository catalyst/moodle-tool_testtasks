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

use tool_testtasks\task\timed_adhoc_task;
use tool_testtasks\task\another_timed_adhoc_task;

$usage = "Wrangle up a variety of test adhoc and scheduled tasks for tracker testing

Options:
    -c --class           Class of task to queue, defaults to timed_adhoc_task
    -d --duration=n      Duration of the test tasks in seconds
    -h --help            Print this help.
    -l --loopdelay=n     Loop delay in ms for repetitive/recursive tasks, default 100, min 10
    -n --numberoftasks=n Number of adhoc tasks to queue
    -s --successrate=n   Success rate the test tasks from 0-100
    -f --future=n        Schedule the task n seconds into the future

";
list($options, $unrecognized) = cli_get_params(
    [
        'class' => 'tool_testtasks\task\timed_adhoc_task',
        'duration' => false,
        'future' => 0,
        'help' => false,
        'loopdelay' => 100,
        'numberoftasks' => false,
        'successrate' => 100,
    ], [
        'd' => 'duration',
        'h' => 'help',
        'f' => 'future',
        'l' => 'loopdelay',
        'n' => 'numberoftasks',
        's' => 'successrate',
    ]
);

if ($unrecognized || $options['help']) {
    cli_writeln($usage);
    exit(2);
}

$numberoftasks = $options['numberoftasks'];
$taskduration = $options['duration'];
$successrate = $options['successrate'];
$loopdelay = $options['loopdelay'];

if (!$numberoftasks) {
    $numberoftasks = 1;
}

if (!$taskduration) {
    $taskduration = 1;
}

if ($successrate === false) {
    $successrate = 100;
}

if ($loopdelay < 10) {
    $loopdelay = 100;
}

$taskclass = $options['class'];

for ($i = 1; $i <= intval($numberoftasks); $i++) {
    $task = new $taskclass();
    $data = [
        'label' => "$i of $numberoftasks",
        'duration' => $taskduration,
        'success' => $successrate,
        'loopdelay' => $loopdelay
    ];
    mtrace("Queue task with this data: " . json_encode($data));
    $task->set_custom_data($data);

    $future = (int)$options['future'];
    $task->set_next_run_time(time() + $future);

    $task->set_component('tool_testtasks');
    \core\task\manager::queue_adhoc_task($task);
}
