#!/bin/sh

LOG=/tmp/testcron.log
OUTPUT=/tmp/testoutput.txt
rm -f $LOG # remove old log file.
rm -f $OUTPUT # remove old output file.

# Step 1: Enable cron
php admin/cli/cron.php --enable

# Step 2: clear adhoc queue so we only have 1 adhoc task
php admin/tool/testtasks/cli/clear_adhoc_task_queue.php

# Step 4: Run cron in the background and pipe output to log.
php admin/tool/testtasks/cli/queue_ten_second_adhoc_task.php

# Step 5: Run cron in the background and pipe output to log.
php admin/cli/cron.php 2>&1 > $LOG &

# Step 6: Wait for 10 second adhoc task to start.
( tail -f $LOG & ) | grep -q "Starting ten second adhoc task"

#step 7: write output of is running to a file in the background.
php admin/cli/cron.php --is-running --verbose 2>&1 > $OUTPUT &

#step 8: give 1 sec to finish.
sleep 1;

#step 9: Assert cron is running could not acquire the adhoc task
if cat $OUTPUT | grep -q 'Could not acquire adhoc_'
then
    echo "[Test 5] --- PASS Could not acquire adhoc task ---";
else
    echo "[Test 5] --- FAIL Could acquire adhoc task ---";
fi