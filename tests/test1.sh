#!/bin/sh

LOG=/tmp/testcron.log
rm -f $LOG # remove old log file.

# Step 1: Disable cron
php admin/cli/cron.php --disable

# Step 2: Run cron in the background and pipe output to log.
php admin/cli/cron.php 2>&1 > $LOG &

# Step 3: Wait for cron to finish.
( tail -f $LOG 2>/dev/null & ) | grep -q "Cron completed"

# Step 4: assert no cron tasks ran.
if grep -q "Execute scheduled task:" $LOG
then
    echo "[Test 1-1] --- FAIL cron task executed ---";
else
    echo "[Test 1-1] --- PASS no cron tasks executed ---";
fi

# Step 5: Run one_second_task through shedule_task cli script
php admin/tool/task/cli/schedule_task.php 2>&1 --execute=\\tool_testtasks\\task\\one_second_task > $LOG &

# Step 6: wait a second for task to run.
sleep 1;

# Step 7: assert one_second_task ran.
if grep -q "Starting one second task" $LOG
then
    echo "[Test 1-2] --- PASS schedule task cli script still works ---";
else
    echo "[Test 1-2] --- FAIL schedule task cli script doesnt work ---";
fi


