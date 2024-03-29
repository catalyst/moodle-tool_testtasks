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
 * tool_testtasks tasks
 *
 * @package   tool_testtasks
 * @author    Kenneth Hendricks <kennethhendricks@catalyst-au.net>
 * @copyright Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$tasks = array(
    array(
        'classname' => 'tool_testtasks\task\one_second_task',
        'blocking'  => 0,
        'minute'    => '*',
        'hour '     => '*',
        'day'       => '*',
        'dayofweek' => '*',
        'month'     => '*'
    ),
    array(
        'classname' => 'tool_testtasks\task\slow_task',
        'blocking'  => 0,
        'minute'    => '0',
        'hour '     => '*',
        'day'       => '*',
        'dayofweek' => '*',
        'month'     => '*'
    ),
    array(
        'classname' => 'tool_testtasks\task\progress_task',
        'blocking'  => 0,
        'minute'    => '0',
        'hour '     => '*',
        'day'       => '*',
        'dayofweek' => '*',
        'month'     => '*'
    ),
    array(
        'classname' => 'tool_testtasks\task\failing_task',
        'blocking'  => 0,
        'minute'    => '0',
        'hour '     => '*',
        'day'       => '*',
        'dayofweek' => '*',
        'month'     => '*'
    ),
    array(
        'classname' => 'tool_testtasks\task\mtrace_task',
        'blocking'  => 0,
        'minute'    => '0',
        'hour '     => '*',
        'day'       => '*',
        'dayofweek' => '*',
        'month'     => '*'
    ),
    array(
        'classname' => 'tool_testtasks\task\stored_progress_scheduled_task_manual',
        'blocking' => 0,
        'minute' => '*',
        'hour' => '*',
        'day' => '*',
        'dayofweek' => '*',
        'month' => '*'
    ),
    array(
        'classname' => 'tool_testtasks\task\stored_progress_scheduled_task_iterations',
        'blocking' => 0,
        'minute' => '*',
        'hour' => '*',
        'day' => '*',
        'dayofweek' => '*',
        'month' => '*'
    ),
);

