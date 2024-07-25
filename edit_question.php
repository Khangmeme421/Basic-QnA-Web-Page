<?php
$title = 'Edit Question';
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/dbfunctions.php';
$nav = nav();
ob_start();

// Retrieve the question data from the database using the ID
if (isset($_GET['id'])){
    include 'includes/DatabaseConnection.php';
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM questions WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $row = $stmt->fetch();
    $qtitle = htmlspecialchars($row['title']);
    $qcontent = htmlspecialchars($row['content']);
    $qsubmit = htmlspecialchars('Update');
    $imgsrc = $row['image'];
    //echo "<img src='http://localhost/coursebt/uploads/silly cart.jpeg' alt='Image' width='64' height='64'>";
    //Retrieve subjects name from database
    ob_start();
    $sub = subjects();

    include 'layouts/ask.html.php';
}
if (isset($_POST['qtitle'])){
    $title = $_POST['qtitle'];
    $content = $_POST['qcont'];
    $subject_id = $_POST['subject'];
    $user_id = $_SESSION['userid'];
    $date = date('Y-m-d H:i:s');

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
    echo '<div class="alert alert-success d-flex justify-content-center align-items-center mt-5 mx-auto" role="alert" style="max-width: 18rem;" id="alert">
                Your post created successfully
            </div>
            <script>
                setTimeout(function() {
                    document.getElementById("alert").remove();
                }, 4000);
            </script>';
}
// Populate the form fields with the existing data
$output = ob_get_clean();
include 'layouts/index.html.php';
?>
