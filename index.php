<?php
$title = 'Home';
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/dbfunctions.php';
$nav = nav();
ob_start();
//handling upvote
if (isset($_POST['upvote']) && isset($_SESSION['userid'])) {
    $idQuestion = $_POST['upvote'];
    $idUser = $_SESSION['userid'];
    upvote($idQuestion,$idUser);
}
// if a post is selected
function displayquestion($pdo){
    //display the post
    $id = htmlspecialchars($_GET['id']);
    $sql = "SELECT q.id,q.image,q.iduser,q.title,q.content FROM questions q
            WHERE id =$id";
    $question = $pdo->query($sql);
    $question_id = null;
    $q_uid = null;
    foreach($question as $inf){
        echo '<div class="mt-5 ms-5 mb-2 ">';
        echo '<h2>'.htmlspecialchars($inf['title']).'</h2>';
        $img = 'http://localhost/coursebt'.substr($inf['image'],2);
        $onerror_attr = 'onerror="this.style.display=\'none\'"';
        echo "<img src=$img alt='Image'".$onerror_attr." class='mb-3'>";
        echo '<p>'.htmlspecialchars($inf['content']).'</p>';
        $question_id = $inf['id'];
        $q_uid = $inf['iduser'];
        echo '</div>';
    }
    include 'layouts/comment.html.php';
    //delete comment in database
    if (isset($_POST['comment_id'])) {
        $id = htmlspecialchars($_POST['comment_id']);
        delete('answers',$id);
        if (isset($_POST['iduser'])){
            $content = 'Admin deleted your comment';
            admin_delete($_POST['iduser'],$content);
        }
    }
    //insert comment to database
    if (isset($_POST['comment'])){
        $comment = $_POST['comment'];
        $user_id = $_SESSION['userid'];
        $date = date('Y-m-d H:i:s');
        $data = [
            'iduser' => $user_id,
            'idquestion' => $question_id,
            'date_create' => $date,
            'content' => $comment
        ];
        $stmt = $pdo->prepare("INSERT INTO answers SET content = :content, idquestion = :idquestion, iduser = :iduser, date_create = :date_create");
        //array_merge is new need to be ref in the report.
        $stmt->execute($data);
        // if user does not comment their own post send a notification to receiver
        if ($q_uid != $_SESSION['userid']){
            $data = null;
            $comment = 'commented on your ';
            $data = [
                'idsender' => $_SESSION['userid'],
                'iduser' => $q_uid,
                'idquestion' => $question_id,
                'date_create' => $date,
                'content' => $comment
            ];
            $smfc = $pdo->prepare("INSERT INTO notifications SET content = :content, idsender = :idsender, idquestion = :idquestion, iduser = :iduser, date_create = :date_create");
            $smfc->execute($data);
        }
    }
    displayAnswers($pdo, $id);
}
if(isset($_GET['subid'])) {
    //displayProblems($pdo, $_GET['subid']);
    $tit = 'Have a problem? Just <a class="text-decoration-none" href="ask.php">ask</a>';
    displayQuestions($pdo, array('subid' => $_GET['subid'],'title' => $tit));
}elseif(isset($_GET['id'])) {
    displayquestion($pdo);
}
else {
    //use var name != $title to avoid conflict in the code
    $tit = 'Have a problem? Just <a class="text-decoration-none" href="ask.php">ask</a>';
    displayQuestions($pdo, array('title' => $tit));
}
$output = ob_get_clean();
include 'layouts/index.html.php';
