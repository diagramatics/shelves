ECHO OFF
TITLE Develop Shelves - Starting
ECHO We're going to run commands to get the local server up and running!
ECHO ---------------------------------------------------------------------------
ECHO Starting Vagrant server...

vagrant up
ECHO --------------------------------------------------------------------------
ECHO Vagrant server is started. Now running Gulp to track files and live reloads.
ECHO NOTE: When you finished developing, close the command line and run stop-dev.bat
gulp serve
