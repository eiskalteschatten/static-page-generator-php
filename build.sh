#!/bin/bash

export ROOT_DIR="$(pwd)"
export FUNCTIONS_FILE="${ROOT_DIR}/lib/functions.php"

php bin/build.php
