# Tomax’s proctoring report plugin 
This plugin is dependent on the Tomax’s proctoring plugin.

Tomax’s proctoring plugin ensure exam integrity and provide faculty and students with a better experience online & offline.
TomaEtest’s solution enables instructors to efficiently and pleasantly manage and secure the exam process.

TomaEtest’s plugin integrates with the Moodle exam and offers seamless integration.
Contact us for a free trial with full access to all features at sales@tomaxltd.com

* **Test anytime, from anywhere**
Students can take the exam on their device or the institution’s device.
TomaEtest can lock the computer both online and offline to prevent students from using unauthorized applications.

* **Real-time monitoring**
AI-based video & audio proctoring engine, screen recording,
and integrity reports allow you to detect when there is any suspicion of deception.

* **Advanced communication**
Instructors, invigilators, and students can safely communicate with each other during the exam.

# Features

* Integrity reports
* Student identity verification
* Complete computer lockdown
* Screen recording
* Artificial Intelligence proctoring engine
* Live and real-time monitoring
* Secure messaging during the exam
* ISO certification

# Installation
Download the two plugins and install them through the moodle user interface
1. [TomaETest quiz access rule](https://s3.eu-central-1.amazonaws.com/public.tomagrade.com/ApplicationSetup/WindowsSetup/TET-accessrule-moodle.zip)
2. [TomaETest quiz report](https://s3.eu-central-1.amazonaws.com/public.tomagrade.com/ApplicationSetup/WindowsSetup/TET-report-moodle.zip)

## Setup
Go to "Site Administration" > "Plugins" > "Activity modules" > "Quiz" > "TomaETest quiz access"
1. Configure the Domain,UserID, and APIKey which has been given from Tomax’s side.
2. Add the student disclaimer, this disclaimer will b signed by the user before entering an exam.
3. Configure the default teacher identifier to be passed to TomaETest.
4. Configure the default student identifier to be passed to TomaETest.
5. Define the application permissions which should be enforced during an exam, e.g. "AnyDesk - Deny Access"

### <U>TomaGrade System Configurations</U>
configure only if using the Scanning module
1. Configure the Domain,UserID, and APIKey which has been given from Tomax’s side.
2. Configure if the Scanned exams should be ID Matched (place as true if using scanning module).

### <U>TomaETest Defaults</U>
1. Default TomaETest enable proctoring - Should TomaETest be enabled as default
2. Default Show Participant on screen - Should the participant see himself during the exam
3. Default Lock Computer - There are three options for locking the computer:
	1. Without
	2. Soft lock
	3. Hard lock 
4. Default Verification Timing - When should the student be verified:
	1. Without - No verification at all.
	2. Before exam - Before starting the exam.
	3. After exam - After the exam has been started, **This means the user can start the exam without being verified**.
5. Default Verification Types - How should the student be verified:
	1. Without - No verification at all.
	2. Manual - The verification will be manual without any identification.
	3. Password -  The verification will be with a password which is accessible to the teacher/monitor.
	4. Camera - The verification will be with the students camera.
	   It will ask the student to take a picture of his face and his identification card,
	   then it will be sent to verification to the monitor side.
6. Proctoring Type
	1. Computer Camera - The frontal computer camera, this will record the student’s actions during the exam using his frontal camera and later on will be analyzed using an AI.
	2. Monitor Recording - The computer’s screen will be recorded and later on analyzed using an AI.

### <u>TomaETest Dashboard Permission</u>
1. In order to allow certain roles to access the TomaETest Monitor, please allow the following capability “mod/quizaccess_tomaetest:viewtomaetestmonitor to the appropriate roles
2. In order to allow certain roles to access the TomaETest Advanced Integrity Report, please allow the following capability “mod/quizaccess_tomaetest:viewtomaetestair to the appropriate roles

# Usage
## Creating a Quiz
Upon creating a quiz under “Extra restrictions on attempts” you will have all the TomaETest proctoring as a service settings enabled to be edited (please note the settings that have been defined above).

## Using the scanning module
To use the scanning module you will need to have the TomaGrade settings configured and enabled the “Scanned exams should be ID Matched”.
After doing so, when configuring the quiz, you will need to choose “Use TomaETest Scanning module”,
Then choose the Realted TomaETest User and afterwards choose the Exam which should already exist on TomaGrade.
After the exam has ended, the teacher can go over to “TomaETest Dashboard” and click on “View in TomaGrade”

## Monitoring 
When an exam has been enabled with TomaETest Proctoring, you will be able to move to the monitor dashboard using the report “TomaETest Dashboard”.
You will then have the list of students with the appropriate Integrity score, and a button to move to the TomaETest Dashboard.

## Participating in a quiz
When the quiz is ready to be taken, the student will have a button called “Click to launch TomaETest client”.
After clicking, the TomaETest client will open up and he will be able to start using TomaETest.

# Subscription
This plugin integrates with Tomax TomaETTest solution which is provided as a subscription.
Receive a free trial with full access to all Tomax’s features.
For more information please contact us at sales@tomaxltd.com
for experiencing with a demo version you can use

# Dependencies
Tomax Proctoring plugin.
