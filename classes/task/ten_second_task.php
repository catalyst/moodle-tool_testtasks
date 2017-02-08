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
 * Basic Task
 *
 * @package   tool_testtasks
 * @author    Kenneth Hendricks <kennethhendricks@catalyst-au.net>
 * @copyright Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_testtasks\task;


defined('MOODLE_INTERNAL') || die();

class ten_second_task extends \core\task\scheduled_task {

    /**
     * Get task name
     */
    public function get_name() {
        return get_string('ten_second_task', 'tool_testtasks');
    }

    /**
     * Execute task
     */
    public function execute() {
        mtrace("Starting ten second task");
        $seconds = 10;
        for ($i = 1; $i <= $seconds; $i++) {
            mtrace("basic task running: $i/$seconds");
            sleep(1);
        }
        mtrace("Ending ten second task");
    }
}


