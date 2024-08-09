<?php
$title = 'Edit answer';
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/dbfunctions.php';
$nav = nav();
ob_start();
$butt = 'Update';
//if a post selected create $val stores all answer info
if (isset($_GET['id'])){
    $sql = 'SELECT * FROM `answers` WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();
        $answer = $stmt->fetchAll();
        foreach ($answer as $ans){
            $val = $ans['content'];
        }
        $val = 'value="'.$val.'"';
}
include 'layouts/mngmodules.html.php';
if (isset($_POST['module'])){
    $stmt = $pdo->prepare("UPDATE `answers` SET `content` = :content WHERE `id` = :id");
    $stmt->bindParam(':content', $_POST['module']);
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    echo '<div class="alert alert-success d-flex justify-content-center align-items-center mt-5 mx-auto" role="alert" style="max-width: 18rem;" id="alert">
                    New subject created successfully
                </div>';
}
$output = ob_get_clean();
include 'layouts/index.html.php';
