#!/bin/sh

# Tests the cron script.
# Requires 5.2 or higher.

echo "Task hook test. Tests cron"

LOG=/tmp/testcron.log
rm -f $LOG # remove old log file.

# Step 1: Make sure one_second_task is the only task enabled.
php admin/cli/cron.php --enable
php public/admin/tool/testtasks/cli/disable_all_tasks.php
php admin/cli/scheduled_task.php --enable=\\tool_testtasks\\task\\one_second_task

# We need to wait a minute to ensure task gets run.
echo "Please wait one minute."
sleep 60

php public/admin/tool/testtasks/cli/queue_adhoc_tasks.php --numberoftasks=1 --duration=1

# Step 2: Run a scheduled task.
echo "Running Cron";
php admin/cli/cron.php --keep-alive=0 2>&1 > $LOG

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
