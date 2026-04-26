<?php
// config.php
//
// Database configuration for the e‑commerce site.  Update the
// constants below to match your hosting environment.  When
// deploying to cPanel you may need to adjust the hostname or use
// localhost if the database resides on the same server.  The
// database name, user and password are provided in the project
// context.

define('DB_HOST', 'localhost');
// Updated database credentials per user instructions
define('DB_USER', 'riusfnxmti_admin');
define('DB_PASS', 'riusfnxmti_admin');
define('DB_NAME', 'riusfnxmti_ecommerce');


// Establish a connection when this file is included.  If the
// connection fails the script will exit immediately with an error
// message.  This uses mysqli for its simplicity and because it is
// widely supported on shared hosting providers like cPanel.
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
    die('Failed to connect to MySQL: ' . $mysqli->connect_error);
}

// Set the default timezone for all date/time operations.  This
// ensures that functions like date() and strtotime() use the Asia/Tokyo
// timezone instead of the server’s default.  This is especially
// important for birthday and expiry date calculations.
date_default_timezone_set('Asia/Tokyo');

?>