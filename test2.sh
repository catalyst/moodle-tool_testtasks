#!/bin/sh

LOG=/tmp/testcron.log
rm -f $LOG # remove old log file.

# Step 1: Enable cron
php admin/cli/cron.php --enable

# Step 2: Ensure cron will run for at least 10 seconds.
php admin/tool/testtasks/cli/queue_ten_one_second_adhoc_tasks.php

# Step 3: Run cron in the background and pipe output to log.
php admin/cli/cron.php 2>&1 > $LOG &

# Step 4: wait a second for cron get going
sleep 1;

# Step 5: disable cron
php admin/cli/cron.php --disable

# Step 6: wait two seconds for cron to finish early.
sleep 3;

if grep -q "Cron completed at" $LOG
then
    echo "[Test 2] --- PASS cron finished early ---";
else
    echo "[Test 2] --- FAIL cron hasn't finished early ---";
fi
