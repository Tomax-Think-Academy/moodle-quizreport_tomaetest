<?php

//SSO Integrity Management
require_once(dirname(dirname(__FILE__)) . '../../../../config.php');
defined('MOODLE_INTERNAL') || die();
require_login();
require_once($CFG->dirroot . "/mod/quiz/accessrule/tomaetest/rule.php");
global $DB, $CFG, $USER;

// check if it is not an admin user.
$users = quizaccess_tomaetest_utils::getMoodleAllowedIntegrityManagement($USER->id);
if (empty($users)) {
    echo "<script>alert('No Permission.')</script>";
    echo "<script>window.close();</script>";
    exit;
}
//Try to SSO.
$result = tomaetest_connection::ssoIntegrityManagement($USER->id);
if ($result !== false) {
    header("location: $result");
    exit;
}
//Create the System User if not exists.
$data = quizaccess_tomaetest_utils::createSystemUser($USER->id);

if ($data !== true) {
    echo "<script>alert('$data')</script>";
    echo "<script>window.close();</script>";
    exit;
}
// SSO.
$result = tomaetest_connection::ssoIntegrityManagement($USER->id);
// var_dump($result);
if ($result !== false) {
    header("location: $result");
    exit;
}

echo "<script>alert('Operation failed, please contact the system administrator.')</script>";
echo "<script>window.close();</script>";
