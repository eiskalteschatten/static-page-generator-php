@echo off

set ROOT_DIR=%cd%
set FUNCTIONS_FILE=%ROOT_DIR%\lib\functions.php
set DEV_MODE=true

cd src
php -S localhost:8080
