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

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/quiz/report/attemptsreport.php');
require_once($CFG->dirroot . '/mod/quiz/report/reportlib.php');
require_once($CFG->dirroot . '/mod/quiz/locallib.php');
require_once($CFG->dirroot . '/mod/quiz/attemptlib.php');
require_once($CFG->libdir . '/pagelib.php');
require_once($CFG->dirroot . "/mod/quiz/accessrule/tomaetest/rule.php");

// require_once($CFG->dirroot . '/mod/quiz/report/responses/responses_options.php');
// require_once($CFG->dirroot . '/mod/quiz/report/responses/responses_form.php');
// require_once($CFG->dirroot . '/mod/quiz/report/responses/last_responses_table.php');
// require_once($CFG->dirroot . '/mod/quiz/report/responses/first_or_all_responses_table.php');

class quiz_tomaetest_report extends quiz_default_report
{

    protected $cm;
    protected $quiz;
    protected $course;

    public function display($quiz, $cm, $course)
    {
        global $PAGE, $USER;
        $this->quiz = $quiz;
        $this->cm = $cm;
        $this->course = $course;

        $this->print_header_and_tabs($cm, $course, $quiz, 'archive');
        if (quizaccess_tomaetest_utils::getMoodleTeachers($quiz->id, $USER->id)) {



            $this->display_archive($quiz);
            return true;
        }else{
            echo "<p> <b>No permissions to view this page.</b></p>";

        }
    }

    /**
     * Display all attempts.
     */
    protected function display_archive($quiz)
    {
        global $OUTPUT, $PAGE,$USER;
        if (quizaccess_tomaetest_utils::isETestPluginEnabled()) {

            $record = quizaccess_tomaetest_utils::get_etest_quiz($quiz->id);
            if ($record != false) {
                $loginToPanel = new moodle_url('/mod/quiz/report/tomaetest/sso.php', array('id' => $this->quiz->id));
                $loginToGrade = new moodle_url('/mod/quiz/report/tomaetest/ssoTG.php');
                echo "<a target='_blank' href='$loginToPanel'>Click here to start TomaETest Monitor</a></br>";
                $extradata = $record->extradata;
                 if(isset ($extradata["IDMatch"]) && $extradata["IDMatch"] === true && isset($extradata["TeacherID"])){
                     if ($USER->id === $extradata["TeacherID"]){
                        echo "<a target='_blank' href='$loginToGrade'>Click here to start TomaGrade.</a></br>";
                     }
                 };
                // var_dump()
                echo "<p>The Exam Code is <b>" . $record->extradata["TETExamLink"] . "</b> .";
                $table = new html_table();
                // $reflector = new \ReflectionClass('html_table');
                // echo $reflector->getFileName();
                $table->attributes['style'] = 'width:500px;';
                $info = tomaetest_connection::getParticipantsList($record->extradata["TETID"]);
                $table->head = ["Participant", "Integrity score"];
                foreach ($info["data"]["participants"] as $participant) {
                    $score = "Waiting for score";
                    $participantID = $participant["ParticipantID"];
                    if (isset($participant["parExamAtts"]["TETExamParticipantSystemScore"]["key"])) {
                        $score = $participant["parExamAtts"]["TETExamParticipantSystemScore"]["key"];
                    }
                    if (isset($participant["parExamAtts"]["TETExamParticipantUserScore"]["key"])) {
                        $score = $participant["parExamAtts"]["TETExamParticipantUserScore"]["key"];
                    }
                    $loginToParticipant = new moodle_url('/mod/quiz/report/tomaetest/sso.php', array('id' => $this->quiz->id, 'parID' => $participantID));
                    $score = "<a target='_blank' href='$loginToParticipant'>$score</a>";
                    $table->data[] = array($participant["UserName"], $score);
                    //$participant["UserName"]
                    // Low
                }
                echo html_writer::table($table);
                return;
                // exit;
            }
            echo "<p>The plugin is not enabled or the exam does not have TomaETest Proctoring enabled.</p>";
        }
        // echo "<a href=''>Click here to see advanced integrity report</a>";
    }

    protected function quizreportgetstudentandattempts($quiz)
    {
        global $DB;

        // Construct the SQL.
        $sql = "SELECT DISTINCT u.id userid, u.firstname, u.lastname, quiza.id attemptid FROM {user} u " .
            "LEFT JOIN {quiz_attempts} quiza " .
            "ON quiza.userid = u.id WHERE quiza.quiz = :quizid ORDER BY u.lastname ASC, u.firstname ASC";
        $params = array('quizid' => $this->quiz->id);
        $results = $DB->get_records_sql($sql, $params);
        $students = array();
        foreach ($results as $result) {
            array_push($students, array('userid' => $result->userid, 'attemptid' => $result->attemptid));
        }
        return $students;
    }
}
