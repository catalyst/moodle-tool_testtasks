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
 * A task which has progress
 *
 * @package   tool_testtasks
 * @author    Brendan Heywood <brendan@catalyst-au.net>
 * @copyright Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_testtasks\task;


defined('MOODLE_INTERNAL') || die();

class progress_task extends \core\task\scheduled_task {

    /**
     * Get task name
     */
    public function get_name() {
        return get_string('progress_task', 'tool_testtasks');
    }

    /**
     * Execute task
     */
    public function execute() {
        global $OUTPUT;
        // $OUTPUT->paragraph('dud');

        $progressbar = new \progress_bar();
        $progressbar->create();

        // Total should be 10 seconds. This has been tuned a few times and the
        // story here is there is an intermittent slowness somewhere in the stack
        // which means that a small percentage of checks have a long TTFB and
        // so icinga / gocd checks fail. This is likely to be an issue in the
        // stack and not the test, so we have just added more margin here.
        $total = 10;
        $progressbar->update_full(0, '0%');
        for ($c = 1; $c <= 100; $c += .3) {
            usleep($total * 1000);
            $progressbar->update_full(sprintf('%.1f', $c), sprintf("You are up to %.1f out of 100", $c));
        }
        $progressbar->update_full($c, sprintf("You are up to %.1f out of 100", $c));
    }
}
