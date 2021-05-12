<?php

defined('MOODLE_INTERNAL') || die();

$plugin->version  = 2021013100;
//                  YYYYMMDDXX
$plugin->requires = 2010110800;
$plugin->component = 'quiz_tomaetest';
$plugin->maturity = MATURITY_STABLE;
$plugin->dependencies = array(
    'quizaccess_tomaetest' => ANY_VERSION
);
