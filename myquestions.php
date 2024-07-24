<?php
$title = 'My Questions';
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/dbfunctions.php';
$nav = nav();
ob_start();
if(!isset($_SESSION['userid'])){
    header('Location: index.php');
}else{
    if (isset($_POST['id'])) {
        $id = htmlspecialchars($_POST['id']);
        delete('questions',$id);
    }
    try{    
        $userid = (int)$_SESSION['userid'];
        $sql = "SELECT q.id,q.date_create, q.iduser, q.title, q.content, q.idsubject, s.sub_name AS subject_name
        FROM questions q
        JOIN users u ON q.iduser = u.id
        JOIN subject s ON q.idsubject = s.id
        WHERE q.iduser = $userid";
        
        $questions = $pdo->query($sql);
        ob_start();
        echo '<div class="questions-container">';
        foreach($questions as $row){
            echo '<div class="card-deck">';
            echo '<div class="card col-12 col-sm-6 mt-5 ms-5 mb-2">';
            echo '    <div class="card-header">';
            echo '<a class="link-opacity-50-hover text-decoration-none float-start" href="index.php?subid='.htmlspecialchars($row['idsubject']).'">'.htmlspecialchars($row['subject_name']).'</a>';
            echo '<div class="float-end">';
            echo '<a class="link-opacity-50-hover text-decoration-none" href="edit_question.php?id='.htmlspecialchars($row['id']).'">Edit</a>';
            echo '<form action="" method="post" class="d-inline ms-3">
                    <input type="hidden" name="id" value="'.htmlspecialchars($row['id']).'">
                    <a class="link-opacity-50-hover text-decoration-none" href="#" onclick="event.preventDefault(); this.parentNode.submit()">Delete</a>
                    </form>';
            echo '    </div>';
            echo '    </div>';
            echo '    <div class="card-body">';
            echo '        <blockquote class="blockquote mb-0">';
            echo '<a class="link-opacity-50-hover text-decoration-none" href="index.php?id='.htmlspecialchars($row['id']).'">';
            echo '<p>'.htmlspecialchars($row['title']).'</p>';
            echo '</a>';
            echo '            <footer class="blockquote-footer">'.htmlspecialchars($row['date_create']).'</footer>';
            echo '        </blockquote>';
            echo '    </div>';
            echo '</div>';
            echo '</div>';
        }
        
    }catch (PDOException $e){
        $title = 'An error has occured';
        $output= 'Database error: ' . $e->getMessage();
    }

}
$output = ob_get_clean();
include 'layouts/index.html.php';