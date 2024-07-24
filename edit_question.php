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
    //Retrieve subjects name from database
    ob_start();
    include 'includes/subjects.php';
    $sub = ob_get_clean();

    include 'layouts/ask.html.php';
}
if (isset($_POST['qtitle'])){
    $title = $_POST['qtitle'];
    $content = $_POST['qcont'];
    $subject_id = $_POST['subject'];
    $user_id = $_SESSION['userid'];
    $date = date('Y-m-d H:i:s');
    $data = [
        'title' => $title,
        'content' => $content,
        'idsubject' => $subject_id,
        'iduser' => $user_id,
        'date_create' => $date
    ];
    
    $stmt = $pdo->prepare("UPDATE questions SET title = :title, content = :content, idsubject = :idsubject, iduser = :iduser, date_create = :date_create WHERE id = :id");
    //array_merge is new need to be ref in the report.
    $stmt->execute(array_merge($data, ['id' => $id]));
    echo '<div class="alert alert-warning d-flex justify-content-center align-items-center mt-5 mx-auto" role="alert" style="max-width: 18rem;" id="alert">
                Your question is updated
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
