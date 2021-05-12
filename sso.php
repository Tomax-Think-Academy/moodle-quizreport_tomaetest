<?php

require_once(dirname(dirname(__FILE__)) . '../../../../config.php');
defined('MOODLE_INTERNAL') || die();
require_login();
require_once($CFG->dirroot . "/mod/quiz/accessrule/tomaetest/rule.php");
global $DB, $CFG, $USER;

$id = $_GET["id"];
$parID = isset($_GET["parID"]) ? $_GET["parID"] : null;


$users = quizaccess_tomaetest_utils::getMoodleTeachers($id, $USER->id);
$adminUser = quizaccess_tomaetest_utils::getMoodleTeachers(null, $USER->id);

// if he is there ,sync first..
if (!empty($adminUser) || !empty($users)) {
    tomaetest_connection::syncToTomaETestFromDatabase($id);
} else {
    echo "<script>alert('No Permission.')</script>";
    echo "<script>window.close();</script>";
    exit;
}
// if it is admin user.
if (!empty($adminUser)) {
    //If it cant SSO, create the user, and then continue to SSO.
    $data = quizaccess_tomaetest_utils::createSystemUser($USER->id);
    if ($data !== true) {
        echo "<script>alert('$data')</script>";
        echo "<script>window.close();</script>";
        exit;
    }
}
$result = tomaetest_connection::sso($id, $USER->id, $parID);

if ($result === false) {
    // Check if admin priviliges
    echo "<script>alert('No Permission.')</script>";
    echo "<script>window.close();</script>";
    exit;
}

header("location: $result");
