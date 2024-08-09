<?php
$title = 'Ask';
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/dbfunctions.php';
$nav = nav();
//redirect user to home page if not loged in
if (!isset($_SESSION['userid'])) {
    header("Location: index.php");
}else{
    $sub = subjects();
    ob_start();
    include 'layouts/ask.html.php';
    // Get form data
    try{
        if (isset($_POST['qtitle'])){
            //basic post data
            $title = $_POST['qtitle'];
            $content = $_POST['qcont'];
            $subject_id = $_POST['subject'];
            $user_id = $_SESSION['userid'];
            $date = date('Y-m-d H:i:s');
            //if post has image
            include 'includes/uploadFile.php';
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
            echo '<div class="alert alert-success d-flex justify-content-center align-items-center mt-5 mx-auto" role="alert" style="max-width: 18rem;" id="alert">
                        Your post created successfully
                    </div>';
        }
    }catch (PDOException $e){
        $output= 'Database error: ' . $e->getMessage();
    }
    $output = ob_get_clean();
}
include 'layouts/index.html.php';
    