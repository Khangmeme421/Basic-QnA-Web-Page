<?php
$title = 'Notifications';   //Set the title of the page
session_start();    //Start the session
include 'includes/DatabaseConnection.php';
include 'includes/dbfunctions.php';
set_cookie(); //Retrieve data from cookie
$nav = nav(); //Get the navigation menu
ob_start();     //Start output buffering
if (!isset($_SESSION['userid'])) {
    header('Location: index.php');  //Redirect to home page if not logged in
}else{
    // Not optimized delete fucntion
    if (isset($_POST['id'])) {
        $id = htmlspecialchars($_POST['id']);  // Get the ID of the notification to delete
        delete('notifications',$id);    // Delete the notification
        header('Location: notifications.php');  //Reload the page
    }
    try{
        // Query the database to get the notifications for the current user
        $sql = "SELECT n.id, n.idquestion, n.idsender, n.content, n.date_create, u.username 
        FROM notifications n 
        LEFT JOIN users u ON n.idsender = u.id 
        WHERE n.iduser = ".$_SESSION['userid'];
        $questions = $pdo->query($sql);
        $questions = $pdo->query($sql);

        // Start the output for the notifications container
        echo '<div class="questions-container">';
        foreach($questions as $row){
            // Initialize variables for the sender and question
            $sender = null;
            $question = null;
            if ($row['idsender']!= NULL && $row['idquestion']){
                // If the sender and question IDs are not null, set the variables
                $sender = '<a class="text-decoration-none" href="profile.php?id='.$row['idsender'].'">'.htmlspecialchars($row['username']).'</a>';
                $question = '<a class="text-decoration-none" href="index.php?id='.$row['idquestion'].'">'.'post'.'</a>';
            }
            // Output the notification card
            echo '<div class="card col-sm-6 mt-5 ms-5 mb-2">';
            echo '    <div class="card-body">';
            echo '        <blockquote class="blockquote mb-0">';
            echo '<p>'.$sender.' '.htmlspecialchars($row['content']).$question.'</p>';
            echo '            <footer class="blockquote-footer">'.htmlspecialchars($row['date_create']);
            echo '<div class="float-end">';
            // Output the delete button for each notification
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
        // Close the notifications container
        echo '</div>';
    }catch (PDOException $e){
        $title = 'An error has occured';
        $output= 'Database error: ' . $e->getMessage();
    }
}
$output = ob_get_clean();   // Get the output buffer and store it in the $output variable
include 'layouts/index.html.php';   // Display the layout
    