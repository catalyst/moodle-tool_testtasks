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
 * A slow Task
 *
 * @package   tool_testtasks
 * @author    Brendan Heywood <brendan@catalyst-au.net>
 * @copyright Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_testtasks\task;


defined('MOODLE_INTERNAL') || die();

class slow_task extends \core\task\scheduled_task {

    /**
     * Get task name
     */
    public function get_name() {
        return get_string('slow_task', 'tool_testtasks');
    }

    /**
     * Execute task
     */
    public function execute() {
        mtrace("Starting slow task");
        for($c=0; $c<20; $c++) {
            mtrace("slow task $c/20");
            sleep(1);
        }
        mtrace("Ending slow task");
    }
}


