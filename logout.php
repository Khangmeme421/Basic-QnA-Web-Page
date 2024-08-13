<?php 
    session_start();
    session_destroy();
    setcookie('userid', null, time() + (30 * 24 * 60 * 60), '/', '', true);
    setcookie('role', null, time() + (30 * 24 * 60 * 60), '/', '', true);
    setcookie('username', null, time() + (30 * 24 * 60 * 60), '/', '', true);
    header("Location: Login.php");
?>