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
 * @package    mod_adobeconnect
 * @author     Akinsaya Delamarre (adelamarre@remote-learner.net)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright  (C) 2015 Remote Learner.net Inc http://www.remote-learner.net
 */

namespace mod_adobeconnect\event;

defined('MOODLE_INTERNAL') || die();

/**
 * The adobeconnect_assign_role event class.
 *
 * @property-read array $other {
 *
 *      User assigns a role.
 * }
 */
class adobeconnect_assign_role extends \core\event\base {
    /**
     * This function initializes class properties.
     */
    protected function init() {
        $this->data['crud'] = 'r';
        $this->data['edulevel'] = self::LEVEL_PARTICIPATING;
    }

    /**
     * This function is overridden from the parent class.
     */
    public static function get_name() {
        return get_string('event_assign_role', 'mod_adobeconnect');
    }

    /**
     * This function is overridden from the parent class.
     */
    public function get_description() {
        return "User assigned the {$this->other['rolename']} role to a user.";
    }
}