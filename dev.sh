#!/bin/bash

export ROOT_DIR="$(pwd)"
export FUNCTIONS_FILE="${ROOT_DIR}/lib/functions.php"
export DEV_MODE=true

cd src
php -S localhost:8080
