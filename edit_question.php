<?php
$title = 'Edit Question';   // Set the title of the page
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/dbfunctions.php';
set_cookie();    //retrieve data from cookie
$nav = nav();   // Get the navigation menu
ob_start();
// Retrieve the question data from the database using the ID
if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
    include 'includes/DatabaseConnection.php';
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM questions WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $row = $stmt->fetch();
    if (!$row)
        include 'layouts/404.html.php';
    else{
        $qtitle = htmlspecialchars($row['title']);
        $qcontent = htmlspecialchars($row['content']);
        $qsubmit = htmlspecialchars('Update');
        $imgsrc = $row['image'];
        ob_start();
        $sub = subjects();

        include 'layouts/ask.html.php'; //use same layout with "ask" page
    }
}else
    include 'layouts/404.html.php';
if (isset($_POST['qtitle'])){
    // Update the question data in the database
    $title = $_POST['qtitle'];
    $content = $_POST['qcont'];
    $subject_id = $_POST['subject'];
    $user_id = $_SESSION['userid'];
    $date = date('Y-m-d H:i:s');

    include 'includes/uploadFile.php';  //Handle image file upload
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
    echo '<div class="alert alert-success d-flex justify-content-center align-items-center mt-5 mx-auto" role="alert" style="max-width: 18rem;" id="alert">
                Your post created successfully
            </div>';
}
// Populate the form fields with the existing data
$output = ob_get_clean();
include 'layouts/index.html.php';
?>
