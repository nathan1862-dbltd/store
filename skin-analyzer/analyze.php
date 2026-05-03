<?php

header('Content-Type: text/html');

if(!isset($_FILES['image'])){
    die("No image uploaded");
}

$uploadDir = "uploads/";

if(!is_dir($uploadDir)){
    mkdir($uploadDir,0777,true);
}

$fileName = time() . "_" . basename($_FILES["image"]["name"]);
$targetFile = $uploadDir . $fileName;

move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);

$imageData = base64_encode(file_get_contents($targetFile));

$apiKey = "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";


$payload = [
    "model" => "google/gemma-3-27b-it:free",
    "messages" => [
        [
            "role" => "user",
            "content" => [
                [
                    "type" => "text",
                    "text" => "Analyze this facial skin image and return professional HTML only.

Include:
- Skin Type
- Acne
- Pores
- Pigmentation
- Dark Circles
- Hydration
- Texture
- Fine Lines
- Skin Health Score (/10)

Then provide skincare recommendations."
                ],
                [
                    "type" => "image_url",
                    "image_url" => [
                        "url" => "data:$imageType;base64,$imageData"
                    ]
                ]
            ]
        ]
    ],
    "max_tokens" => 800
];

$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => "https://openrouter.ai/api/v1/chat/completions",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_TIMEOUT => 120,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer " . $apiKey,
        "Content-Type: application/json",
        "HTTP-Referer: https://yourdomain.com",
        "X-Title: Skin Analyzer"
    ],
    CURLOPT_POSTFIELDS => json_encode($payload)
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {

    echo '
    <div class="result-card">
        <h3>cURL Error</h3>
        <p>' . curl_error($ch) . '</p>
    </div>';

    curl_close($ch);
    exit;
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

$result = json_decode($response, true);

if ($httpCode != 200) {

    echo '
    <div class="result-card">
        <h3>API Error</h3>
        <pre>' . htmlspecialchars($response) . '</pre>
    </div>';

    exit;
}

if (isset($result['choices'][0]['message']['content'])) {

    echo '
    <div class="result-card">
        <h3>Skin Analysis Result</h3>
        ' . $result['choices'][0]['message']['content'] . '
    </div>';

} else {

    echo '
    <div class="result-card">
        <h3>Unexpected Response</h3>
        <pre>' . htmlspecialchars($response) . '</pre>
    </div>';
}

if (file_exists($targetFile)) {
    unlink($targetFile);
}
?>
