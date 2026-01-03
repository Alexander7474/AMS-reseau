<?php
session_start();
session_destroy();
// Redirect back to previous page
header("Location: " . "/src/control/login.php");
exit;
