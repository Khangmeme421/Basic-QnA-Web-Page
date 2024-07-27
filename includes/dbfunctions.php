<?php
//get the user's role, usually on login success 
//send php mailer
function get_role(){
    include 'includes/DatabaseConnection.php';
    $role = NULL;
    try{
        if (isset($_SESSION['userid'])) {
            $userid = (int)$_SESSION['userid'];
            $sql = "SELECT `role` FROM `users` WHERE id=$userid";
            $user = $pdo->query($sql);
            foreach($user as $row){
                $role = $row['role'];
            }
        }
    }catch (PDOException $e){
        $role = NULL;
    }
    return $role;
}

// delete a $target in $table 
function delete($table,$target){
    include 'includes/DatabaseConnection.php';
    $sql = "DELETE FROM $table
    WHERE id = $target";
    $delete = $pdo->prepare($sql);
    $delete->execute();
}

function delete_post($target, $image){
    include 'includes/DatabaseConnection.php';
    $sql = "DELETE FROM `questions`
    WHERE id = $target";
    $delete = $pdo->prepare($sql);
    $delete->execute();
    unlink($image);
}
// navigation pane based on role
function nav(){
    ob_start();
    if (isset($_SESSION['role'])){
        include 'layouts/student.html.php';
        if ($_SESSION['role']=='admin'){
            include 'layouts/ad.html.php';
        }
        include 'layouts/user.html.php';
    }else{
        include 'layouts/loginbtn.html.php';
    }
    $nav = ob_get_clean();
    return $nav;
}

// create subject drop down list
function subjects(){
    ob_start();
    include 'includes/DatabaseConnection.php';
    $sql = 'SELECT * FROM `subject`';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $subjects = $stmt->fetchAll();
    foreach ($subjects as $subject){
        echo '<option value="'.$subject['id'].'">'.$subject['sub_name'].'</option>';
    }
    $sub = ob_get_clean();
    return $sub;
}

function count_data($table,$target,$column){
    include 'includes/DatabaseConnection.php';
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM $table WHERE $column = :target");
    $stmt->bindParam(':target', $target);
    $stmt->execute();
    return $stmt->fetchColumn();
}

// manage upvote
function upvote($idQuestion,$idUser){
    include 'includes/DatabaseConnection.php';
    // Check if the upvote already exists
    $stmt = $pdo->prepare("SELECT * FROM upvote WHERE idquestion = :idquestion AND iduser = :iduser");
    $stmt->bindParam(':idquestion', $idQuestion);
    $stmt->bindParam(':iduser', $idUser);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // If the upvote already exists, delete it
        $stmt = $pdo->prepare("DELETE FROM upvote WHERE idquestion = :idquestion AND iduser = :iduser");
        $stmt->bindParam(':idquestion', $idQuestion);
        $stmt->bindParam(':iduser', $idUser);
        $stmt->execute();
    } else {
        // If the upvote does not exist, insert it
        $stmt = $pdo->prepare("INSERT INTO upvote (idquestion, iduser) VALUES (:idquestion, :iduser)");
        $stmt->bindParam(':idquestion', $idQuestion);
        $stmt->bindParam(':iduser', $idUser);
        $stmt->execute();
    }
}