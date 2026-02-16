<?php

// Detect if web or CLI.
$isweb = !isset($argv);
$iscli = !$isweb;

// CLI must define this before including config.php
if ($iscli) {
    define('CLI_SCRIPT', true);
}

require('config.php');

if ($isweb) {
    header('Content-Type: text/plain; charset=UTF-8');
}

$values = [];
$ready = false;

function logg($msg) {
    echo "$msg\n";
    error_log($msg);
}

$wait = 0;

while (!$ready) {

    $ready = true;

    for($c = 1; $c <= 5; $c++) {

        if (isset($values[$c])) {
            continue;
        }

        $cache = cache::make('core', 'coursemodinfo');
        $cachekey = "test$c";
        $value =  $cache->get_versioned($cachekey, 1);

        if ($value) {
            logg("Hit $cachekey = '$value'");
            $values[$c] = $value;
        } else {

            if ($cache->acquire_lock($cachekey, 0) !== false) {
                $value =  $cache->get_versioned($cachekey, 1);

                if ($value) {
                    logg("WAIT and then HIT $cachekey = '$value'");
                    $values[$c] = $value;
                } else {

                    $value = "value $c";
                    logg("LOCK and SET $cachekey = '$value'");
                    sleep(3);
                    $cache->set_versioned($cachekey, 1, $value);
                    $cache->get_versioned($cachekey, 1);
                    $values[$c] = $value;
                }
                $cache->release_lock($cachekey);
            } else {
                logg("MISS $cachekey and NO LOCK");
                $ready = false;
            }
        }
    }

    $wait += 50;

    logg("WAITING for $wait");
    usleep($wait * 1000);

}

// Now we should have all values
echo json_encode($values, JSON_PRETTY_PRINT) . "\n";

