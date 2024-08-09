<?php
$title = 'Manage Modules';
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/dbfunctions.php';
$nav = nav();
ob_start();
//redirect user to home page if not loged in as admin
if ($_SESSION['role']!='admin')
    header("Location: index.php");
$butt = 'Update';
//if a post selected create $val stores all subjects info
if (isset($_GET['id'])){
    try{
        $sql = 'SELECT * FROM `subject` WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();
        $subjects = $stmt->fetchAll();

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
        echo '<div class="alert alert-success d-flex justify-content-center align-items-center mt-5 mx-auto" role="alert" style="max-width: 18rem;" id="alert">
                        New subject created successfully
                    </div>';
    }else{
        echo '<div class="alert alert-warning d-flex justify-content-center align-items-center mt-5 mx-auto" role="alert" style="max-width: 18rem;" id="alert">
                        Subject already exist
                    </div>';
    }
    
}
$output = ob_get_clean();
include 'layouts/index.html.php';