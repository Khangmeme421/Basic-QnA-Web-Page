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
                // Set cookies and session variables
                $_SESSION['userid'] = $userid;
                $_SESSION['role'] = get_role();
                $_SESSION['username'] = $user_name;
                setcookie('userid', $userid, time() + (30 * 24 * 60 * 60), '/', '', true);
                setcookie('role', get_role(), time() + (30 * 24 * 60 * 60), '/', '', true);
                setcookie('username', $user_name, time() + (30 * 24 * 60 * 60), '/', '', true);
                header("Location: index.php");
            } else {
                // Display an error message if the login details are incorrect
                echo '<div class="alert alert-warning d-flex justify-content-center align-items-center mt-5 mx-auto" role="alert" style="max-width: 18rem;" id="alert">
                        Invalid login details
                    </div>';
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
