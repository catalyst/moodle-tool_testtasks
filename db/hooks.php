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
 * Declare hook callbacks used in this plugin.
 *
 * @package tool_testtasks
 * @author Jason den Dulk <jasondendulk@catalyst-au.net>
 * @copyright 2026 Catalyst IT
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$callbacks = [
    [
        'hook' => \core\hook\task\scheduled_task_starting::class,
        'callback' => [\tool_testtasks\hooks\task_hook_callbacks::class, 'scheduled_task_starting'],
        'priority' => 500,
    ],
    [
        'hook' => \core\hook\task\scheduled_task_complete::class,
        'callback' => [\tool_testtasks\hooks\task_hook_callbacks::class, 'scheduled_task_complete'],
        'priority' => 500,
    ],
    [
        'hook' => \core\hook\task\adhoc_task_starting::class,
        'callback' => [\tool_testtasks\hooks\task_hook_callbacks::class, 'adhoc_task_starting'],
        'priority' => 500,
    ],
    [
        'hook' => \core\hook\task\adhoc_task_complete::class,
        'callback' => [\tool_testtasks\hooks\task_hook_callbacks::class, 'adhoc_task_complete'],
        'priority' => 500,
    ],
];
