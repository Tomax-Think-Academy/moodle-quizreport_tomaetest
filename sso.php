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
 * version.php - version information.
 *
 * @package    quiz_tomaetest
 * @subpackage quiz
 * @copyright  2021 Tomax ltd <roy@tomax.co.il>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(__FILE__)) . '../../../../config.php');
defined('MOODLE_INTERNAL') || die();
require_login();
require_once($CFG->dirroot . "/mod/quiz/accessrule/tomaetest/rule.php");
global $DB, $CFG, $USER;

$id = optional_param('id', false, PARAM_INT);
$parid = optional_param('parID', null, PARAM_INT);


$users = quizaccess_tomaetest_utils::get_moodle_teachers($id, $USER->id);
$adminuser = quizaccess_tomaetest_utils::get_moodle_teachers(null, $USER->id);

// If he is there ,sync first.
if (!empty($adminuser) || !empty($users)) {
    tomaetest_connection::sync_to_toma_etest_from_database($id);
} else {
    echo "<script>alert('No Permission.')</script>";
    echo "<script>window.close();</script>";
    exit;
}
// If it is admin user.
if (!empty($adminuser)) {
    // If it cant SSO, create the user, and then continue to SSO.
    $data = quizaccess_tomaetest_utils::create_system_user($USER->id);
    if ($data !== true) {
        echo "<script>alert('$data')</script>";
        echo "<script>window.close();</script>";
        exit;
    }
}
$result = tomaetest_connection::sso($id, $USER->id, $parid);

if ($result === false) {
    // Check if admin privileges.
    echo "<script>alert('No Permission.')</script>";
    echo "<script>window.close();</script>";
    exit;
}

header("location: $result");
