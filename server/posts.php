<?php
require_once 'database.php';
require_once 'helper.php';
require_once 'users_functions.php';
require_once 'posts_functions.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case "create":
            $username = getValueFromKey($_POST, 'username');
            $session = getValueFromKey($_POST, 'session');
            $content = getValueFromKey($_POST, 'content');
            if (isset_notempty($username, $session, $content)) {
                createPost($connection, $username, $session, $content);
            } else {
                exit(errorResponse(400, "Missing username/session/content"));
            }
            break;

        case "edit":
            break;

        case "remove":
            break;

        case "search":
            $term = getValueFromKey($_POST, 'term');
            if (isset_notempty($term)) {
                $result = getPostsBySearch($connection, $term);
                $posts = array();
                while ($row = $results->fetch_assoc()) {
                    array_push($posts, array("username" => $row['username'], "content" => $row['content'], "timestamp" => $row['timestamp']));
                }
                exit(dataResponse(200, "Success", array("posts" => $posts)));
            } else {
                exit(errorResponse(400, "Missing search terms"));
            }
            break;

        case "get":
            $order = getValueFromKey($_POST, 'order');
            $limit = getValueFromKey($_POST, 'limit');
            $offset = getValueFromKey($_POST, 'offset');
            $results = '';

            if (!isset_notempty($limit))
                $limit = 5;
            if (!isset_notempty($offset))
                $offset = 0;
            switch ($offset) {
                case "DESC":
                    $results = getPostsDESC($connection, $limit, $offset);
                    break;
                case "ASC":
                    $results = getPostsASC($connection, $limit, $offset);
                    break;
                default:
                    $results = getPostsDESC($connection, $limit, $offset);
                    break;
            }
            $posts = array();
            while ($row = $results->fetch_assoc()) {
                array_push($posts, array("username" => $row['username'], "content" => $row['content'], "timestamp" => $row['timestamp']));
            }
            exit(dataResponse(200, "Success", array("posts" => $posts)));
            break;

        default:
            exit(errorResponse(400, "Invalid action"));
    }
} else {
    $connection->close();
    exit(errorResponse(400, "Missing action"));
}
