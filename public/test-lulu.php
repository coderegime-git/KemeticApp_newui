<?php
// test-lulu.php
$clientKey = '9f605b15-6c3c-49e5-919b-84f7341a2283';
$clientSecret = '20aiFIjqs1ZnCRFBkcbRLIxUUX83ogIp';

$tokenUrl = 'https://api.sandbox.lulu.com/auth/realms/glasstree/protocol/openid-connect/token';

$authHeader = base64_encode($clientKey . ':' . $clientSecret);

$data = [
    'grant_type' => 'client_credentials'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $tokenUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded',
    'Authorization: Basic ' . $authHeader
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL for testing

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n";

curl_close($ch);
?>