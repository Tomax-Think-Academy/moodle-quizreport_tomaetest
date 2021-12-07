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

// SSO Integrity Management.
require_once(dirname(dirname(__FILE__)) . '../../../../config.php');
defined('MOODLE_INTERNAL') || die();
require_login();
require_once($CFG->dirroot . "/mod/quiz/accessrule/tomaetest/rule.php");
global $DB, $CFG, $USER;

// Check if it is not an admin user.
$users = quizaccess_tomaetest_utils::getMoodleAllowedIntegrityManagement($USER->id);
if (empty($users)) {
    echo "<script>alert('No Permission.')</script>";
    echo "<script>window.close();</script>";
    exit;
}
// Try to SSO.
$result = tomaetest_connection::ssoIntegrityManagement($USER->id);
if ($result !== false) {
    header("location: $result");
    exit;
}
// Create the System User if not exists.
$data = quizaccess_tomaetest_utils::createSystemUser($USER->id);

if ($data !== true) {
    echo "<script>alert('$data')</script>";
    echo "<script>window.close();</script>";
    exit;
}
// SSO.
$result = tomaetest_connection::ssoIntegrityManagement($USER->id);
if ($result !== false) {
    header("location: $result");
    exit;
}

echo "<script>alert('Operation failed, please contact the system administrator.')</script>";
echo "<script>window.close();</script>";
