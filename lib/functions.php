<?php

function getHeader(array $metaData) {
    require_once getenv('ROOT_DIR') .'/templates/header.php';
}

function getFooter() {
    require_once getenv('ROOT_DIR') .'/templates/footer.php';
}
