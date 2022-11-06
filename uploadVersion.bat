
aws s3 cp ./TET-report-moodle.zip s3://downloads-tomax-io/MoodlePlugins/ETest/TET-report-moodle.zip
aws s3api put-object-acl --bucket downloads-tomax-io --key MoodlePlugins/ETest/TET-report-moodle.zip --acl public-read

echo You can test the link now - https://s3.eu-west-1.amazonaws.com/downloads-tomax-io/MoodlePlugins/ETest/TET-report-moodle.zip
pause
