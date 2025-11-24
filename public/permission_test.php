<?php
// Test file permissions
$testFile = __DIR__ . '/store/reels/permission_test.txt';
$content = 'Permission test at ' . date('Y-m-d H:i:s');

try {
    // Test write
    file_put_contents($testFile, $content);
    echo "Write test: SUCCESS\n";
    
    // Test read
    $readContent = file_get_contents($testFile);
    echo "Read test: SUCCESS\n";
    
    // Test delete
    unlink($testFile);
    echo "Delete test: SUCCESS\n";
    
    // Test directory creation
    $testDir = __DIR__ . '/store/reels/test_dir';
    mkdir($testDir);
    echo "Directory creation test: SUCCESS\n";
    rmdir($testDir);
    echo "Directory removal test: SUCCESS\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
