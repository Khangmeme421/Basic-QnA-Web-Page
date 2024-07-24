<?php
$title = 'Ask';
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/dbfunctions.php';
$nav = nav();

if (!isset($_SESSION['userid'])) {
    header("Location: index.php");
}else{
    
    //include 'includes/subjects.php';
    $sub = subjects();
    // ASk work not properly, cant upload IMG 
    ob_start();
    include 'layouts/ask.html.php';
    include 'includes/DatabaseConnection.php';
    // Get form data
    try{
        if (isset($_POST['qtitle'])){
            $title = $_POST['qtitle'];
            $content = $_POST['qcont'];
            $subject_id = $_POST['subject'];
            $user_id = $_SESSION['userid'];
            $date = date('Y-m-d H:i:s');
                // Handle file upload
            $img = $_FILES['img'];
            $img_tmp = $img['tmp_name'];
            $img_name = $img['name'];
            $img_type = $img['type'];
            $img_size = $img['size'];

            // Check if the file is an image
            if ($img_type == 'image/jpeg' || $img_type == 'image/png' || $img_type == 'image/gif') {
                // Store the image data in the content column
                $content = addslashes(file_get_contents($img_tmp));
            } 
            $data = [
                'title' => $title,
                'content' => $content,
                'idsubject' => $subject_id,
                'iduser' => $user_id,
                'date_create' => $date
            ];
            $stmt = $pdo->prepare("INSERT INTO questions (title, content, idsubject, iduser, date_create) VALUES (:title, :content, :idsubject, :iduser, :date_create)");
            $stmt->execute($data);
            echo "New record created successfully";
        }
    }catch (PDOException $e){
        $output= 'Database error: ' . $e->getMessage();
    }
    $output = ob_get_clean();
}
include 'layouts/index.html.php';
    