<?php
$title = 'Problems';    //set title
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/dbfunctions.php';
set_cookie();   //Retrieve data from cookie
$nav = nav();   //Get the navigation menu
ob_start();
if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
    try{
        $id = htmlspecialchars($_GET['id']);
        $sql ="SELECT u.id,u.username,u.role,u.name,u.email
        FROM users u
        WHERE id=$id";
        $user = $pdo->query($sql)->fetch();
        if (!$user)
            include 'layouts/404.html.php';
        else {
            echo '<h2 class="mt-5 ms-5 mb-2">'.htmlspecialchars($user['name']).'</h2>';
            echo '<p class="mt-5 ms-5 mb-2"> <b>Email</b>: '.htmlspecialchars($user['email']).'</p>';
            $q_posted = count_data('questions','iduser',$id);
            $a_posted = count_data('answers','iduser',$id);
            echo '<h3 class="mt-5 ms-5 mb-2">Contribution</h3>';
            echo '<p class="mt-5 ms-5 mb-2">Questions posted: '.$q_posted.'</p>';
            echo '<p class="mt-1 ms-5 mb-2">Answers posted: '.$a_posted.'</p>';
            if (isset($_SESSION['userid']) && $_SESSION['userid']==$_GET['id']){
                echo '<a class="link-opacity-50-hover text-decoration-none mt-5 ms-5 mb-2" href="update_password.php">Update Password</a>';
            }
        }

    }catch (PDOException $e){
        $title = 'An error has occured';
        $output= 'Database error: ' . $e->getMessage();
    }
}else{
    include 'layouts/404.html.php'; //display not found page
}
$output = ob_get_clean();
include 'layouts/index.html.php';
