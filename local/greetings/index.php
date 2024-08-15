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
 * TODO describe file index
 *
 * @package    local_greetings
 * @copyright  2024 Leon Baliley leon.bailey@ucsf.edu
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core\plugininfo\format;

require('../../config.php');
require_once($CFG->dirroot . '/local/greetings/lib.php');

$url = new moodle_url('/local/greetings/index.php', []);

$context = context_system::instance();
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());
$PAGE->set_heading(get_string('pluginname', 'local_greetings'));
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('pluginname', 'local_greetings'));

require_login();

echo $OUTPUT->header();

if (isguestuser()) {
    throw new moodle_exception('noguest');
}



$greeting = get_string('greetinguser', 'local_greetings');

if (isloggedin()) {
    $greeting = local_greetings_get_greeting($USER);
}

echo $greeting;

$allowpost = has_capability('local/greetings:postmessages', $context);
$messageform = new \local_greetings\form\message_form();
if ($allowpost) {
    $messageform->display();
}

$deleteanypost = has_capability('local/greetings:deleteanyposts', $context);
$action = optional_param('action', '', PARAM_TEXT);
if ($action == 'del') {
    require_sesskey();
    $id = required_param('id', PARAM_TEXT);

    if ($deleteanypost) {
        $DB->delete_records('local_greetings_messsages', ['id' => $id]);
        redirect($PAGE->url);
    }
}

if ($data = $messageform->get_data()) {
    require_capability('local/greetings:postmessages', $context);
    $message = required_param('message', PARAM_TEXT);
    if (!empty($message)) {
        $message = local_greetings_save_message($USER, $message);
    }
}

$allowview = has_capability('local/greetings:viewmessages', $context);
if ($allowview) {
    $userfields = \core_user\fields::for_name()->with_identity($context);
    $userfieldssql = $userfields->get_sql('u');

    $sql = "SELECT m.id, m.message, m.timecreated, m.userid {$userfieldssql->selects}
            FROM {local_greetings_messsages} m
        LEFT JOIN {user} u ON u.id = m.userid
        ORDER BY timecreated DESC";

    $messages = $DB->get_records_sql($sql);

    echo $OUTPUT->box_start('card-columns');

    foreach ($messages as $m) {
        $cleanmessage = format_text($m->message, FORMAT_PLAIN);
        echo html_writer::start_tag('div', ['class' => 'card']);
        // Card body.
        echo html_writer::start_tag('div', ['class' => 'card-body']);
        echo html_writer::tag('p', $cleanmessage, ['class' => 'card-text']);
        echo html_writer::tag('p', get_string('postedby', 'local_greetings', $m->firstname), ['class' => 'card-text']);
        echo html_writer::start_tag('p', ['class' => 'card-text']);
        echo html_writer::tag('small', userdate($m->timecreated), ['class' => 'text-muted']);
        echo html_writer::end_tag('p');
        echo html_writer::end_tag('div');
        // Card footer.
        if ($deleteanypost) {
            echo html_writer::start_tag('p', ['class' => 'card-footer text-center']);
            echo html_writer::link(
                new moodle_url(
                    '/local/greetings/index.php',
                    ['action' => 'del', 'id' => $m->id, 'sesskey' => sesskey()]
                ),
                $OUTPUT->pix_icon('t/delete', '') . get_string('delete')
            );
            echo html_writer::end_tag('p');
        }

        echo html_writer::end_tag('div');
    }

    echo $OUTPUT->box_end();

}


echo $OUTPUT->footer();

$USER->id;
