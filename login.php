<?php
$title = 'Login';
session_start();
ob_start();
if (!isset($_SESSION['userid'])){
    include 'layouts/loginbtn.html.php';
}
$nav = ob_get_clean();  //display login button
ob_start();
if(empty($_SESSION['userid'])){
    include 'layouts/login.html.php';   //display layout
    include 'includes/DatabaseConnection.php';
    include 'includes/dbfunctions.php';
    if (isset($_POST['username']) and isset($_POST['password'])) {
        try {
            $username_or_email = $_POST['username'];
            $password = md5($_POST['password']);
            // Check if the input is an email address
            if (filter_var($username_or_email, FILTER_VALIDATE_EMAIL)) {
                $sql = "SELECT * FROM users WHERE email = '". $username_or_email. "' AND password = '". $password. "'";
            } else {
                $sql = "SELECT * FROM users WHERE username = '". $username_or_email. "' AND password = '". $password. "'";
            }
            $info = $pdo->query($sql);
            $login_success = false;
            $userid = NULL;
            $user_name = null;
            foreach ($info as $inf) {
                $login_success = true;
                $userid = $inf['id'];
                $user_name = $inf['name'];
            }
            if ($login_success) {
                setcookie('userid', $userid, time() + (30 * 24 * 60 * 60));
                session_regenerate_id();
                $_SESSION['userid'] = $userid;
                $_SESSION['role'] = get_role();
                $_SESSION['username'] = $user_name;
                header("Location: index.php");
            } else {
                echo '<div class="alert alert-warning d-flex justify-content-center align-items-center mt-5 mx-auto" role="alert" style="max-width: 18rem;" id="alert">
                        Invalid login details
                    </div>
                    <script>
                        setTimeout(function() {
                            document.getElementById("alert").remove();
                        }, 4000);
                    </script>';
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
