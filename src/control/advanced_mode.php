<?php
session_start();

if (!isset($_SESSION['advanced_mode'])) {
    $_SESSION['advanced_mode'] = false;
}

// Toggle the value
$_SESSION['advanced_mode'] = !$_SESSION['advanced_mode'];

// Redirect back to previous page
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
