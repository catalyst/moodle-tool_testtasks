#!/bin/sh

LOG=/tmp/test.log
rm -f $LOG # remove old log file.

# Step 1: Enable cron
php admin/cli/cron.php --enable

# Step 2: Add 10 adhoc tasks.
php admin/tool/testtasks/cli/queue_ten_adhoc_tasks.php

# Step 3: Run cron in the background and pipe output to log.
php admin/cli/cron.php 2>&1 > $LOG &

# Step 4: Wait for adhoc task to start.
( tail -f $LOG & ) | grep -q "Starting one second adhoc task"

# Step 5: disable cron
php admin/cli/cron.php --disable

# Step 6: wait two seconds for cron to finish early.
sleep 2;

if grep -q "Cron completed at" $LOG
then
    echo "[Test 3] --- PASS cron finished early ---";
else
    echo "[Test 3] --- FAIL cron hasn't finished early ---";
fi