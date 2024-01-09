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
 * A task that just does lots of mtrace
 *
 * @package   tool_testtasks
 * @author    Brendan Heywood <brendan@catalyst-au.net>
 * @copyright Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_testtasks\task;


defined('MOODLE_INTERNAL') || die();

class mtrace_task extends \core\task\scheduled_task {

    /**
     * Get task name
     */
    public function get_name() {
        return get_string('mtrace_task', 'tool_testtasks');
    }

    /**
     * Execute task
     */
    public function execute() {

        mtrace("1 This task output a url http://moodle.com");
        usleep(100000);
        mtrace("This task output a url http://moodle.com post stuff");
        usleep(100000);
        mtrace("http://moodle.com post stuff");
        usleep(100000);
        mtrace("http://moodle.com");
        usleep(100000);

        mtrace("Output an email info@moodle.com");
        usleep(100000);
        mtrace("Output an email info@moodle.com post stuff");
        usleep(100000);
        mtrace("info@moodle.com post stuff");
        usleep(100000);

        mtrace("This should all ", '');
        usleep(100000);
        mtrace("appear on ", '');
        usleep(100000);
        mtrace("one line");
        usleep(100000);

        mtrace("This should all", '');
        usleep(100000);
        mtrace(" appear on", '');
        usleep(100000);
        mtrace(" one line");
        usleep(100000);

        mtrace("2 <script>alert('this should not alert');</script>");

        debugging("3 debugging normal", DEBUG_NORMAL);
        usleep(100000);

        debugging("4 debugging developer", DEBUG_DEVELOPER);
        usleep(100000);

        debugging("5 debugging all", DEBUG_ALL);
        usleep(100000);

        debugging("6 debugging minimal", DEBUG_MINIMAL);
        usleep(100000);

        debugging("7 debugging none", DEBUG_NONE);
        usleep(100000);

        error_log('8 this is error_log');
        usleep(100000);

        fwrite(STDERR, "9 this is direct to STDERR\n");
        usleep(100000);

        fwrite(STDOUT, "10 this is direct to STDOUT\n");
        usleep(100000);

        print "11 this is normal print\n";
        usleep(100000);

            // throw new \Exception("Exploding!");
    }
}


