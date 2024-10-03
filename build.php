<?php

$rootDir = __DIR__;
$distDir = __DIR__ . DIRECTORY_SEPARATOR . 'public';
$timestamp = time();

// TODO: copy assets to the dist folder and add a unix timestamp to the file name. Also update the getAsset function to use the timestamp.

function compilePages() {
    global $rootDir, $distDir, $timestamp;

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $file) {
        if ($file->isDir()) {
            $indexPath = $file->getPathname() . DIRECTORY_SEPARATOR . 'index.php';
            if (file_exists($indexPath)) {
                $output = shell_exec("TIMESTAMP={$timestamp} php {$indexPath}");
                $relativePath = str_replace($rootDir, '', $file->getPathname());
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

compilePages();
