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
        }
        else {
            unlink($file->getPathname());
        }
    }

    return rmdir($folderPath);
}

function copyAssets() {
    global $publicDir, $timestamp;
    $assetsDir = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . '_assets';
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
    $srcDir = __DIR__ . DIRECTORY_SEPARATOR . 'src';

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($srcDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $phpFilePath = $file->getPathname();

            // Skip files in the _assets directory
            if (strpos($phpFilePath, DIRECTORY_SEPARATOR . '_assets' . DIRECTORY_SEPARATOR) !== false) {
                continue;
            }

            $functionsPath = realpath($_SERVER['DOCUMENT_ROOT']) . DIRECTORY_SEPARATOR . 'functions.php';
            putenv("FUNCTIONS_PATH={$functionsPath}");
            putenv("TIMESTAMP={$timestamp}");
            $output = shell_exec("php {$phpFilePath}");

            // Calculate the relative path from src directory
            $relativePath = str_replace($srcDir, '', dirname($phpFilePath));

            // Get the base filename without extension
            $baseFileName = $file->getBasename('.php');

            // Create the output path with .html extension
            $outputDir = $publicDir . $relativePath;
            $outputFilePath = $outputDir . DIRECTORY_SEPARATOR . $baseFileName . '.html';

            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0777, true);
            }

            file_put_contents($outputFilePath, $output);
            echo $phpFilePath . ' -> ' . $outputFilePath . PHP_EOL;
        }
    }
}

copyAssets();
compilePages();
