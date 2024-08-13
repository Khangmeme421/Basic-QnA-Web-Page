<?php
$title = 'Feedback';    // Set the title of the page
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/dbfunctions.php';
set_cookie();   //retrieve data from cookie
$nav = nav();   // Get the navigation menu
ob_start();
//redirect user to home if user is not admin
if (!isset($_SESSION['role']) || $_SESSION['role']!='admin') {
    header('Location: index.php');
}else{
    //when user delete a feedback
    if (isset($_POST['del'])) {
        $id = htmlspecialchars($_POST['del']);
        delete('feedback',$id);
        header('Location: feedback.php');
    }
    // when a feedback is selected
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT f.id, f.iduser, f.content, f.title, u.name
        FROM feedback f
        JOIN users u ON f.iduser = u.id
        WHERE f.id=".$id;
        $feedbacks = $pdo->query($sql);
        //display only title and content
        foreach($feedbacks as $row){
            echo '<h2 class="mt-5 ms-5 mb-2">'.$row['title'].'</h2>';
            echo '<p  class="mt-5 ms-5 mb-2">'.$row['content'].'</p>';
        }
    }else 
    try{
        // SQL query to retrieve feedback data with user information
        $sql = "SELECT f.id, f.iduser, f.content, f.title, u.name
        FROM feedback f
        JOIN users u ON f.iduser = u.id";
        // Execute the SQL query and store the result in $feedbacks
        $feedbacks = $pdo->query($sql);
        foreach($feedbacks as $row){
            // Start of a feedback card
            echo '<div class="card col-sm-6 mt-5 ms-5 mb-2">';
            echo '<div class= "card-header">';  // Card header with user information
            echo '<a class="link-opacity-50-hover text-decoration-none" href="profile.php?id='.$row['iduser'].'">'.$row['name'].'</a>';
            
            // Delete button
            echo '<div class="float-end">';
            echo '<form action="" method="post" class="d-inline ms-3">
                    <input type="hidden" name="id" value="'.htmlspecialchars($row['id']).'">
                    <a class="link-opacity-50-hover text-decoration-none" href="#" onclick="event.preventDefault(); this.parentNode.submit()">Delete</a>
                </form>';
            echo '</div>';
            echo '</div>';
            
            // Card body with feedback title
            echo '    <div class="card-body">';
            echo '        <blockquote class="blockquote mb-0">';
            echo '<p><a class="link-opacity-50-hover text-decoration-none" href="feedback.php?id='.$row['id'].'">'.htmlspecialchars($row['title']).'</a></p>';
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
    