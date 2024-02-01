<?php
define('NO_OUTPUT_BUFFERING', true);
require '../../../config.php';

// Display the bar of the long running task.

$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/example/view.php');

echo $OUTPUT->header();
echo $OUTPUT->heading('All running stored progress bars');

$records = $DB->get_records('stored_progress');
foreach ($records as $record) {

    $bar = \core\stored_progress_bar::get_by_idnumber($record->idnumber);
    if ($bar) {
        $bar->render();
    }

    echo '<hr>';

}

echo $OUTPUT->footer();
