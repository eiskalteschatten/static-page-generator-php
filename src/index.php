<?php
    require_once getenv('FUNCTIONS_PATH') ?: "../functions.php";

    getHeader([
        // 'title' => 'Home',
        // 'description' => 'This is the homepage'
    ]);
?>

<div>
    This is the homepage
</div>

<?php getFooter(); ?>
