<?php

// Test configuration
$apiUrl = 'http://local.kemetic.dev/api/development/reels';
$testFile = fopen(__DIR__ . '/test-video.txt', 'w');
fwrite($testFile, "This is a test file to simulate a video upload");
fclose($testFile);
$videoPath = __DIR__ . '/test-video.txt';
$token = 'YOUR_AUTH_TOKEN'; // You'll need to replace this with a valid token

// Prepare the request
$curl = curl_init();
$postData = [
    'title' => 'Test Reel ' . date('Y-m-d H:i:s'),
    'caption' => 'This is a test reel upload',
    'video' => new CURLFile($videoPath, 'video/mp4', 'test-video.mp4')
];

curl_setopt_array($curl, [
    CURLOPT_URL => $apiUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $postData,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $token,
        'Accept: application/json',
        'X-API-KEY: ' . getenv('API_KEY')
    ]
]);

// Execute the request
echo "Uploading test video...\n";
$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

// Check for errors
if (curl_errno($curl)) {
    echo 'Curl error: ' . curl_error($curl) . "\n";
} else {
    echo "HTTP Response Code: " . $httpCode . "\n";
    echo "Response:\n" . json_encode(json_decode($response), JSON_PRETTY_PRINT) . "\n";
}

curl_close($curl);

// Clean up test video
unlink($videoPath);

echo "\nTest completed.\n";
