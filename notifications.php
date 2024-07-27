<?php
$title = 'Notifications';
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/dbfunctions.php';
$nav = nav();
ob_start();
if (!isset($_SESSION['userid'])) {
    header('Location: index.php');
}else{
    // Not optimized delete fucntion
    if (isset($_POST['del'])) {
        $id = htmlspecialchars($_POST['del']);
        delete('notifications',$id);
        header('Location: notifications.php');
    }
    try{
        $sql = "SELECT n.id, n.idquestion, n.idsender, n.content, n.date_create, u.username 
        FROM notifications n 
        LEFT JOIN users u ON n.idsender = u.id 
        WHERE n.iduser = ".$_SESSION['userid'];
        $questions = $pdo->query($sql);
        $questions = $pdo->query($sql);
        echo '<div class="questions-container">';
        foreach($questions as $row){
            $sender = null;
            $question = null;
            if ($row['idsender']!= NULL && $row['idquestion']){
                $sender = '<a class="text-decoration-none" href="profile.php?id='.$row['idsender'].'">'.htmlspecialchars($row['username']).'</a>';
                $question = '<a class="text-decoration-none" href="index.php?id='.$row['idquestion'].'">'.'post'.'</a>';
            }
            echo '<div class="card col-sm-6 mt-5 ms-5 mb-2">';
            echo '    <div class="card-body">';
            echo '        <blockquote class="blockquote mb-0">';
            echo '<p>'.$sender.' '.htmlspecialchars($row['content']).$question.'</p>';
            echo '            <footer class="blockquote-footer">'.htmlspecialchars($row['date_create']);
            echo '<div class="float-end">';
            echo '<form action="" method="post" class="d-inline ms-3">
                    <input type="hidden" name="id" value="'.htmlspecialchars($row['id']).'">
                    <a class="link-opacity-50-hover text-decoration-none" href="#" onclick="event.preventDefault(); this.parentNode.submit()">Delete</a>
                </form>';
            echo '</div>';
            echo '</footer>';
            echo '        </blockquote>';
            echo '    </div>';
            echo '</div>';
        }
        echo '</div>';
    }catch (PDOException $e){
        $title = 'An error has occured';
        $output= 'Database error: ' . $e->getMessage();
    }
}
$output = ob_get_clean();
include 'layouts/index.html.php';
    