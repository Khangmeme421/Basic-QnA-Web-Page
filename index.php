<?php
$title = 'Home';
session_start();
include 'includes/DatabaseConnection.php';
include 'includes/dbfunctions.php';
set_cookie();
$nav = nav();
ob_start();
//handling upvote
if (isset($_POST['upvote']) && isset($_SESSION['userid'])) {
    $idQuestion = $_POST['upvote'];
    $idUser = $_SESSION['userid'];
    upvote($idQuestion,$idUser);
}
/**
 * Displays a question and its answers on the index page.
 */
function displayQuestion($pdo) {
    // Retrieve the question ID from the URL parameter
    $questionId = htmlspecialchars($_GET['id']);

    // Prepare the SQL query to fetch the question details
    $sql = "SELECT q.id, q.image, q.iduser, q.title, q.content FROM questions q
            WHERE id = :questionId";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':questionId', $questionId);
    $statement->execute();

    // Fetch the question details from the database
    $question = $statement->fetch();

    // If the question exists, display it
    if ($question) {
        echo '<div class="mt-5 ms-5 mb-2">';

        // Display the question title
        echo '<h2>' . htmlspecialchars($question['title']) . '</h2>';

        // Display the question image
        $imageUrl = 'http://localhost/coursebt' . substr($question['image'], 2);
        $onErrorAttr = 'onerror="this.style.display=\'none\'"';
        echo "<img src=$imageUrl alt='Image' $onErrorAttr class='mb-3'>";

        // Display the question content
        echo '<p>' . htmlspecialchars($question['content']) . '</p>';

        // Store the question ID and user ID for future reference
        $questionId = $question['id'];
        $questionUserId = $question['iduser'];
        // Include the HTML layout for comments
        include 'layouts/comment.html.php';
        // Handle deletion of a comment
        handleCommentDeletion($pdo, $questionId);
    
        // Handle addition of a new comment
        handleCommentAddition($pdo, $questionId, $questionUserId);
    
        // Display the answers to the question
        displayAnswers($pdo, $questionId);

        echo '</div>';
    }else
        include 'layouts/404.html.php';
}

/**
 * Handles the deletion of a comment.
 * @param int $questionId The ID of the question.
 */
function handleCommentDeletion($pdo, $questionId) {
    if (isset($_POST['comment_id'])) {
        $commentId = htmlspecialchars($_POST['comment_id']);
        delete('answers', $commentId);

        if (isset($_POST['iduser'])) {
            $content = 'Admin deleted your comment';
            adminDelete($_POST['iduser'], $content);
        }

        header("Location: index.php?id=$questionId");
    }
}

/**
 * Handles the addition of a new comment.
 * @param int $questionId The ID of the question.
 * @param int $questionUserId The ID of the question owner.
 */
function handleCommentAddition($pdo, $questionId, $questionUserId) {
    if (isset($_POST['comment'])) {
        $comment = $_POST['comment'];
        $userId = $_SESSION['userid'];
        $date = date('Y-m-d H:i:s');

        $data = [
            'userId' => $userId,
            'questionId' => $questionId,
            'dateCreated' => $date,
            'content' => $comment
        ];

        $sql = "INSERT INTO answers SET content = :content, idquestion = :questionId, iduser = :userId, date_create = :dateCreated";
        $statement = $pdo->prepare($sql);
        $statement->execute($data);

        if ($questionUserId != $userId) {
            $notificationData = [
                'senderId' => $userId,
                'userId' => $questionUserId,
                'questionId' => $questionId,
                'dateCreated' => $date,
                'content' => 'commented on your '
            ];

            $notificationSql = "INSERT INTO notifications SET content = :content, idsender = :senderId, idquestion = :questionId, iduser = :userId, date_create = :dateCreated";
            $notificationStatement = $pdo->prepare($notificationSql);
            $notificationStatement->execute($notificationData);
        }
    }
}
if(!empty($_GET['subid']) && is_numeric($_GET['subid'])) {
    //displayProblems($pdo, $_GET['subid']);
    $tit = 'Have a problem? Just <a class="text-decoration-none" href="ask.php">ask</a>';
    displayQuestions($pdo, array('subid' => $_GET['subid'],'title' => $tit));
}elseif(!empty($_GET['id']) && is_numeric($_GET['id'])) {
    displayquestion($pdo);
    //displayAnswers($pdo, $_GET['id']);
}
else {
    //use var name != $title to avoid conflict in the code
    $tit = 'Have a problem? Just <a class="text-decoration-none" href="ask.php">ask</a>';
    displayQuestions($pdo, array('title' => $tit));
}
$output = ob_get_clean();
include 'layouts/index.html.php';
