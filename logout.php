<?php
if (isset($_POST['logout'])) {
    session_start();
    session_unset();
    session_destroy(); // Destroy the session
    header("Location: index.php"); // Redirect to the homepage
    exit();
}
?>
