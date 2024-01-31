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
 * @package    local_example
 * @copyright  2023 onwards Catalyst IT {@link http://www.catalyst-eu.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Conn Warwicker <conn.warwicker@catalyst-eu.net>
 */

namespace tool_testtasks\task;

class stored_progress_adhoc_task extends \core\task\adhoc_task {

    public function execute() : bool {

        // Create progress bar with unique name.
        // For this case, we use the task class and ID so nothing should overwrite it.
        $progress = new \core\stored_progress_bar(
            \core\stored_progress_bar::convert_to_idnumber(get_class($this), $this->get_id())
        );

        // Start the progress storing.
        $progress->auto_update(false);
        $progress->start();

        $seconds = 30;
        for ($i = 1; $i <= $seconds; $i++) {

            // Manually update the percentage.
            $percent = round(($i / $seconds) * 100);
            $progress->update_full($percent, "{$percent}% completed");
            mtrace("{$percent}% done");
            sleep(1);

        }

        return true;

    }

}
