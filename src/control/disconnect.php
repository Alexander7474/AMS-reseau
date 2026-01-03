<?php
session_start();

if (isset($_SESSION['authenticated'])) {
  unset($_SESSION['authenticated']);
}

// Redirect back to previous page
header("Location: " . "/src/control/login.php");
exit;
