<?php
header('Content-Type: text/html');

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    die('<p style="color:red">No image uploaded or upload error.</p>');
}

// Get uploaded image
$imageFile = $_FILES['image']['tmp_name'];
$imageData = base64_encode(file_get_contents($imageFile));
$mimeType = mime_content_type($imageFile); // e.g., image/jpeg, image/png

// Your OpenRouter API key – replace with your actual key
$apiKey = "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"; // REPLACE THIS

// Prepare the correct multimodal payload for OpenRouter
$payload = [
    "model" => "google/gemma-3-27b-it:free",
    "messages" => [
        [
            "role" => "user",
            "content" => [
                [
                    "type" => "text",
                    "text" => "Analyze this skin image professionally. Describe skin condition, concerns, and general recommendations. Be concise but informative."
                ],
                [
                    "type" => "image_url",
                    "image_url" => [
                        "url" => "data:$mimeType;base64,$imageData"
                    ]
                ]
            ]
        ]
    ]
];

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => "https://openrouter.ai/api/v1/chat/completions",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $apiKey",
        "Content-Type: application/json"
    ],
    CURLOPT_POSTFIELDS => json_encode($payload)
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false) {
    echo '<p style="color:red">cURL error: ' . curl_error($ch) . '</p>';
    exit;
}

$decoded = json_decode($response, true);

if ($httpCode === 200 && isset($decoded['choices'][0]['message']['content'])) {
    $analysis = nl2br(htmlspecialchars($decoded['choices'][0]['message']['content']));
    echo "<div><strong>Analysis Result:</strong><br>$analysis</div>";
} else {
    $errorMsg = $decoded['error']['message'] ?? 'Unknown API error';
    echo "<p style='color:red'>API Error (HTTP $httpCode): $errorMsg</p>";
}
?>
