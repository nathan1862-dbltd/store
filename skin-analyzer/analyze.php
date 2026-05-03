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

$data = [
    "model" => "openrouter/free",
    "messages" => [
        [
            "role" => "user",
            "content" => [
                [
                    "type" => "text",
                    "text" => "Analyze facial skin condition from this image. Return clean HTML only. Include Skin Type, Acne Severity, Pores, Pigmentation, Dark Circles, Hydration, Texture, Fine Lines, Overall Skin Health Score (/10), skincare recommendations."
                ],
                [
                    "type" => "image_url",
                    "image_url" => [
                        "url" => "data:image/jpeg;base64," . $imageData
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
        "Authorization: Bearer " . $apiKey,
        "Content-Type: application/json",
        "HTTP-Referer: https://thebizportwebs.online",
        "X-Title: Skin Analyzer"
    ],
    CURLOPT_POSTFIELDS => json_encode($data)
]);

$response = curl_exec($ch);

if(curl_errno($ch)){
    die(curl_error($ch));
}

curl_close($ch);

$result = json_decode($response, true);

$output = $result['choices'][0]['message']['content'] ?? 'Analysis failed';

echo '
<div class="result-card">
<h3>Skin Analysis Result</h3>
'.$output.'
</div>
';

if(file_exists($targetFile)){
    unlink($targetFile);
}
?>
