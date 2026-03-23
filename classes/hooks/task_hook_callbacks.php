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

namespace tool_testtasks\hooks;

use \core\hook\task\scheduled_task_starting;
use \core\hook\task\scheduled_task_complete;
use \core\hook\task\adhoc_task_starting;
use \core\hook\task\adhoc_task_complete;

/**
 * Define hook callbacks used in this plugin.
 *
 * @package tool_testasks
 * @author Jason den Dulk <jasondendulk@catalyst-au.net>
 * @copyright 2026 Catalyst IT
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class task_hook_callbacks
{
    /**
     * Pre scheduled hook callback.
     *
     * @param scheduled_task_starting $hook
     */
    public static function scheduled_task_starting(scheduled_task_starting $hook) {
        mtrace('Greetings from scheduled task: ' . $hook->task->get_name() . ', task ID: ' . $hook->taskid);
    }

    /**
     * Post scheduled hook callback.
     *
     * @param scheduled_task_complete $hook
     */
    public static function scheduled_task_complete(scheduled_task_complete $hook) {
        mtrace('Farewell from scheduled task: ' . $hook->task->get_name() . ', task ID: ' . $hook->taskid);
    }

    /**
     * Pre adhoc hook callback.
     *
     * @param adhoc_task_starting $hook
     */
    public static function adhoc_task_starting(adhoc_task_starting $hook) {
        mtrace('Greetings from adhoc task: ' . $hook->task->get_name() . ', task ID: ' . $hook->task->get_id());
    }

    /**
     * Post adhoc hook callback.
     *
     * @param adhoc_task_complete $hook
     */
    public static function adhoc_task_complete(adhoc_task_complete $hook) {
        mtrace('Farewell from adhoc task: ' . $hook->task->get_name() . ', task ID: ' . $hook->task->get_id());
    }
}
