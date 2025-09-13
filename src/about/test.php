<?php
    require_once getenv('FUNCTIONS_PATH') ?: "../../functions.php";

    getHeader([
        'title' => 'About test',
        // 'description' => 'This is the homepage'
    ]);
?>

<div>
    This is the about page. test
</div>

<?php getFooter(); ?>
