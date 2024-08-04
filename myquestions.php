<?php
$title = 'My Questions';
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/dbfunctions.php';
$nav = nav();
ob_start();
//redirect user to home page if not loged in
if(!isset($_SESSION['userid'])){
    header('Location: index.php');
}else{
    //delete post based on id sent via POST method
    if (isset($_POST['id'])) {
        $id = htmlspecialchars($_POST['id']);
        delete('questions',$id);
    }
    //handling upvote
    if (isset($_POST['upvote']) && isset($_SESSION['userid'])) {
        $idQuestion = $_POST['upvote'];
        $idUser = $_SESSION['userid'];
        upvote($idQuestion,$idUser);
    }
    //call display function
    displayQuestions($pdo, array('userid' => $_SESSION['userid']));
}
$output = ob_get_clean();
include 'layouts/index.html.php';