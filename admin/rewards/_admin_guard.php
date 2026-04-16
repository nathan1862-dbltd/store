<?php
require_once __DIR__ . '/../../init.php';
require_once __DIR__ . '/../../_helpers.php';

ensureSessionStarted();
if (!currentUserId()) redirect('../../login.php');
if (!isAdmin()) { http_response_code(403); echo 'Forbidden'; exit; }
