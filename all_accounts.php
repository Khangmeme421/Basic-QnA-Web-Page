<?php
$title = 'All Accounts';
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/dbfunctions.php';
$nav = nav();
ob_start();
if (isset($_POST['id'])) {
    $id = htmlspecialchars($_POST['id']);
    delete('users',$id);
}
try{
    $sql = 'SELECT * FROM `users` WHERE id !='.$_SESSION['userid'];
    $users = $pdo->query($sql);
    foreach ($users as $user){
        echo '<div class="card col-12 col-sm-6 mt-5 ms-5 mb-2">';
        echo '    <div class="card-body">';
        echo '         <div class="float-start">';
        echo '          <p>'.$user['name'].'</p>';
        echo '          <p>'.$user['email'].'</p>';
        echo '         </div>';
        echo '      <div class="float-end">';
        echo '<form action="" method="post" class="d-inline ms-3">
                        <input type="hidden" name="id" value="'.htmlspecialchars($user['id']).'">
                        <a class="link-opacity-50-hover text-decoration-none" href="#" onclick="event.preventDefault(); this.parentNode.submit()">Delete</a>
                        </form>';
        echo '      </div>';
        echo '   </div>';
        echo '</div>';
    }
}catch(PDOException $e){
    $title = 'An error has occured';
    $output= 'Database error: ' . $e->getMessage();
}
$output = ob_get_clean();
include 'layouts/index.html.php';
