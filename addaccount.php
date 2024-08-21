<?php
$title = 'Add Account'; // Set the title of the page
session_start();    // Start the session
include 'includes/DatabaseConnection.php';
include 'includes/dbfunctions.php'; // Include the database functions file
set_cookie();    //retrieve data from cookie
$nav = nav();   // Get the navigation menu
ob_start();     // Start output buffering
// Add Account worked but need some optimization
if($_SESSION['role']=='admin'){
    include 'layouts/addaccount.html.php';  //display layout
    try {
        if (isset($_GET['success'])){   //display success message via GET method
            create_Alert('success', 'New account added');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Retrieve the form data
            $username = $_POST['username'];
            $email = $_POST['email'];
            $fullName = $_POST['name'];
            $password = md5($_POST['password']);
            $role = $_POST['role'];
            // Check if the input is an email address or a username
            $stmt = $pdo->prepare('SELECT * FROM `users` WHERE `username` = :username OR `email`= :email');
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $result = $stmt->fetchAll();
            if(empty($result)){
                // Insert the data into the database if account not exist
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
                // Display an error message if the account already exists
                create_Alert('warning', 'Username or Email existed');
                }
        }
    } catch (PDOException $e) {
        $output = 'Database error: '. $e->getMessage();
    }
}else{
    header('Location: index.php');  //redirect user to home page if not loged in as admin
}

$output = ob_get_clean();   //stop output buffering
include 'layouts/index.html.php';   //display layout
