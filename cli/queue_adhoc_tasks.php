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

list($options, $unrecognized) = cli_get_params(
    [
        'numberoftasks' => false,
        'taskduration' => false,
        'successrate' => 100,
        'class' => 'tool_testtasks\task\timed_adhoc_task',
    ], [
        'n' => 'numberoftasks',
        'd' => 'taskduration',
        's' => 'successrate',
    ]
);


$numberoftasks = $options['numberoftasks'];
$taskduration = $options['taskduration'];
$successrate = $options['successrate'];

if (!$numberoftasks) {
    $numberoftasks = 1;
}

if (!$taskduration) {
    $taskduration = 1;
}

if (!$successrate) {
    $successrate = 100;
}

$taskclass = $options['class'];

for ($i = 1; $i <= intval($numberoftasks); $i++) {
    $task = new $taskclass();
    $task->set_custom_data(array(
        'label' => "$i of $numberoftasks",
        'duration' => $taskduration,
        'success' => $successrate,
    ));
    $task->set_component('tool_testtasks');
    \core\task\manager::queue_adhoc_task($task);
}
