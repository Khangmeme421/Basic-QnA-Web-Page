<?php
$title = 'Manage Modules';
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/dbfunctions.php';
$nav = nav();
ob_start();
if ($_SESSION['role']!='admin'){
    include 'layouts/redirect.html.php';
}
$butt = 'Update';
if (isset($_GET['id'])){
    try{
        
        $sql = 'SELECT * FROM `subject` WHERE id='.$_GET['id'];
        $subjects = $pdo->query($sql);
        foreach ($subjects as $subject){
            $val = $subject['sub_name'];
        }
        $val = 'value="'.$val.'"';
    }catch(PDOException $e){
        $title = 'An error has occured';
        $output= 'Database error: ' . $e->getMessage();
    }
}
include 'layouts/mngmodules.html.php';
if (isset($_POST['module'])){
    $name = $_POST['module'];
    $stmt = $pdo->prepare('SELECT * FROM `subject` WHERE `sub_name` = :name');
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    $result = $stmt->fetchAll();
    if(empty($result)){
        $stmt = $pdo->prepare("UPDATE `subject` SET `sub_name` = :name WHERE `id` = :id");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();
        echo "New record created successfully";
    }else{
        echo "Subject already exist";
    }
}
$output = ob_get_clean();
include 'layouts/index.html.php';
