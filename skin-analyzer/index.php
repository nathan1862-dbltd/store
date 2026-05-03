<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AI Skin Analyzer</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
  <div class="card">
    <h1>AI Skin Analyzer</h1>

    <p class="subtitle">
      Upload a clear face photo for AI-powered skin analysis
    </p>

    <form id="skinForm" enctype="multipart/form-data">
      <div class="upload-box">
        <input type="file" name="image" id="image" accept="image/*" required>
      </div>

      <button type="submit" class="analyze-btn">
        Analyze Skin
      </button>
    </form>

    <div id="loading" class="loading hidden">
      Analyzing skin...
    </div>

    <div id="result"></div>

  </div>
</div>

<script src="script.js"></script>
</body>
</html>
