#!/bin/sh

LOG=/tmp/testcron.log
rm -f $LOG # remove old log file.

PROCESSES=$1;

# Step 1: Enable cron
#php admin/cli/cron.php --enable

php admin/tool/testtasks/cli/disable_all_tasks.php

# Step 2: Ensure cron will run for at least 10 seconds.
php admin/tool/testtasks/cli/queue_adhoc_tasks.php --numberoftasks=16 --taskduration=1

# Step 3: Run cron in the background and pipe output to log.
for  i in `seq 1 $PROCESSES`; do
    echo "Starting cron $i of $PROCESSES";
    php admin/cli/cron.php | grep 'Ending adhoc task' &
done

wait


# Cleanup
php admin/tool/testtasks/cli/enable_all_tasks.php

