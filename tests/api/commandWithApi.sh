#!/bin/bash
# Script for wrapping provided command with starting
# and stopping an api webserver for testing.
# Done in a script because of composer stopping the array of commands on
# a failed command, so on a failed test it would not kill the webserver.

# run the webserver serving the testing data as background task
mkdir -p temp/tester/log
export FLASK_APP=tests/api/api.py
nohup flask run -h 127.0.0.1 -p 8090 > temp/tester/log/api.log 2>&1 < /dev/null &
sleep 1

# execute main command and catch it's exit code
$*
EXIT_CODE=$?

# kill the webservr
kill -9 $(ps aux | grep 'flask run -h 127.0.0.1 -p 8090' | head -n1 | awk '{print $2}')

# exit with the same code as the main command did
exit $EXIT_CODE
