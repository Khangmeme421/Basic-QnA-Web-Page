<?php
$title = 'Ask';
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/dbfunctions.php';
$nav = nav();
//redirect user to home page if not loged in
ob_start();
if (!isset($_SESSION['userid'])) {
    include 'layouts/req_login.html.php';
}else{
    $sub = subjects();
    include 'layouts/ask.html.php';
    // Get form data
    try{
        if (isset($_POST['subject']) && $_POST['subject'] == "Choose a subject")
            create_Alert('warning', 'Please choose a subject');
        elseif (isset($_POST['qtitle'])){
            //basic post data
            $title = $_POST['qtitle'];
            $content = $_POST['qcont'];
            $subject_id = $_POST['subject'];
            $user_id = $_SESSION['userid'];
            $date = date('Y-m-d H:i:s');
            //if post has image
            if (!empty($_FILES["fileToUpload"]["name"])){
                include 'includes/uploadFile.php';
            }
            $image_path = '../uploads/' . basename($_FILES["fileToUpload"]["name"]);
            $data = [
                'title' => $title,
                'content' => $content,
                'idsubject' => $subject_id,
                'iduser' => $user_id,
                'date_create' => $date,
                'image' => $image_path
            ];
            $stmt = $pdo->prepare("INSERT INTO questions (title, content, idsubject, iduser, date_create, image) VALUES (:title, :content, :idsubject, :iduser, :date_create, :image)");
            $stmt->execute($data);
            //Send success message to user
            create_Alert('success', 'Your post created successfully');
            }
        }
    catch (PDOException $e){
        $output= 'Database error: ' . $e->getMessage();
    }
}
$output = ob_get_clean();
include 'layouts/index.html.php';
    