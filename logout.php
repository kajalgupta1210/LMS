<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Redirect to login page (adjusted path)
header('Location: ../frontend/html/login.html');
exit();
?>
