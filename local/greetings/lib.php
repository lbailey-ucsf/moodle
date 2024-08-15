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
 * Callback implementations for Greetings
 *
 * @package    local_greetings
 * @copyright  2024 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Retrieves the appropriate greeting based on the user's country.
 *
 * @param object $user The user object.
 * @return string The greeting message.
 */
function local_greetings_get_greeting($user) {
    if ($user == null) {
        return get_string('greetinguser', 'local_greetings');
    }

    $country = $user->country;
    switch ($country) {
        case 'AU':
            return get_string('greetinguserau', 'local_greetings', fullname($user));
        case 'ES':
            return get_string('greetinguseres', 'local_greetings', fullname($user));
        case 'FR':
            return get_string('greetinguserfr', 'local_greetings', fullname($user));
        case 'IT':
            return get_string('greetinguserit', 'local_greetings', fullname($user));
        default:
            return get_string('greetinguser', 'local_greetings');
    }
}

/**
 * Extends the navigation block on the front page.
 *
 * @param navigation_node $frontpage The front page navigation node.
 */
function local_greetings_extend_navigation_frontpage(navigation_node $frontpage) {
    if (!isguestuser()) {
        $frontpage->add(
            get_string('pluginname', 'local_greetings'),
            new  moodle_url('/local/greetings/index.php'),
            navigation_node::TYPE_SETTING, );
    }
}

/**
 * Saves a message to the database.
 *
 * @param object $user The user object.
 * @param string $message The message to save.
 */
function local_greetings_save_message($user, $message) {
    global $DB;

    $record = new stdClass();
    $record->userid = $user->id;
    $record->message = $message;
    $record->timecreated = time();

    $DB->insert_record('local_greetings_messsages', $record);
}
