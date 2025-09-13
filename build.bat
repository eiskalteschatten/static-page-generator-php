@echo off

set ROOT_DIR=%cd%
set FUNCTIONS_FILE=%ROOT_DIR%\lib\functions.php

php bin/build.php
