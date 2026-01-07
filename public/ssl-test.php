<?php
    $ch = curl_init("https://oauth2.googleapis.com/token");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);

    if (curl_errno($ch)) {
        echo "ERROR: " . curl_error($ch);
    } else {
        echo "SSL WORKING ✅";
    }
?>
