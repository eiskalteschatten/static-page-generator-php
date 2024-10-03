<?php
    $root = realpath($_SERVER['DOCUMENT_ROOT']);
    require_once "${root}/functions.php";

    getHeader([
        'title' => 'About',
        // 'description' => 'This is the homepage'
    ]);
?>

<div>
    This is the about page.
</div>

<?php getFooter(); ?>
