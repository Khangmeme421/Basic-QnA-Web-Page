<?php 
    session_start();
    session_destroy();
    setcookie('pusbyciU9MbXjJEfpJwjv9BhL', null, time() - 3600, '/', '', true, true);
    setcookie('RSWHBmf47GvoLh7omLXsMKquv', null, time() - 3600, '/', '', true, true);
    setcookie('jomBGmEz4by39DgRSrFq9P19q', null, time() - 3600, '/', '', true, true);

    setcookie('E6PgCCAHVeHJB4u', null, time() - 3600, '/', '', true, true);
    setcookie('ClfSjOKzZTKgony', null, time() - 3600, '/', '', true, true);
    setcookie('Ei1yiRbEpidLEc5', null, time() - 3600, '/', '', true, true);
    header("Location: Login.php");
?>