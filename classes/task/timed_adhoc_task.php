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

class timed_adhoc_task extends \core\task\adhoc_task {

    /**
     * Get task name
     */
    public function get_name() {
        return get_string('timed_adhoc_task', 'tool_testtasks');
    }

    /**
     * Execute task
     */
    public function execute() {
        $data = self::get_custom_data();
        $label = $data->label;
        $duration = $data->duration;

        mtrace ("Starting adhoc task '$label' wth duration: $duration");

        for ($i = 1; $i <= $duration; $i++) {
            sleep(1);
            mtrace("adhoc task running: $i/$duration seconds");
        }
        mtrace("Ending adhoc task '$label' with duration: $duration");
    }

}

