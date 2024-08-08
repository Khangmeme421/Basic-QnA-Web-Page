<?php
$title = 'Update Password';
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/dbfunctions.php';
$nav = nav();
ob_start();

// Add Account worked but need some optimization
if(isset($_SESSION['userid'])){ 
    include 'layouts/update_password.html.php';
    try {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Update password
            $current_password = md5($_POST['current_password']);
            $new_password = md5($_POST['n_password']);
            $c_password = md5($_POST['c_password']);

            // Get the user's current password from the database
            $stmt = $pdo->prepare("SELECT password FROM users WHERE id = :id");
            $stmt->bindParam(':id', $_SESSION['userid']);
            $stmt->execute();
            $row = $stmt->fetch();
            $hashed_password = $row['password'];

            // Check if the current password is correct
            if ($current_password != $hashed_password) {
                // Current password is incorrect
                echo '<div class="alert alert-warning d-flex justify-content-center align-items-center mt-5 mx-auto" role="alert" style="max-width: 18rem;" id="alert">
                        Current password is incorrect
                    </div>';
            } elseif ($new_password !== $c_password) {
                // New password and confirm password don't match
                echo '<div class="alert alert-warning d-flex justify-content-center align-items-center mt-5 mx-auto" role="alert" style="max-width: 18rem;" id="alert">
                        New password and confirm password mismatch
                    </div>';
            } else {
                // Update the password
                $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
                $stmt->bindParam(':password', $new_password);
                $stmt->bindParam(':id', $_SESSION['userid']);
                $stmt->execute();

                // Password updated successfully
                echo '<div class="alert alert-success d-flex justify-content-center align-items-center mt-5 mx-auto" role="alert" style="max-width: 18rem;" id="alert">
                Password updated successfully
                    </div>';
            }
            echo '<script>
                    setTimeout(function() {
                        document.getElementById("alert").remove();
                    }, 4000);
                </script>';
        }
    } catch (PDOException $e) {
        $output = 'Database error: '. $e->getMessage();
    }
}else{
    //redirect user to home page if not loged in
    header('Location: index.php');
}

$output = ob_get_clean();
include 'layouts/index.html.php';
