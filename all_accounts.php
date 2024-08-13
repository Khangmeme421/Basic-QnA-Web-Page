<?php
$title = 'All Accounts';    // Set the title of the page
session_start();    // Start the session
include 'includes/DatabaseConnection.php';
include 'includes/dbfunctions.php';
set_cookie();//Retrieve data from cookie
$nav = nav();//Get the navigation menu
ob_start();
//delete account with given id via POST method
if (isset($_POST['id'])) {
    $id = htmlspecialchars($_POST['id']);   // This is used to delete an account with the given id
    delete('users',$id);    // Get the id from the POST request and sanitize it using htmlspecialchars
}
try{
    //select everyone except the current user
    $sql = 'SELECT * FROM `users` WHERE id !='.$_SESSION['userid'];
    // Execute the query and store the result in the $users variable
    $users = $pdo->query($sql);
    //display account list
    foreach ($users as $user){
        // Create a card for each account
        echo '<div class="card col-12 col-sm-6 mt-5 ms-5 mb-2">';
        echo '    <div class="card-body">';

        // Display the account information
        echo '         <div class="float-start">';
        echo '          <p><a class="text-decoration-none" href="profile.php?id='.$user['id'].'">'.$user['name'].'</a></p>';
        echo '          <p>'.$user['email'].'</p>';
        echo '         </div>';

        // Display the delete button for each account
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
    // Handle any database-related errors
    $title = 'An error has occured';
    $output= 'Database error: ' . $e->getMessage();
}
$output = ob_get_clean();
include 'layouts/index.html.php'; //Display the output
