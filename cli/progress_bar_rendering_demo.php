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
 * Demonstrates CLI progress bar rendering bugs.
 *
 * Run this in a real terminal (TTY), not piped to a file. Without the fix you will see:
 *  1. The "IMPORTANT: ..." line printed just before the bar is overwritten by the first update.
 *  2. Leftover characters from long status messages / estimates when a shorter one replaces them.
 *
 * @package    tool_testtasks
 * @author     Brendan Heywood <brendan@catalyst-au.net>
 * @copyright  2026 Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('CLI_SCRIPT', true);

require_once(__DIR__ . '/../../../../config.php');
require_once($CFG->libdir . '/clilib.php');

// Messages alternate long and short so stale trailing characters are obvious.
$messages = [
    'Processing a really quite long status message to leave trailing characters behind',
    'Short',
    'Another fairly long message: the quick brown fox jumps over the lazy dog',
    'Tiny',
    'Medium length message here',
    'x',
];

$total = 20;
$sleep = 500000; // Microseconds between updates.

echo "Line 1: printed before the progress bar\n";
echo "IMPORTANT: this line is directly above the progress bar and should survive the first update\n";

$bar = new progress_bar('demo', 50, true);

// Pause so the initial render (before any update) can be inspected.
usleep(1500000);

for ($i = 1; $i <= $total; $i++) {
    $msg = $messages[$i % count($messages)] . " ($i/$total)";
    $bar->update($i, $total, $msg);
    usleep($sleep);
}

echo "Done. Check that the 'IMPORTANT' line above is intact and there is no stray text beside the bar/message.\n";
