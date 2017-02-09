#!/bin/sh

LOG=/tmp/testcron.log
rm -f $LOG # remove old log file.

# Step 1: Enable cron
php admin/cli/cron.php --enable

# Step 2: Add 10 adhoc tasks.
php admin/tool/testtasks/cli/queue_adhoc_tasks.php --numberoftasks=10 --taskduration=1

# Step 3: Run cron in the background and pipe output to log.
php admin/cli/cron.php 2>&1 > $LOG &

# Step 4: Wait for adhoc task to start.
( tail -f $LOG & ) | grep -q "Starting adhoc task with duration: 1"

# Step 5: disable cron
php admin/cli/cron.php --disable

# Step 6: wait two seconds for cron to finish early.
sleep 2;

if grep -q "Cron completed at" $LOG
then
    echo "[Test 3] --- PASS cron with adhocs stopped early ---";
else
    echo "[Test 3] --- FAIL cron with adhocs did not stop early ---";
fi