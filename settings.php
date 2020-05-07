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

defined('MOODLE_INTERNAL') || die;

global $PAGE;

// installation process that can't done in plugin install process moved here from db\install.php file
$param = array('shortname' => 'adobeconnectpresenter');
$mrole = $DB->get_record('role', $param);

if (!$mrole){
	// The commented out code is waiting for a fix for MDL-25709
    $result = true;
    $timenow = time();
    $sysctx = context_system::instance();
    $mrole = new stdClass();
    $levels = array(CONTEXT_COURSECAT, CONTEXT_COURSE, CONTEXT_MODULE);

    $param = array('shortname' => 'coursecreator');
    $coursecreator = $DB->get_records('role', $param, 'id ASC', 'id', 0, 1);
    if (empty($coursecreator)) {
        $param = array('archetype' => 'coursecreator');
        $coursecreator = $DB->get_records('role', $param, 'id ASC', 'id', 0, 1);
    }
    $coursecreatorrid = array_shift($coursecreator);

    $param = array('shortname' =>'editingteacher');
    $editingteacher = $DB->get_records('role', $param, 'id ASC', 'id', 0, 1);
    if (empty($editingteacher)) {
        $param = array('archetype' => 'editingteacher');
        $editingteacher = $DB->get_records('role', $param, 'id ASC', 'id', 0, 1);
    }
    $editingteacherrid = array_shift($editingteacher);

    $param = array('shortname' =>'teacher');
    $teacher = $DB->get_records('role', $param, 'id ASC', 'id', 0, 1);
    if (empty($teacher)) {
        $param = array('archetype' => 'teacher');
        $teacher = $DB->get_records('role', $param, 'id ASC', 'id', 0, 1);
    }
    $teacherrid = array_shift($teacher);

    // Fully setup the Adobe Connect Presenter role.
    $param = array('shortname' => 'adobeconnectpresenter');
    if (!$mrole = $DB->get_record('role', $param)) {

        if ($rid = create_role(get_string('adobeconnectpresenter', 'adobeconnect'), 'adobeconnectpresenter',
                               get_string('adobeconnectpresenterdescription', 'adobeconnect'), 'adobeconnectpresenter')) {

            $mrole = new stdClass();
            $mrole->id = $rid;
            $result = $result && assign_capability('mod/adobeconnect:meetingpresenter', CAP_ALLOW, $mrole->id, $sysctx->id);

            set_role_contextlevels($mrole->id, $levels);
        } else {
            $result = false;
        }
    }

    if (isset($coursecreatorrid->id)) {
        $param = array('allowassign' => $mrole->id, 'roleid' => $coursecreatorrid->id);
        if (!$DB->get_record('role_allow_assign', $param)) {
            core_role_set_assign_allowed($coursecreatorrid->id, $mrole->id);
        }
    }

    if (isset($editingteacherrid->id)) {
        $param = array('allowassign' => $mrole->id, 'roleid' => $editingteacherrid->id);
        if (!$DB->get_record('role_allow_assign', $param)) {
            core_role_set_assign_allowed($editingteacherrid->id, $mrole->id);
        }
    }

    if (isset($teacherrid->id)) {
        $param = array('allowassign' => $mrole->id, 'roleid' => $teacherrid->id);
        if (!$DB->get_record('role_allow_assign', $param)) {
            core_role_set_assign_allowed($teacherrid->id, $mrole->id);
        }
    }

    // Fully setup the Adobe Connect Participant role.
    $param = array('shortname' => 'adobeconnectparticipant');

    if ($result && !($mrole = $DB->get_record('role', $param))) {

        if ($rid = create_role(get_string('adobeconnectparticipant', 'adobeconnect'), 'adobeconnectparticipant',
                               get_string('adobeconnectparticipantdescription', 'adobeconnect'), 'adobeconnectparticipant')) {

            $mrole = new stdClass();
            $mrole->id  = $rid;
            $result = $result && assign_capability('mod/adobeconnect:meetingparticipant', CAP_ALLOW, $mrole->id, $sysctx->id);
            set_role_contextlevels($mrole->id, $levels);
        } else {
            $result = false;
        }
    }

    if (isset($coursecreatorrid->id)) {
        $param = array('allowassign' => $mrole->id, 'roleid' => $coursecreatorrid->id);
        if (!$DB->get_record('role_allow_assign', $param)) {
            core_role_set_assign_allowed($coursecreatorrid->id, $mrole->id);
        }
    }

    if (isset($editingteacherrid->id)) {
        $param = array('allowassign' => $mrole->id, 'roleid' => $editingteacherrid->id);
        if (!$DB->get_record('role_allow_assign', $param)) {
            core_role_set_assign_allowed($editingteacherrid->id, $mrole->id);
        }
    }

    if (isset($teacherrid->id)) {
        $param = array('allowassign' => $mrole->id, 'roleid' => $teacherrid->id);
        if (!$DB->get_record('role_allow_assign', $param)) {
            core_role_set_assign_allowed($teacherrid->id, $mrole->id);
        }
    }


    // Fully setup the Adobe Connect Host role.
    $param = array('shortname' => 'adobeconnecthost');
    if ($result && !$mrole = $DB->get_record('role', $param)) {
        if ($rid = create_role(get_string('adobeconnecthost', 'adobeconnect'), 'adobeconnecthost',
                               get_string('adobeconnecthostdescription', 'adobeconnect'), 'adobeconnecthost')) {

            $mrole = new stdClass();
            $mrole->id  = $rid;
            $result = $result && assign_capability('mod/adobeconnect:meetinghost', CAP_ALLOW, $mrole->id, $sysctx->id);
            set_role_contextlevels($mrole->id, $levels);
        } else {
            $result = false;
        }
    }

    if (isset($coursecreatorrid->id)) {
        $param = array('allowassign' => $mrole->id, 'roleid' => $coursecreatorrid->id);
        if (!$DB->get_record('role_allow_assign', $param)) {
            core_role_set_assign_allowed($coursecreatorrid->id, $mrole->id);
        }
    }

    if (isset($editingteacherrid->id)) {
        $param = array('allowassign' => $mrole->id, 'roleid' => $editingteacherrid->id);
        if (!$DB->get_record('role_allow_assign', $param)) {
            core_role_set_assign_allowed($editingteacherrid->id, $mrole->id);
        }
    }

    if (isset($teacherrid->id)) {
        $param = array('allowassign' => $mrole->id, 'roleid' => $teacherrid->id);
        if (!$DB->get_record('role_allow_assign', $param)) {
            core_role_set_assign_allowed($teacherrid->id, $mrole->id);
        }
    }
}

if ($ADMIN->fulltree) {
    require_once($CFG->dirroot . '/mod/adobeconnect/locallib.php');
    $PAGE->requires->js_init_call('M.mod_adobeconnect.init');

    $settings->add(new admin_setting_configtext('adobeconnect_host', get_string('host', 'adobeconnect'),
                       get_string('host_desc', 'adobeconnect'), 'localhost/api/xml', PARAM_URL));

    $settings->add(new admin_setting_configtext('adobeconnect_meethost', get_string('meetinghost', 'adobeconnect'),
                       get_string('meethost_desc', 'adobeconnect'), 'localhost', PARAM_URL));

    $settings->add(new admin_setting_configtext('adobeconnect_port', get_string('port', 'adobeconnect'),
                       get_string('port_desc', 'adobeconnect'), '80', PARAM_INT));

    $settings->add(new admin_setting_configtext('adobeconnect_admin_login', get_string('admin_login', 'adobeconnect'),
                       get_string('admin_login_desc', 'adobeconnect'), 'admin', PARAM_TEXT));

    $settings->add(new admin_setting_configpasswordunmask('adobeconnect_admin_password', get_string('admin_password', 'adobeconnect'),
                       get_string('admin_password_desc', 'adobeconnect'), ''));

    $settings->add(new admin_setting_configtext('adobeconnect_admin_httpauth', get_string('admin_httpauth', 'adobeconnect'),
                       get_string('admin_httpauth_desc', 'adobeconnect'), 'my-user-id', PARAM_TEXT));

    $settings->add(new admin_setting_configcheckbox('adobeconnect_email_login', get_string('email_login', 'adobeconnect'),
                       get_string('email_login_desc', 'adobeconnect'), '0'));

    $settings->add(new admin_setting_configcheckbox('adobeconnect_https', get_string('https', 'adobeconnect'),
                       get_string('https_desc', 'adobeconnect'), '0'));


    $url = $CFG->wwwroot . '/mod/adobeconnect/conntest.php';
    $url = htmlentities($url, ENT_COMPAT, 'UTF-8');
    $options = 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=700,height=300';
    $str = '<center><input type="button" onclick="window.open(\''.$url.'\', \'\', \''.$options.'\');" value="'.
           get_string('testconnection', 'adobeconnect') . '" /></center>';

    $settings->add(new admin_setting_heading('adobeconnect_test', '', $str));

    $param = new stdClass();
    $param->image = $CFG->wwwroot.'/mod/adobeconnect/pix/rl_logo.png';
    $param->url = 'https://moodle.org/plugins/view.php?plugin=mod_adobeconnect';

    $settings->add(new admin_setting_heading('adobeconnect_intro', '', get_string('settingblurb', 'adobeconnect', $param)));
}
