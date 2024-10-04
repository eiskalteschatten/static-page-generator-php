<?php
$publicDir = __DIR__ . DIRECTORY_SEPARATOR . 'public';
$timestamp = time();

function deleteFolder($folderPath) {
    if (!is_dir($folderPath)) {
        return false;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($folderPath, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($iterator as $file) {
        if ($file->isDir()) {
            rmdir($file->getPathname());
        } else {
            unlink($file->getPathname());
        }
    }

    return rmdir($folderPath);
}

function copyAssets() {
    global $publicDir, $timestamp;
    $assetsDir = __DIR__ . DIRECTORY_SEPARATOR . 'assets';
    $jsonContent = file_get_contents($assetsDir . DIRECTORY_SEPARATOR . 'assets.json');

    if ($jsonContent === false) {
        die("Error reading the assets JSON file.");
    }

    $assets = json_decode($jsonContent, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Error parsing JSON: " . json_last_error_msg());
    }

    $dirs = array_keys($assets);

    foreach ($dirs as $dir) {
        $destDir = $publicDir . DIRECTORY_SEPARATOR . $dir;
        deleteFolder($destDir);

        foreach ($assets[$dir] as $file) {
            $originalFilePath = $assetsDir . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $file;
            $outputFilePath = $destDir . DIRECTORY_SEPARATOR . $file;
            $outputFilePathWithTimestamp = preg_replace('/(\.[^.]+)$/', "-$timestamp$1", $outputFilePath);

            if (!is_dir(dirname($outputFilePathWithTimestamp))) {
                mkdir(dirname($outputFilePathWithTimestamp), 0777, true);
            }

            copy($originalFilePath, $outputFilePathWithTimestamp);
            echo $originalFilePath . ' -> ' . $outputFilePathWithTimestamp . PHP_EOL;
        }
    }
}

function compilePages() {
    global $publicDir, $timestamp;

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(__DIR__, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($iterator as $file) {
        if ($file->isDir()) {
            $indexPath = $file->getPathname() . DIRECTORY_SEPARATOR . 'index.php';
            if (file_exists($indexPath)) {
                $functionsPath = realpath($_SERVER['DOCUMENT_ROOT']) . DIRECTORY_SEPARATOR . 'functions.php';
                putenv("FUNCTIONS_PATH={$functionsPath}");
                putenv("TIMESTAMP={$timestamp}");
                $output = shell_exec("php {$indexPath}");

                $relativePath = str_replace(__DIR__, '', $file->getPathname());
                $relativePath = str_replace('/pages', '', $relativePath);
                $outputFilePath = $publicDir . $relativePath . DIRECTORY_SEPARATOR . 'index.html';

                if (!is_dir(dirname($outputFilePath))) {
                    mkdir(dirname($outputFilePath), 0777, true);
                }

                file_put_contents($outputFilePath, $output);
                echo $indexPath . ' -> ' . $outputFilePath . PHP_EOL;
            }
        }
    }
}

copyAssets();
compilePages();
