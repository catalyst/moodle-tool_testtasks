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
 * Two second task.
 *
 * @package   tool_testtasks
 * @copyright Catalyst IT Australia Pty Ltd
 * @author    Cameron Ball <cameron@cameron1729.xyz>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_testtasks\task;

defined('MOODLE_INTERNAL') || die();

class two_second_task extends \core\task\adhoc_task {

    /**
     * Get task name
     */
    public function get_name() {
        return get_string('two_second_task', 'tool_testtasks');
    }

    /**
     * Execute task
     */
    public function execute() {
        mtrace("Starting two second task");
        sleep(2);
        mtrace("Ending two second task");
    }
}
