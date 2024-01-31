<?php

define('CLI_SCRIPT', true);

require_once(__DIR__ . '/../../../../config.php');
require_once($CFG->libdir . '/clilib.php');

$num_tasks = 5000; // the number of tasks to be completed.

$progress = new \core\stored_progress_bar('example-cli-bar');
$progress->start();

for($cur_task = 0; $cur_task <= $num_tasks; $cur_task++)
{
    $progress->update($cur_task, $num_tasks, 'this is '. $cur_task . '/' . $num_tasks);
}

mtrace('done');
