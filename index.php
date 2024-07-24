<?php
$title = 'Home';
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/dbfunctions.php';
$nav = nav();
ob_start();
function displayProblems($pdo, $subid = null) {
    try {
        $sql = 'SELECT q.id, q.iduser, q.title, q.date_create, q.content, q.idsubject, u.username, s.sub_name AS subject_name
        FROM questions q
        JOIN users u ON q.iduser = u.id
        JOIN subject s ON q.idsubject = s.id';
        if ($subid) {
            $sql .= ' WHERE q.idsubject = ' . htmlspecialchars($subid);
        }
        $questions = $pdo->query($sql);
        echo '<h2 class="mt-5 ms-5 mb-2">'.'Have a problem? Just '.'<a class="text-decoration-none" href="ask.php">'.'ask'.'</a></h2>';
        foreach ($questions as $row) {
            echo '<div class="card-deck">';
            echo '<div class="card col-12 col-sm-6 mt-5 ms-5 mb-2">';
            echo '    <div class="card-header">';
            echo '<a class="link-opacity-50-hover text-decoration-none" href="index.php?subid='.htmlspecialchars($row['idsubject']).'">'.htmlspecialchars($row['subject_name']).'</a>';
            echo '    </div>';
            echo '    <div class="card-body">';
            echo '        <blockquote class="blockquote mb-0">';
            echo '<a class="link-opacity-50-hover text-decoration-none" href="index.php?id='.htmlspecialchars($row['id']).'">';
            echo '<p>'.htmlspecialchars($row['title']).'</p>';
            echo '</a>';
            echo '            <footer class="blockquote-footer">'.htmlspecialchars($row['date_create']).' <a class="link-opacity-50-hover text-decoration-none" href="profile.php?id='.htmlspecialchars($row['iduser']).'"><cite title="Source Title">'. htmlspecialchars($row['username']).'</cite></a></footer>';
            echo '        </blockquote>';
            echo '    </div>';
            echo '</div>';
            echo '</div>';
        }
    } catch (PDOException $e) {
        $title = 'An error has occured';
        $output = 'Database error: ' . $e->getMessage();
    }
}
// if a post is selected
function displayquestion($pdo){
    $id = htmlspecialchars($_GET['id']);
    $sql = "SELECT q.id,q.iduser,q.title,q.content FROM questions q
            WHERE id =$id";
    $question = $pdo->query($sql);
    $question_id = null;
    $q_uid = null;
    foreach($question as $inf){
        echo '<div class="mt-5 ms-5 mb-2 ">';
        echo '<h2>'.htmlspecialchars($inf['title']).'</h2>';
        echo '<p>'.htmlspecialchars($inf['content']).'</p>';
        $question_id = $inf['id'];
        $q_uid = $inf['iduser'];
        echo '</div>';
    }
    include 'layouts/comment.html.php';
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
    $sql = "SELECT a.content, a.date_create, u.username 
    FROM answers a 
    JOIN users u ON a.iduser = u.id 
    WHERE a.idquestion = $id";
    $comments = $pdo->query($sql);
    foreach($comments as $comment){
        echo '<div class="card col-sm-4 mt-5 ms-5 mb-2">';
        echo '<p class="mb-1">'.htmlspecialchars($comment['username']).': '.htmlspecialchars($comment['content']).'</p>';
        echo '<p>'.htmlspecialchars($comment['date_create']);
        echo '</div>';
    }
}
if(isset($_GET['subid'])) {
    displayProblems($pdo, $_GET['subid']);
}elseif(isset($_GET['id'])) {
    displayquestion($pdo);
}
else {
    displayProblems($pdo);
}
$output = ob_get_clean();
include 'layouts/index.html.php';
