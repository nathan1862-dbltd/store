<?php

$apiKey = "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";

$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => "https://openrouter.ai/api/v1/models",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer " . $apiKey
    ]
]);

$response = curl_exec($ch);

echo "<pre>";
print_r(json_decode($response, true));
echo "</pre>";
