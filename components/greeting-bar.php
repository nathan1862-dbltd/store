<?php
// Set Myanmar timezone
date_default_timezone_set('Asia/Yangon');

$username = $_SESSION['user']['username'] ?? '';

$hour = date('H');

if ($hour < 12) {
    $greeting = "Good Morning";
} elseif ($hour < 17) {
    $greeting = "Good Afternoon";
} else {
    $greeting = "Good Evening";
}

$day = date('l'); // Monday, Tuesday...

echo "
<div class='greeting-bar'>
    <span class='greeting-text'>
        {$greeting}, " . htmlspecialchars($username) . " — {$day}
    </span>
</div>
";
?>