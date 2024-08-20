<?php 
    session_start();
    session_destroy();
    setcookie('userid', null, time() - 3600, '/', '', true, true);
    setcookie('role', null, time() - 3600, '/', '', true, true);
    setcookie('username', null, time() - 3600, '/', '', true, true);

    setcookie('E6PgCCAHVeHJB4u', null, time() - 3600, '/', '', true, true);
    setcookie('ClfSjOKzZTKgony', null, time() - 3600, '/', '', true, true);
    setcookie('Ei1yiRbEpidLEc5', null, time() - 3600, '/', '', true, true);
    header("Location: Login.php");
?>