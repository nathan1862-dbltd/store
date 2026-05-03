<?php
// index.php – no session needed anymore
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DermaScan AI – Skin Analysis</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* ---------- GLOBAL STYLES ---------- */
        :root {
            --primary: #6C5CE7;
            --primary-light: #A29BFE;
            --accent: #00B894;
            --bg: #0F0F1A;
            --card-bg: rgba(255,255,255,0.05);
            --text: #EDEDED;
            --text-muted: #B0B0B0;
            --border: rgba(255,255,255,0.1);
            --shadow: 0 8px 32px rgba(0,0,0,0.3);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0F0F1A 0%, #1A1A2E 50%, #16213E 100%);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }
        .container {
            width: 100%;
            max-width: 800px;
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: var(--shadow);
        }
        h1 {
            font-size: 2.4rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-light), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }
        .subtitle {
            color: var(--text-muted);
            margin-bottom: 2rem;
            font-size: 1.05rem;
        }

        /* ---------- UPLOAD ZONE ---------- */
        .upload-area {
            border: 2px dashed var(--border);
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
            background: rgba(255,255,255,0.02);
            margin-bottom: 1.5rem;
        }
        .upload-area:hover, .upload-area.dragover {
            border-color: var(--primary-light);
            background: rgba(108, 92, 231, 0.1);
        }
        .upload-area img.preview {
            max-width: 200px;
            max-height: 200px;
            border-radius: 12px;
            margin: 0 auto 1rem;
            display: none;
        }
        .upload-area .icon-upload {
            font-size: 3rem;
            margin-bottom: 0.5rem;
            opacity: 0.7;
        }
        .upload-area p {
            color: var(--text-muted);
        }
        .file-input {
            display: none;
        }

        /* ---------- BUTTONS ---------- */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            width: 100%;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            box-shadow: 0 4px 15px rgba(108, 92, 231, 0.4);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(108, 92, 231, 0.6);
        }
        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* ---------- LOADING SHIMMER ---------- */
        .loader-container {
            display: none;
            margin: 1.5rem 0;
        }
        .loader-shimmer {
            height: 6px;
            background: linear-gradient(90deg, var(--border), var(--primary-light), var(--border));
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            border-radius: 3px;
        }
        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        .loader-text {
            text-align: center;
            margin-top: 0.75rem;
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        /* ---------- RESULT CARDS ---------- */
        .results {
            display: none;
            animation: fadeSlideUp 0.5s ease-out;
        }
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .result-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        .result-header h2 {
            font-size: 1.8rem;
            font-weight: 600;
            background: linear-gradient(135deg, var(--accent), var(--primary-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .badge {
            background: rgba(0,184,148,0.15);
            color: var(--accent);
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        .result-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.2s;
            backdrop-filter: blur(10px);
        }
        .result-card:hover {
            border-color: var(--primary-light);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(108, 92, 231, 0.2);
        }
        .result-card h3 {
            color: var(--primary-light);
            margin-bottom: 0.75rem;
            font-size: 1.2rem;
            font-weight: 600;
        }
        .result-card p {
            color: var(--text);
            line-height: 1.6;
            white-space: pre-line; /* preserve newlines from AI */
        }

        /* Error message */
        .error-message {
            background: rgba(255,82,82,0.1);
            border: 1px solid rgba(255,82,82,0.3);
            color: #FF5252;
            padding: 1rem;
            border-radius: 12px;
            margin-top: 1rem;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>✨ DermaScan AI</h1>
        <p class="subtitle">Upload a close‑up of your skin and get a professional, AI‑powered analysis in seconds.</p>

        <!-- Upload Area -->
        <div class="upload-area" id="uploadArea">
            <div class="icon-upload">📸</div>
            <p>Drag & drop your image here or <strong style="color:var(--primary-light)">click to browse</strong></p>
            <img id="previewImg" class="preview" alt="Skin preview" />
            <input type="file" id="fileInput" class="file-input" accept="image/jpeg,image/png,image/webp" />
        </div>

        <button id="analyzeBtn" class="btn btn-primary" disabled>
            <span>🔬</span> Analyze Skin
        </button>

        <!-- Loader -->
        <div class="loader-container" id="loader">
            <div class="loader-shimmer"></div>
            <div class="loader-text">Our AI is analyzing your skin…</div>
        </div>

        <!-- Error -->
        <div class="error-message" id="errorMsg"></div>

        <!-- Results (hidden until data) -->
        <div class="results" id="results"></div>
    </div>

    <script>
        // ---------- DOM ELEMENTS ----------
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('fileInput');
        const previewImg = document.getElementById('previewImg');
        const analyzeBtn = document.getElementById('analyzeBtn');
        const loader = document.getElementById('loader');
        const errorMsg = document.getElementById('errorMsg');
        const resultsDiv = document.getElementById('results');

        let selectedFile = null;

        // ---------- CLICK TO BROWSE ----------
        uploadArea.addEventListener('click', () => fileInput.click());

        // ---------- DRAG & DROP ----------
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });
        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            handleFiles(e.dataTransfer.files);
        });

        // ---------- FILE INPUT CHANGE ----------
        fileInput.addEventListener('change', () => {
            handleFiles(fileInput.files);
        });

        function handleFiles(files) {
            if (files.length === 0) return;
            const file = files[0];

            // Validate type
            if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
                showError('Please upload a JPEG, PNG, or WebP image.');
                return;
            }
            // Validate size (10 MB)
            if (file.size > 10 * 1024 * 1024) {
                showError('Image size must be under 10 MB.');
                return;
            }

            selectedFile = file;
            // Show preview
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
                uploadArea.querySelector('.icon-upload').style.display = 'none';
                uploadArea.querySelector('p').innerHTML = 'Click to change image';
            };
            reader.readAsDataURL(file);

            // Enable button
            analyzeBtn.disabled = false;
            // Hide previous results/errors
            resultsDiv.style.display = 'none';
            errorMsg.style.display = 'none';
        }

        // ---------- ANALYZE BUTTON CLICK ----------
        analyzeBtn.addEventListener('click', async () => {
            if (!selectedFile) return;

            // Show loader, disable button, hide previous
            loader.style.display = 'block';
            analyzeBtn.disabled = true;
            resultsDiv.style.display = 'none';
            errorMsg.style.display = 'none';

            try {
                // Convert file to base64 data URI
                const dataUri = await fileToDataUri(selectedFile);

                // Call our backend
                const response = await fetch('/aiskin/analyze_ajax.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ image: dataUri })
                });

                const data = await response.json();

                if (!response.ok || data.error) {
                    throw new Error(data.error || 'Analysis failed');
                }

                // Display the parsed results
                displayResults(data.analysis);
            } catch (err) {
                showError(err.message);
            } finally {
                loader.style.display = 'none';
                analyzeBtn.disabled = false;
            }
        });

        // Helper: File -> data URI
        function fileToDataUri(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = () => resolve(reader.result);
                reader.onerror = reject;
                reader.readAsDataURL(file);
            });
        }

        // ---------- DISPLAY RESULTS (parse AI text into cards) ----------
        function displayResults(analysisText) {
            // Parse sections (like "1. **Skin Type**:" ...)
            // We'll split by a numbered pattern: /^\d+\.\s*\*\*(.*?)\*\*:/m
            const sections = [];
            const regex = /^(\d+)\.\s*\*\*(.*?)\*\*:\s*/gm;
            let match;
            let lastIndex = 0;

            while ((match = regex.exec(analysisText)) !== null) {
                const title = match[2].trim();
                const startOfContent = match.index + match[0].length;
                // Find next section or end of string
                const nextMatch = regex.exec(analysisText);
                const endOfContent = nextMatch ? nextMatch.index : analysisText.length;
                // Reset regex lastIndex because we used exec inside the loop
                regex.lastIndex = startOfContent;

                let content = analysisText.substring(startOfContent, endOfContent).trim();
                sections.push({ title, content });
            }

            // Fallback if parsing fails: treat entire text as one card
            if (sections.length === 0) {
                sections.push({ title: 'Analysis', content: analysisText });
            }

            // Build HTML
            let html = `
                <div class="result-header">
                    <h2>🔍 Your Skin Analysis</h2>
                    <span class="badge">AI‑generated</span>
                </div>
            `;

            sections.forEach(section => {
                // Simple icon mapping (optional)
                const icons = {
                    'Skin Type': '🧴',
                    'Visible Conditions': '🔬',
                    'Severity Assessment': '📊',
                    'Possible Causes': '🧩',
                    'Skincare Recommendations': '🌿',
                    'When to See a Doctor': '🩺'
                };
                const icon = icons[section.title] || '📋';
                html += `
                    <div class="result-card">
                        <h3>${icon} ${section.title}</h3>
                        <p>${escapeHtml(section.content)}</p>
                    </div>
                `;
            });

            resultsDiv.innerHTML = html;
            resultsDiv.style.display = 'block';
            resultsDiv.scrollIntoView({ behavior: 'smooth' });
        }

        // ---------- ERROR DISPLAY ----------
        function showError(message) {
            errorMsg.textContent = '⚠️ ' + message;
            errorMsg.style.display = 'block';
            errorMsg.scrollIntoView({ behavior: 'smooth' });
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>