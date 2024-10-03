<?php
    $defaultTitle = 'My Static Site';
    $title = isset($metaData['title']) ? "{$metaData['title']} - ${defaultTitle}" : $defaultTitle;
    $description = isset($metaData['description']) ? $metaData['description'] : 'My Static Site';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?=$description?>">
    <title><?= $title ?></title>
</head>
<body>
