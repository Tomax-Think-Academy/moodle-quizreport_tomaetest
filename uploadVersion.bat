
aws s3 cp ./TET-report-moodle.zip s3://capsule.public/MoodlePlugins/ETest/TET-report-moodle.zip
aws s3api put-object-acl --bucket capsule.public --key MoodlePlugins/ETest/TET-report-moodle.zip --acl public-read

echo You can test the link now - https://s3.eu-west-1.amazonaws.com/capsule.public/MoodlePlugins/ETest/TET-report-moodle.zip
pause
