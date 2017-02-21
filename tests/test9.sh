#!/bin/sh

PROCESSES=$1;

# Step 1: Enable cron
php admin/cli/cron.php --enable

# Step 2: Disbale all scheduled tasks so we can focus on ad hoc
php admin/tool/testtasks/cli/disable_all_tasks.php

# Step 3: clear adhoc queue so we only have 1 adhoc task
php admin/tool/testtasks/cli/clear_adhoc_task_queue.php

# Step 4: Ensure cron will run for at least 10 seconds.
php admin/tool/testtasks/cli/queue_adhoc_tasks.php --numberoftasks=16 --taskduration=1

# Step 5: Run n crons in the background
for  i in `seq 1 $PROCESSES`; do
    echo "Starting cron $i of $PROCESSES";
    php admin/cli/cron.php | grep 'Ending adhoc task' &
done

wait


# Cleanup
php admin/tool/testtasks/cli/enable_all_tasks.php

