<?php
$title = 'Add Account';
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/dbfunctions.php';
$nav = nav();
ob_start();

// Add Account worked but need some optimization
if($_SESSION['role']=='admin'){
    include 'layouts/newacc.html.php';
    try {
        if (isset($_GET['success'])){
            echo '<div class="alert alert-success d-flex justify-content-center align-items-center mt-5 mx-auto" role="alert" style="max-width: 18rem;" id="alert">
                        New account added
                    </div>';
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Retrieve the form data
            $username = $_POST['username'];
            $email = $_POST['email'];
            $fullName = $_POST['name'];
            $password = md5($_POST['password']);
            $role = $_POST['role'];

            $stmt = $pdo->prepare('SELECT * FROM `users` WHERE `username` = :username OR `email`= :email');
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $result = $stmt->fetchAll();
            if(empty($result)){
                // Insert the data into the database
                $stmt = $pdo->prepare('INSERT INTO users (username, email, name, password, role) VALUES (:username, :email, :fullName, :password, :role)');
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':fullName', $fullName);
                $stmt->bindParam(':password', $password);
                $stmt->bindParam(':role', $role);
                $stmt->execute();
                // Redirect to a success page or display a success message
                header('Location: ' . $_SERVER['PHP_SELF']. '?success=true');
                exit;
            }else{
                echo '<div class="alert alert-warning d-flex justify-content-center align-items-center mt-5 mx-auto" role="alert" style="max-width: 18rem;" id="alert">
                        Username or Email existed
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
    //redirect user to home page if not loged in as admin
    header('Location: index.php');
}

$output = ob_get_clean();
include 'layouts/index.html.php';
