#!/bin/sh

LOG=/tmp/test.log

echo "[Test 1] Step 1: --- Enable cron ---";
php admin/cli/cron.php --enable
rm -f $LOG

echo "[Test 1] Step 1: --- Run cron in background ---";
php admin/cli/cron.php 2>&1 | grep 'Execution took' > $LOG &

echo "[Test 1] Step 2: --- sleep 1 ---";
sleep 1;

echo "[Test 1] Step 3: --- Disable cron ---";
php admin/cli/cron.php --disable

echo "[Test 1] Step 4: --- sleep 2 ---";
sleep 2;

if [ -f $LOG ]; then
    echo "[Test 1] Step 5: --- PASS cron is finished early ---";
else
    echo "[Test 1] Step 5: --- FAIL cron hasn't finished early ---";
fi

