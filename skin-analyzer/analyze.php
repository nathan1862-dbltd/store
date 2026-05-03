<?php

header('Content-Type: text/html');

if (!isset($_FILES['image'])) {
    die('<div class="result-card">No image uploaded.</div>');
}

$uploadDir = "uploads/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$fileName = time() . "_" . basename($_FILES["image"]["name"]);
$targetFile = $uploadDir . $fileName;

if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
    die('<div class="result-card">Image upload failed.</div>');
}

$imageType = mime_content_type($targetFile);
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
        "HTTP-Referer: https://thebizportwebs.online",
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
