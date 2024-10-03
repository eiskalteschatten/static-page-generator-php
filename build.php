<?php

$distDir = __DIR__ . DIRECTORY_SEPARATOR . 'public';
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
    global $distDir, $timestamp;
    $assetsDir = __DIR__ . DIRECTORY_SEPARATOR . 'assets';

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($assetsDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    deleteFolder($distDir . DIRECTORY_SEPARATOR . 'css');
    deleteFolder($distDir . DIRECTORY_SEPARATOR . 'js');

    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $relativePath = str_replace($assetsDir, '', $file->getPathname());
            $outputFilePath = $distDir . $relativePath;
            $outputFilePathWithTimestamp = preg_replace('/(\.[^.]+)$/', "-$timestamp$1", $outputFilePath);

            if (!is_dir(dirname($outputFilePathWithTimestamp))) {
                mkdir(dirname($outputFilePathWithTimestamp), 0777, true);
            }

            copy($file->getPathname(), $outputFilePathWithTimestamp);
            echo $file->getPathname() . ' -> ' . $outputFilePathWithTimestamp . PHP_EOL;
        }
    }
}

function compilePages() {
    global $distDir, $timestamp;

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(__DIR__, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($iterator as $file) {
        if ($file->isDir()) {
            $indexPath = $file->getPathname() . DIRECTORY_SEPARATOR . 'index.php';
            if (file_exists($indexPath)) {
                $output = shell_exec("TIMESTAMP={$timestamp} php {$indexPath}");
                $relativePath = str_replace(__DIR__, '', $file->getPathname());
                $relativePath = str_replace('/pages', '', $relativePath);
                $outputFilePath = $distDir . $relativePath . DIRECTORY_SEPARATOR . 'index.html';

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
