ECHO OFF
TITLE Develop Shelves — Stopping
ECHO We're going to run commands to stop the server so that no memory is taken
ECHO ---------------------------------------------------------------------------
ECHO Stopping Vagrant server...

vagrant suspend
ECHO --------------------------------------------------------------------------
ECHO Vagrant server has stopped. We're finished
PAUSE
