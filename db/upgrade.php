<?php

defined('MOODLE_INTERNAL') || die();

/**
 * Upgrade the plugin.
 *
 * @param int $oldversion
 * @return bool always true
 */
function xmldb_tool_testtasks_upgrade($oldversion) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();


    // sleep(5);

    $task = new tool_testtasks\task\timed_adhoc_task();
    $task->set_custom_data(array(
        'label' => "an upgrade triggered task",
        'duration' => 3,
        'success' => 1,
    ));
    $task->set_component('tool_testtasks');
    \core\task\manager::queue_adhoc_task($task);

    // This seems to be set to run all the time, not just once. So just updated to latest version.
    upgrade_plugin_savepoint(true, 2024013102, 'tool', 'testtasks');

}
