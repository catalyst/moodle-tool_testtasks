#!/bin/sh

LOGONE=/tmp/testcron_1.log
LOGTWO=/tmp/testcron_2.log
OUTPUT=/tmp/testoutput.txt
truncate -s 0 $LOGONE # remove old log file.
truncate -s 0 $LOGTWO # remove old log file.
truncate -s 0 $OUTPUT # remove old output file.

# Step 1: Enable cron
php admin/cli/cron.php --enable

# Step 2: clear adhoc queue
php admin/tool/testtasks/cli/clear_adhoc_task_queue.php

# Step 3: add two long adhoc tasks to the queue they need to have a runtime diff of > 10 secs for us to parallel them.
php admin/tool/testtasks/cli/queue_adhoc_tasks.php --numberoftasks=1 --taskduration=30
sleep 1; #so they have diffent priorities.
php admin/tool/testtasks/cli/queue_adhoc_tasks.php --numberoftasks=1 --taskduration=10

# Step 4: Run cron twice the background.
php admin/cli/cron.php 2>&1 > $LOGONE &
sleep 3; # to let logone record 30 sec task.
php admin/cli/cron.php 2>&1 > $LOGTWO &

# Step 5: Wait for 30 second adhoc task to start.
echo 'waiting for 30 second task'
( tail -f -n 1000 $LOGONE $LOGTWO & ) | grep -q "Starting adhoc task '1 of 1' wth duration: 30"
echo "30 second ad hoc task has started"

echo 'waiting for 10 second task'
# Step 6: Wait for 10 second adhoc task to start.
( tail -f -n 1000 $LOGONE $LOGTWO & ) | grep -q "Starting adhoc task '1 of 1' wth duration: 10"
echo "10 second ad hoc task has started"

echo "Now disable cron and wait"
php admin/cli/cron.php --disable-wait --verbose  # 2>&1 > $OUTPUT &
# php admin/cli/cron.php --disable-wait --verbose # 2>&1 > $OUTPUT &

