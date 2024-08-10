<?php
$title = 'Manage Modules';
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/dbfunctions.php';
$nav = nav();
ob_start();
if ($_SESSION['role']!='admin')
    header("Location: index.php");
include 'layouts/newmodules.html.php';
if (isset($_POST['id'])) {
    $id = htmlspecialchars($_POST['id']);
    if ($id!=1){
    delete('subject',$id);
    }
}
try{
    $edit = null;
    if(isset($_GET['id']))
        $edit = $_GET['id'];
    if (isset($_POST['module'])){
        $name = $_POST['module'];
        $action = $edit ==null ? "insert" : "update";   //insert or update is depends on edit value
        manageSubject($pdo,$name,$edit,$action);
    }
    $sql = 'SELECT * FROM `subject`';
    $subjects = $pdo->query($sql);
    foreach ($subjects as $subject){
        if ($edit == $subject['id']){
            $val = 'value="'.$subject['sub_name'].'"';
            $link = '"managemodules.php"';
            include 'layouts/mngmodules.html.php';
        }else {
            echo '<div class="card col-12 col-sm-6 mt-5 ms-5 mb-2">';
            echo '    <div class="card-body">';
            echo '         <div class="float-start">';
            echo '          <p>'.$subject['sub_name'].'</p>';
            echo '         </div>';
            echo '      <div class="float-end">';
            echo '          <a class="link-opacity-50-hover text-decoration-none" href="managemodules.php?id='.htmlspecialchars($subject['id']).'">'.'Edit'.'</a>';
            echo '          <form action="" method="post" class="d-inline ms-3">
                                <input type="hidden" name="id" value="'.htmlspecialchars($subject['id']).'">
                                <a class="link-opacity-50-hover text-decoration-none" href="#" onclick="event.preventDefault(); this.parentNode.submit()">Delete</a>
                            </form>';
            echo '      </div>';
            echo '   </div>';
            echo '</div>';
        }
    }
}catch(PDOException $e){
    $title = 'An error has occured';
    $output= 'Database error: ' . $e->getMessage();
}

$output = ob_get_clean();
include 'layouts/index.html.php';
