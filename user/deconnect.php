<?php
    session_start();
    unset($_SESSION['user_id']);
    setcookie('user_id', '', time() - 3600, '/');
    session_destroy();
    header('location: signin.php')
?>