<?php
    $root = realpath($_SERVER['DOCUMENT_ROOT']);
    require_once "${root}/functions.php";

    getHeader([
        // 'title' => 'Home',
        // 'description' => 'This is the homepage'
    ]);
?>

<div>
    This is the homepage
</div>

<?php getFooter(); ?>
