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

class recursive_task extends \core\task\adhoc_task {

    const LOOP_DELAY_MIN = 10000;  // Must be > 10ms.
    const LOOP_DELAY_DEFAULT = 100000;
    protected $started = 0;
    protected $duration = 0;

    /**
     * Get task name
     */
    public function get_name() {
        return get_string('recursive_task', 'tool_testtasks');
    }

    /**
     * Execute task
     */
    public function execute() {
        // The default condition is a recursive task with 0.1 second delay running for 10 seconds.

        $data = self::get_custom_data();
        $label = $data->label;
        $duration = (int)$data->duration;
        $success = (int)$data->success;
        // Convert from user (milliseconds) to internal (microseconds).
        $loopdelay = (int)$data->loopdelay * 1000;
        if ($loopdelay < self::LOOP_DELAY_MIN) {
            $loopdelay = self::LOOP_DELAY_DEFAULT;
        }
        if ($duration <= 0) {
            // Total task running time in seconds.
            $duration = 10;
        }
        $this->duration = $duration;

        mtrace ("Starting adhoc task '$label' wth loop duration: $duration");

        $this->started = time();
        $this->recurse($loopdelay);

        $error = rand(0, 100);
        if ($error > $success) {
            mtrace("Ending adhoc task '$label' with duration: $duration with exception");
            throw new \Exception("Exploding!");
        }

        mtrace("Ending adhoc task '$label' with duration: $duration");
    }

    public function recurse($delay) {
        if (time() > ($this->started + $this->duration)) {
            return true;
        }
        time_nanosleep(0, $delay);
        return $this->recurse($delay);
    }
}

