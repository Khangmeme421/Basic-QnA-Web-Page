<?php
$title = 'Manage Posts';
session_start();
include 'includes/dbfunctions.php';
$nav = nav();
include 'includes/DatabaseConnection.php';
//if user is not admin redirect to home page
if ($_SESSION['role']!='admin'){
    header("Location: index.php");
}
ob_start();
if (isset($_POST['id']) && isset($_POST['iduser'])) {
    $id = $_POST['id'];
    delete('questions',$id);
    $userid = $_POST['iduser'];
    try{
        //send a notification to user get their post deleted by admin
        if ($userid != $_SESSION['userid']){
            $content = 'Admin deleted your post';
            admin_delete($userid,$content);
        }
    }
    catch (PDOException $e){
        $title = 'An error has occured';
        $output= 'Database error: ' . $e->getMessage();
    }
}
//handling upvote
if (isset($_POST['upvote']) && isset($_SESSION['userid'])) {
    $idQuestion = $_POST['upvote'];
    $idUser = $_SESSION['userid'];
    upvote($idQuestion,$idUser);
}
//call display funtion
$title = 'Posts Management';
if (isset($_GET['subid'])) {
    displayQuestions($pdo, array('title' => $title,'subid' => $_GET['subid']));
} else {
    displayQuestions($pdo, array('title' => $title));
} 
$output = ob_get_clean();
include 'layouts/index.html.php';
