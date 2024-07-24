<?php
$title = 'Problems';
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/dbfunctions.php';
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
            echo '<p>'.htmlspecialchars($attributes['name']).'</p>';
            echo '<p>'.htmlspecialchars($attributes['email']).'</p>';
        }
        
    }catch (PDOException $e){
        $title = 'An error has occured';
        $output= 'Database error: ' . $e->getMessage();
    }
}
$output = ob_get_clean();
include 'layouts/index.html.php';
