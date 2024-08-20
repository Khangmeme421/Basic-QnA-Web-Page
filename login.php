<?php
$title = 'Login';
session_start();
ob_start();
if (!isset($_SESSION['userid'])){
    include 'layouts/loginbtn.html.php';
}
$nav = ob_get_clean();  //display login button
ob_start();
if(!(isset($_COOKIE['userid']))){
    include 'layouts/login.html.php';   //display layout
    include 'includes/DatabaseConnection.php';
    include 'includes/dbfunctions.php';
    if (isset($_POST['username']) and isset($_POST['password'])) {
        try {
            $username_or_email = $_POST['username'];
            $password = md5($_POST['password']);
            // Check if the input is an email address or a username
            if (filter_var($username_or_email, FILTER_VALIDATE_EMAIL)) {
                $sql = "SELECT * FROM users WHERE email = :username_or_email AND password = :password";
            } else {
                $sql = "SELECT * FROM users WHERE username = :username_or_email AND password = :password";
            }
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':username_or_email', $username_or_email);
            $stmt->bindParam(':password', $password);
            $stmt->execute();
            $info = $stmt->fetch();

            $login_success = false;
            $userid = NULL;
            $user_name = null;
            if ($info) {
                $login_success = true;
                $userid = $info['id'];
                $user_name = $info['name'];
            }
            if ($login_success) {
                $encryption_key = 'b1JbS2v7IhX2uVj3K6PgUvO4PiRxV2VzQ5Uw1OjL3uQ'; //Encryption key
    
                // Encrypt the data
                $encrypted_userid = encrypt_data($userid, $encryption_key);
                $encrypted_role = encrypt_data(get_role(), $encryption_key);
                $encrypted_username = encrypt_data($user_name, $encryption_key);

                // Set decoy cookies
                setcookie('userid', 'qQd3vPm7LS', time() + (30 * 24 * 60 * 60), '/', '', true, true);
                setcookie('role', 'EAxgOynY2y', time() + (30 * 24 * 60 * 60), '/', '', true, true);
                setcookie('username', 'm0j8SK4JRU', time() + (30 * 24 * 60 * 60), '/', '', true, true);

                // Set real encrypted cookies
                setcookie('E6PgCCAHVeHJB4u', $encrypted_userid, time() + (30 * 24 * 60 * 60), '/', '', true, true);
                setcookie('ClfSjOKzZTKgony', $encrypted_role, time() + (30 * 24 * 60 * 60), '/', '', true, true);
                setcookie('Ei1yiRbEpidLEc5', $encrypted_username, time() + (30 * 24 * 60 * 60), '/', '', true, true);
                header("Location: index.php");
            } else {
                // Display an error message if the login details are incorrect
                create_Alert('warning', 'Invalid login details');
            }
        } catch (PDOException $e) {
            $output = 'Database error: '. $e->getMessage();
        }
    }
}else{
    header("Location: index.php");
}

$output = ob_get_clean();
include 'layouts/index.html.php';
