<?php

require_once(dirname(dirname(__FILE__)) . '../../../../config.php');
defined('MOODLE_INTERNAL') || die();
require_login();
require_once($CFG->dirroot . "/mod/quiz/accessrule/tomaetest/rule.php");


$connection = new tet_plugin_tomagrade_connection;
$result = $connection->teacherLogin($USER->id);

if (!isset($result["Token"]) || !isset($result["UserID"])){
    echo ("<script>alert('There was an error, Please contact a system administrator.');</script>");
    echo ("<script>window.close();</script>");
    exit();
}

$domain = tomaetest_connection::$config->domain;
$url = "https://$domain.tomagrade.com/TomaGrade/Server/php/SAMLLogin.php/" . $result["Token"] . "/" . $result["UserID"];

header("location: $url");