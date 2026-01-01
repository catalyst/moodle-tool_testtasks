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
    -f --future=n        Schedule the task n seconds into the future
    -h --help            Print this help.
    -l --loopdelay=n     Loop delay in ms for repetitive/recursive tasks, default 100, min 10
    -n --numberoftasks=n Number of adhoc tasks to queue
    -s --successrate=n   Success rate the test tasks from 0-100
    -u --user            If set assignedd the task to a random user
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
        'user' => false,
    ], [
        'd' => 'duration',
        'f' => 'future',
        'h' => 'help',
        'l' => 'loopdelay',
        'n' => 'numberoftasks',
        's' => 'successrate',
        'u' => 'user',
    ]
);

if ($unrecognized || $options['help']) {
    cli_writeln($usage);
    exit(2);
}

$loopdelay = $options['loopdelay'];
$numberoftasks = $options['numberoftasks'];
$successrate = $options['successrate'];
$taskduration = $options['duration'];
$user = $options['user'];

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

// Get just as many users as we need
if ($user) {
    $users = $DB->get_records_sql("
      SELECT id,
             username
        FROM {user}
       WHERE deleted = 0
         AND suspended = 0
         AND username != 'guest'
", [], 0, $numberoftasks);
    $users = array_values($users);
}

for ($i = 1; $i <= intval($numberoftasks); $i++) {
    $task = new $taskclass();

    $info = '';
    if ($user) {
        $userrec = $users[($i-1) % sizeof($users)];
        $task->set_userid($userrec->id);
        $info = " with user id {$userrec->id} {$userrec->username}";
    }

    $data = [
        'label' => "$i of $numberoftasks",
        'duration' => $taskduration,
        'success' => $successrate,
        'loopdelay' => $loopdelay
    ];
    mtrace("Queue task with this data$info: " . json_encode($data));
    $task->set_custom_data($data);

    $future = (int)$options['future'];
    $task->set_next_run_time(time() + $future);

    $task->set_component('tool_testtasks');
    \core\task\manager::queue_adhoc_task($task);
}
