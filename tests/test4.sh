#!/bin/sh

LOG=/tmp/testcron.log
OUTPUT=/tmp/testoutput.txt
rm -f $LOG # remove old log file.
rm -f $OUTPUT # remove old output file.

# Step 1: Enable cron
php admin/cli/cron.php --enable

# Step 2: queue ten tasks to ensure runtime
php admin/tool/testtasks/cli/queue_adhoc_tasks.php --numberoftasks=10 --taskduration=1

# Step 3: Run cron in the background and pipe output to log.
php admin/cli/cron.php 2>&1 > $LOG &

# Step 4: wait for cron to start.
sleep 1;

#step 5: write output of is running to a file in the background.
php admin/cli/cron.php --is-running --verbose 2>&1 > $OUTPUT &

#step 6: give 1 sec to finish.
sleep 1;

# Step 7 should be done by now
if php admin/cli/cron.php --is-running | grep -q 'Cron is currently running.'
then
    echo "[Test 4] --- PASS detected cron is running in <= 1 second ---";
else
    echo "[Test 4] --- FAIL did not detect cron is running in <= 1 second ---";
fi

