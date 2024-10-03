<?php
    $defaultTitle = 'My Static Site';
    $title = isset($metaData['title']) ? "{$metaData['title']} - ${defaultTitle}" : $defaultTitle;
    $description = isset($metaData['description']) ? $metaData['description'] : 'My Static Site';

    function getAsset(string $path) {
        // TODO: figure out the difference between built and not built assets
        return "/assets/{$path}";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Content-Language" content="en">
    <meta name="author" content="Alex Seifert">
    <meta name="copyright" content="Copyright (c) Alex Seifert">
    <meta name="theme-color" content="#fbbc05">
    <meta name="color-scheme" content="light dark">
    <meta name="description" content="<?=$description?>">
    <title><?= $title ?></title>

    <link rel="stylesheet" href="<?=getAsset('css/main.css')?>" type="text/css">
</head>
<body>
