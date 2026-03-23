#!/bin/sh

# Tests the scripts scheduled_task and adhoc_task.
# Requires 5.2 or higher.

echo "Task hook test. Tests task scripts."

LOG=/tmp/testcron.log
rm -f $LOG # remove old log file.

# Step 1: Make sure cron is enabled.
php admin/cli/cron.php --enable

# Step 2: Run a scheduled task.
echo "Running Scheduled task 'One second task'";
php admin/cli/scheduled_task.php --enable=\\tool_testtasks\\task\\one_second_task
php admin/cli/scheduled_task.php --execute=\\tool_testtasks\\task\\one_second_task 2>&1 > $LOG

# Step 3: Check that scheduled hooks were called.
if grep -q "Greetings from scheduled task: One second task" $LOG
then
    echo "--- PASS Scheduled task pre hook successfully called"
else
    echo "--- FAIL Scheduled task pre hook not called"
fi

if grep -q "Farewell from scheduled task: One second task" $LOG
then
    echo "--- PASS Scheduled task post hook successfully called"
else
    echo "--- FAIL Scheduled task post hook not called"
fi

# Step 4: Run an adhoc task.
echo "Running ad hoc task 'Timed adhoc task'";
php public/admin/tool/testtasks/cli/queue_adhoc_tasks.php --successrate=50
# We need to wait one second to ensure task gets run.
sleep 1
php admin/cli/adhoc_task.php --execute=\\tool_testtasks\\task\\timed_adhoc_task 2>&1 > $LOG
php public/admin/tool/testtasks/cli/clear_adhoc_task_queue.php

# Step 5: Check that adhoc hooks were called.
if grep -q "Greetings from adhoc task: Timed adhoc task" $LOG
then
    echo "--- PASS Ad hoc task pre hook successfully called"
else
    echo "--- FAIL Ad hoc task pre hook not called"
fi

if grep -q "Farewell from adhoc task: Timed adhoc task" $LOG
then
    echo "--- PASS Ad hoc task post hook successfully called"
else
    echo "--- FAIL Ad hoc task post hook not called"
fi
