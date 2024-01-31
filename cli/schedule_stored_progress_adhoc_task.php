<?php

define('CLI_SCRIPT', true);

require_once(__DIR__ . '/../../../../config.php');
require_once($CFG->libdir . '/clilib.php');

$task = new \tool_testtasks\task\stored_progress_adhoc_task();
\core\task\manager::queue_adhoc_task($task);

echo "SCHEDULED ADHOC TASK\n";
echo "Run it with: php admin/cli/adhoc_task.php --execute='\\" . get_class($task) . "'\n";
