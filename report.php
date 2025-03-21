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

require_once($CFG->dirroot . "/mod/quiz/accessrule/tomaetest/rule.php");
// This work-around is required until Moodle 4.2.
if (class_exists('\mod_quiz\local\reports\report_base')) {
    class_alias('\mod_quiz\local\reports\report_base', '\report_base_parent_class_alias');
} else {
    require_once($CFG->dirroot . '/mod/quiz/accessrule/accessrulebase.php');
    require_once($CFG->dirroot . '/mod/quiz/report/attemptsreport.php');
    require_once($CFG->dirroot . '/mod/quiz/report/reportlib.php');
    require_once($CFG->dirroot . '/mod/quiz/locallib.php');
    require_once($CFG->dirroot . '/mod/quiz/attemptlib.php');
    require_once($CFG->libdir . '/pagelib.php');
    class_alias('\quiz_default_report', '\report_base_parent_class_alias');
}

class quiz_tomaetest_report extends report_base_parent_class_alias
{

    protected $cm;
    protected $quiz;
    protected $course;

    public function display($quiz, $cm, $course) {
        global $PAGE, $USER;
        $this->quiz = $quiz;
        $this->cm = $cm;
        $this->course = $course;

        $this->print_header_and_tabs($cm, $course, $quiz, 'archive');
        if (quizaccess_tomaetest_utils::get_moodle_teachers($quiz->id, $USER->id)) {

            $this->display_archive($quiz);
            return true;
        } else {
            echo "<p> <b>No permissions to view this page.</b></p>";

        }
    }

    /**
     * Display all attempts.
     */
    protected function display_archive($quiz) {
        global $OUTPUT, $PAGE, $USER;
        if (quizaccess_tomaetest_utils::is_etest_plugin_enabled()) {

            $record = quizaccess_tomaetest_utils::get_etest_quiz($quiz->id);
            if ($record != false) {
                $logintopanel = new moodle_url('/mod/quiz/report/tomaetest/sso.php', array('id' => $this->quiz->id));
                $logintograde = new moodle_url('/mod/quiz/report/tomaetest/ssoTG.php');
                echo "<a target='_blank' href='$logintopanel'>Click here to start TomaETest Monitor</a></br>";
                $extradata = $record->extradata;
                if (isset ($extradata["IDMatch"]) && $extradata["IDMatch"] === true && isset($extradata["TeacherID"])) {
                    if ($USER->id === $extradata["TeacherID"]) {
                        echo "<a target='_blank' href='$logintograde'>Click here to start TomaGrade.</a></br>";
                    }
                };
                echo "<p>The Exam Code is <b>" . $record->extradata["TETExamLink"] . "</b> .";
                $table = new html_table();
                $table->attributes['style'] = 'width:500px;';
                $info = tomaetest_connection::get_participants_list($record->extradata["TETID"]);
                $table->head = ["Participant", "Integrity score"];
                foreach ($info["data"]["participants"] as $participant) {
                    $score = "Waiting for score";
                    $participantid = $participant["ParticipantID"];
                    if (isset($participant["parExamAtts"]["TETExamParticipantSystemScore"]["key"])) {
                        $score = $participant["parExamAtts"]["TETExamParticipantSystemScore"]["key"];
                    }
                    if (isset($participant["parExamAtts"]["TETExamParticipantUserScore"]["key"])) {
                        $score = $participant["parExamAtts"]["TETExamParticipantUserScore"]["key"];
                    }
                    $logintoparticipant = new moodle_url('/mod/quiz/report/tomaetest/sso.php',
                     array('id' => $this->quiz->id, 'parID' => $participantid));
                    $score = "<a target='_blank' href='$logintoparticipant'>$score</a>";
                    $table->data[] = array($participant["UserName"], $score);
                }
                echo html_writer::table($table);
                return;
            }
            echo "<p>The plugin is not enabled or the exam does not have TomaETest Proctoring enabled.</p>";
        }
    }

    protected function quizreportgetstudentandattempts($quiz) {
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
