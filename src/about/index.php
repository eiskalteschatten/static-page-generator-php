<?php
    require_once getenv('FUNCTIONS_PATH') ?: "../../functions.php";

    getHeader([
        'title' => 'About',
        // 'description' => 'This is the homepage'
    ]);
?>

<div>
    This is the about page.
</div>

<?php getFooter(); ?>
