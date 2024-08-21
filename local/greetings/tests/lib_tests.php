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


namespace local_greetings;
defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/local/greetings/lib.php');

/**
 * Greetings lib tests.
 *
 * @package    local_greetings
 * @copyright  2024 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class lib_tests extends \advanced_testcase {

    /**
     * Testing the translation of greeting messages.
     *
     * @covers ::local_greetings_get_greeting
     *
     * @dataProvider local_greetings_get_greeting_provider
     * @param string|null $country User country
     * @param string $langstring Greetings message language string
     */
    public function test_local_greetings_get_greeting(?string $country, string $langstring) {
        $user = null;
        if (!empty($country)) {
            $this->resetAfterTest(true);
            $user = $this->getDataGenerator()->create_user(['country' => $country]);
        }
        $this->assertSame(get_string($langstring, 'local_greetings', fullname($user)), local_greetings_get_greeting($user));
    }

    /**
     * Data provider for {@see test_local_greetings_get_greeting()}.
     *
     * @return array List of data sets - (string) data set name => (array) data
     */
    public function local_greetings_get_greeting_provider() {
        return [
            'No user' => [
                'country' => null,
                'langstring' => 'greetinguser',
            ],
            'AU User' => [
                'country' => 'AU',
                'langstring' => 'greetinguserau',
            ],
            'ES User' => [
                'country' => 'ES',
                'langstring' => 'greetinguseres',
            ],
            'France' => [
                'country' => 'FR',
                'langstring' => 'greetinguserfr',
            ],
            'Italy' => [
                'country' => 'IT',
                'langstring' => 'greetinguserit',
            ],
            'VU User' => [
                'country' => 'VU',
                'langstring' => 'greetingloggedinuser',
            ],
        ];
    }
}
