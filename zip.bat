del  TET-report-moodle.zip
mkdir tomaetest
copy * tomaetest
Xcopy  /S /I /E lang  tomaetest\lang
rmdir tomaetest\tomaetest /s /q
tar.exe -a -c -f TET-report-moodle.zip tomaetest
rmdir tomaetest /s /q