<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Example scheduled task.
 *
 * @package    tool_testtasks
 * @copyright  2023 onwards Catalyst IT {@link http://www.catalyst-eu.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Conn Warwicker <conn.warwicker@catalyst-eu.net>
 */

namespace tool_testtasks\task;

class stored_progress_scheduled_task_manual extends \core\task\scheduled_task {

    public function get_name() {
        return 'Example scheduled task';
    }

    public function execute() {

        // Create progress bar with unique name.
        // For this case, we use the class name so nothing else will overwrite it (or will it?).
        $progress = new \core\stored_progress_bar(
            \core\stored_progress_bar::convert_to_idnumber(get_class($this))
        );

        // Start the progress storing and don't auto render updates as it doesn't work in tasks.
        $progress->auto_update(false);
        $progress->start();

        $seconds = 30;
        for ($i = 1; $i <= $seconds; $i++) {

            // Manually update the percentage.
            $percent = round(($i / $seconds) * 100);
            $progress->update_full($percent, "I am {$percent}% done");
            sleep(1);

            if ($i > 28) {
                $progress->error('oh no i failed!');
                return;
            }

        }

        return true;

    }

}
