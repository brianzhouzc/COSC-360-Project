<?php
require_once 'database.php';
require_once 'helper.php';
require_once 'comments_functions.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case "get":
            $post_id = getValueFromKey($_POST, 'post_id');
            if (isset_notempty($post_id)) {
                $results = getCommentsByPostId($connection, intval($post_id));

                $comments = array();
                while ($row = $results->fetch_assoc()) {
                    array_push($comments, array("id" =>  $row['id'], "username" => $row['username'], "content" => $row['content'], "timestamp" => $row['timestamp'], "views" => $row['views']));
                }
                exit(dataResponse(200, "Success", array("comments" => $comments)));
            } else {
                exit(errorResponse(400, "Missing post_id"));
            }
            break;
    }
} else {
    exit(errorResponse(400, "Missing action"));
}
