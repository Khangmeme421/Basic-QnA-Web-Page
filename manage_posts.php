<?php
$title = 'Manage Posts';
session_start();
include 'includes/dbfunctions.php';
$nav = nav();
include 'includes/DatabaseConnection.php';
ob_start();
if (isset($_POST['id']) && isset($_POST['iduser'])) {
    $id = $_POST['id'];
    delete('questions',$id);
    $userid = $_POST['iduser'];
    try{
        if ($userid != $_SESSION['userid']){
            $content = 'Admin deleted your post';
            $data = [
                'idsender' => null,
                'iduser' => $userid,
                'idquestion' => null,
                'date_create' => date('Y-m-d H:i:s'),
                'content' => $content
            ];
            $smfc = $pdo->prepare("INSERT INTO notifications SET content = :content, idsender = :idsender, idquestion = :idquestion, iduser = :iduser, date_create = :date_create");
            $smfc->execute($data);
        }
    }
    catch (PDOException $e){
        $title = 'An error has occured';
        $output= 'Database error: ' . $e->getMessage();
    }
}
function displayPosts($pdo, $subid = null) {
    try {
        $sql = 'SELECT q.id, q.iduser, q.title, q.date_create, q.content, q.idsubject, u.username, s.sub_name AS subject_name
        FROM questions q
        JOIN users u ON q.iduser = u.id
        JOIN subject s ON q.idsubject = s.id';
        if ($subid) {
            $sql .= ' WHERE q.idsubject = ' . htmlspecialchars($subid);
        }
        $questions = $pdo->query($sql);
        ob_start();
        echo '<h2 class="mt-5 ms-5 mb-2">'.'Posts Management</h2>';
        foreach ($questions as $row) {
            echo '<div class="card-deck">';
            echo '<div class="card col-12 col-sm-6 mt-5 ms-5 mb-2">';
            echo '    <div class="card-header">';
            if ($subid) {
                echo '<a class="link-opacity-50-hover text-decoration-none" href="index.php?subid='.htmlspecialchars($row['idsubject']).'">'.htmlspecialchars($row['subject_name']).'</a>';
            } else {
                echo '<a class="link-opacity-50-hover text-decoration-none" href="manage_posts.php?subid='.htmlspecialchars($row['idsubject']).'">'.htmlspecialchars($row['subject_name']).'</a>';
            }
            echo '      <div class="float-end">';
            echo '<form action="" method="post" class="d-inline ms-3">
                            <input type="hidden" name="id" value="'.htmlspecialchars($row['id']).'">
                            <input type="hidden" name="iduser" value="'.htmlspecialchars($row['iduser']).'">
                            <a class="link-opacity-50-hover text-decoration-none" href="#" onclick="event.preventDefault(); this.parentNode.submit()">Delete</a>
                            </form>';
            echo '      </div>';
            echo '    </div>';
            echo '    <div class="card-body">';
            echo '        <blockquote class="blockquote mb-0">';
            echo '<a class="link-opacity-50-hover text-decoration-none" href="index.php?id='.htmlspecialchars($row['id']).'">';
            echo '<p>'.htmlspecialchars($row['title']).'</p>';
            echo '</a>';
            echo '            <footer class="blockquote-footer">'.htmlspecialchars($row['date_create']).' <a class="link-opacity-50-hover text-decoration-none" href="profile.php?id='.htmlspecialchars($row['iduser']). '"><cite title="Source Title">'. htmlspecialchars($row['username']).'</a></cite></footer>';
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
if (isset($_GET['subid'])) {
    displayPosts($pdo, $_GET['subid']);
} else {
    displayPosts($pdo);
}
//include 'layouts/problems.html.php';  
$output = ob_get_clean();
include 'layouts/index.html.php';
