<?php
$title = 'Problems';
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/dbfunctions.php';
set_cookie()
$nav = nav();
ob_start();
if (isset($_GET['id'])) {
    try{
        $id = htmlspecialchars($_GET['id']);
        $sql ="SELECT u.id,u.username,u.role,u.name,u.email
        FROM users u
        WHERE id=$id";
        $user = $pdo->query($sql);
        foreach ($user as $attributes){
            echo '<h2 class="mt-5 ms-5 mb-2">'.htmlspecialchars($attributes['name']).'</h2>';
            echo '<p class="mt-5 ms-5 mb-2"> <b>Email</b>: '.htmlspecialchars($attributes['email']).'</p>';
        }
        $q_posted = count_data('questions','iduser',$id);
        $a_posted = count_data('answers','iduser',$id);
        echo '<h3 class="mt-5 ms-5 mb-2">Contribution</h3>';
        echo '<p class="mt-5 ms-5 mb-2">Questions posted: '.$q_posted.'</p>';
        echo '<p class="mt-1 ms-5 mb-2">Answers posted: '.$a_posted.'</p>';
        if (isset($_SESSION['userid']) && $_SESSION['userid']==$_GET['id']){
            echo '<a class="link-opacity-50-hover text-decoration-none mt-5 ms-5 mb-2" href="update_password.php">Update Password</a>';
        }

    }catch (PDOException $e){
        $title = 'An error has occured';
        $output= 'Database error: ' . $e->getMessage();
    }
}else{
    header("Location: index.php");
}
$output = ob_get_clean();
include 'layouts/index.html.php';
