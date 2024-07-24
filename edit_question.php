<?php
$title = 'Edit Question';
session_start();
include 'includes/nav.php';
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
    
    $stmt = $pdo->prepare("UPDATE questions SET title = :title, content = :content, idsubject = :idsubject, iduser = :iduser, date_create = :date_create WHERE id = :id");
    //array_merge is new need to be ref in the report.
    $stmt->execute(array_merge($data, ['id' => $id]));
    echo "New record created successfully";
}
// Populate the form fields with the existing data
?>
<?php
$output = ob_get_clean();
include 'layouts/index.html.php';
?>
