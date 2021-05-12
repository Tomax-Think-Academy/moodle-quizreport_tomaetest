<?php

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

// function get_user_attempts($quizID, $courseID)
// {
//     global $DB;

//     $sql = "SELECT DISTINCT CONCAT(u.id, '#', COALESCE(qa.id, 0)) AS uniqueid,
//         				quiza.uniqueid 		AS quizuniqueid,
//         				quiza.id 			AS quizattemptid,
//         				quiza.attempt 		AS userattemptnum,		/*1*/
//         				u.id 				AS userid,
//         				u.username,									/*2*/
//         				u.idnumber, u.firstnamephonetic, u.lastnamephonetic, u.middlename, u.alternatename, u.firstname, u.lastname,
//         				qa.id 				AS questionattemptid,	/*3*/
//         				qa.questionusageid 	AS qubaid,				/*4*/
//         				qa.slot,									/*5*/
//         				qa.questionid,								/*6*/
//         				quiza.state,
//         				quiza.timefinish,
//         				quiza.timestart,
// 				        CASE WHEN quiza.timefinish = 0
// 				        		THEN null
// 				        	 WHEN quiza.timefinish > quiza.timestart
// 				        	 	THEN quiza.timefinish - quiza.timestart
// 				        	 ELSE 0
// 				        END AS duration
// 		        FROM		{user} 				u
// 		        LEFT JOIN 	{quiz_attempts} 	quiza	ON	quiza.userid 		= u.id
// 		        										AND quiza.quiz 			= $quizID
// 		        JOIN 		{question_attempts} qa 		ON	qa.questionusageid	= quiza.uniqueid
// 		        WHERE
// 		        	quiza.preview = 0
// 		        	AND quiza.id IS NOT NULL
// 		        	AND u.deleted = 0";
//     $user_attempts = $DB->get_records_sql($sql);

//     return $user_attempts;
// }
// $quizid = 95;

// $CMID = quizaccess_tomaetest_utils::getCMID($quizid);

// // $cm = quizaccess_tomaetest_utils::get_coursemodule($CMID);
// $quiz = quizaccess_tomaetest_utils::get_quiz($quizid);
// $course = quizaccess_tomaetest_utils::get_course($CMID);
// $userAttempts = get_user_attempts($quizid,$course->id);
// var_dump($userAttempts);
