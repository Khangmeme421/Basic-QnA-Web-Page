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
// send a message if admin delete
function admin_delete($id_victim,$content){
    $data = [
        'idsender' => null,
        'iduser' => $id_victim,
        'idquestion' => null,
        'date_create' => date('Y-m-d H:i:s'),
        'content' => $content
    ];
    include 'includes/DatabaseConnection.php';
    $smfc = $pdo->prepare("INSERT INTO notifications SET content = :content, idsender = :idsender, idquestion = :idquestion, iduser = :iduser, date_create = :date_create");
    $smfc->execute($data);
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

function count_data($table,$column,$target){
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

//display question
function displayQuestions($pdo, $options = array()) {
    try {
        $sql = 'SELECT q.id, q.image, q.iduser, q.title, q.date_create, q.content, q.idsubject, u.username, s.sub_name AS subject_name
                FROM questions q
                JOIN users u ON q.iduser = u.id
                JOIN subject s ON q.idsubject = s.id';
        if (!empty($options)) {
            if (isset($options['subid'])) {
                $sql .= ' WHERE q.idsubject = ' . htmlspecialchars($options['subid']);
            } elseif (isset($options['userid'])) {
                $sql .= ' WHERE q.iduser = "' . htmlspecialchars($options['userid']) . '"';
            }
        }

        $questions = $pdo->query($sql);
        ob_start();

        if (isset($options['title'])) {
            echo '<h2 class="mt-5 ms-5 mb-2">' . $options['title'] . '</h2>';
        }
        foreach ($questions as $row) {
            echo '<div class="card-deck">';
            echo '<div class="card col-12 col-sm-6 mt-5 ms-5 mb-2">';
            echo '    <div class="card-header">';
            echo '<a class="link-opacity-50-hover text-decoration-none" href="index.php?subid=' . htmlspecialchars($row['idsubject']) . '">' . htmlspecialchars($row['subject_name']) . '</a>';

            if (isset($options['userid'])) {
                echo '<div class="float-end">';
                echo '<a class="link-opacity-50-hover text-decoration-none" href="edit_question.php?id=' . htmlspecialchars($row['id']) . '">Edit</a>';
                echo '<form action="" method="post" class="d-inline ms-3">
                        <input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '">
                        <a class="link-opacity-50-hover text-decoration-none" href="#" onclick="event.preventDefault(); this.parentNode.submit()">Delete</a>
                        </form>';
                echo '    </div>';
            } elseif (isset($_SESSION['role'])&& $_SESSION['role']=='admin') {
                echo '      <div class="float-end">';
                echo '<form action="" method="post" class="d-inline ms-3">
                                <input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '">
                                <input type="hidden" name="iduser" value="' . htmlspecialchars($row['iduser']) . '">
                                <a class="link-opacity-50-hover text-decoration-none" href="#" onclick="event.preventDefault(); this.parentNode.submit()">Delete</a>
                                </form>';
                echo '      </div>';
            }

            echo '    </div>';
            echo '    <div class="card-body">';
            echo '        <blockquote class="blockquote mb-0">';
            echo '<a class="link-opacity-50-hover text-decoration-none" href="index.php?id=' . htmlspecialchars($row['id']) . '">';
            echo '<p>' . htmlspecialchars($row['title']) . '</p>';
            $img = 'http://localhost/coursebt' . substr($row['image'], 2);
            $onerror_attr = 'onerror="this.style.display=\'none\'"';
            echo "<img src=$img alt='Image' width='256' height='256'" . $onerror_attr . " class='mb-3'>";
            echo '</a>';
            echo '            <footer class="blockquote-footer">' . htmlspecialchars($row['date_create']) . ' <a class="link-opacity-50-hover text-decoration-none" href="profile.php?id=' . htmlspecialchars($row['iduser']) . '"><cite title="Source Title">' . htmlspecialchars($row['username']) . '</a></cite></footer>';
            echo '        </blockquote>';
            echo '    </div>';
            $upvote = count_data('upvote','idquestion',$row['id']);
            $comment_count = count_data('answers','idquestion',$row['id']);
            echo '  <div class="card-footer">';
            echo '<form action="" method="post" class="d-inline ms-1">
            <input type="hidden" name="upvote" value="'.htmlspecialchars($row['id']).'">
            <a class="link-opacity-50-hover text-decoration-none" href="#" onclick="event.preventDefault(); this.parentNode.submit()">▲</a>
             </form>';        
            echo '       <small class="text-body-secondary me-5">'.$upvote.'</small>
                    <a class="link-opacity-50-hover text-decoration-none" href="index.php?id='.$row['id'].'"><small class="text-body-secondary ms-5">💬'.$comment_count.'</small></a>
                    </div>';
            echo '</div>';
            echo '</div>';
        }
    } catch (PDOException $e) {
        $title = 'An error has occured';
        $output = 'Database error: ' . $e->getMessage();
    }
}

//display comments
function displayAnswers($pdo, $idQuestion){
    $sql = "SELECT a.id,a.content, a.iduser, a.date_create, u.username 
    FROM answers a 
    JOIN users u ON a.iduser = u.id 
    WHERE a.idquestion = $idQuestion";
    $comments = $pdo->query($sql);
    foreach($comments as $comment){
        echo '<div class="card col-sm-4 mt-5 ms-5 mb-2">';
        //edit need to be rework
        if ((isset($_SESSION['userid'])&& $_SESSION['userid'] == $comment['iduser'] ) || (isset($_SESSION['userid'])&& $_SESSION['role'] == 'admin')) {
            echo '<div class="dropdown position-absolute top-0 end-0" >
                  <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-image: none !important;">
                    ⋮
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
            
            if ($_SESSION['userid'] == $comment['iduser']) {
                echo '<li><a class="dropdown-item" href="#">Edit</a></li>';
            }
            
            echo '<li>
                  <form action="" method="post" class="d-inline">
                  <input type="hidden" name="comment_id" value="'.htmlspecialchars($comment['id']).'">';
            
            if ($_SESSION['role'] == 'admin') {
                echo '<input type="hidden" name="iduser" value="'.htmlspecialchars($comment['iduser']).'">';
            }
            
            echo '<button class="dropdown-item text-decoration-none" type="submit">Delete</button>
                  </form>
                  </li>
                  </ul>
                  </div>';
        }
        echo '<p class="mb-1">'.htmlspecialchars($comment['username']).': '.htmlspecialchars($comment['content']).'</p>';
        echo '<p>'.htmlspecialchars($comment['date_create']);
        echo '</div>';
    }
}