<?php
require_once 'database.php';
require_once 'helper.php';
require_once 'users_functions.php';
require_once 'posts_functions.php';

if (isset_notempty($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case "create":
            $username = getValueFromKey($_POST, 'username');
            $session = getValueFromKey($_POST, 'session');
            $title = getValueFromKey($_POST, 'title');
            $content = getValueFromKey($_POST, 'content');
            if (isset_notempty($username, $session, $title, $content)) {
                createPost($connection, $username, $session, $title, $content);
            } else {
                exit(errorResponse(400, "Missing username/session/title/content"));
            }
            break;

        case "edit":
            $post_id = getValueFromKey($_POST, 'postid');
            $username = getValueFromKey($_POST, 'username');
            $session = getValueFromKey($_POST, 'session');
            $title = getValueFromKey($_POST, 'title');
            $content = getValueFromKey($_POST, 'content');
            if (isset_notempty($post_id, $username, $session, $title, $content)) {
                editPost($connection, $post_id, $username, $session, $title, $content);
            } else {
                exit(errorResponse(400, "Missing postid/username/session/title/content"));
            }
            break;

        case "remove":
            $post_id = getValueFromKey($_POST, 'postid');
            $username = getValueFromKey($_POST, 'username');
            $session = getValueFromKey($_POST, 'session');
            if (isset_notempty($post_id, $username, $session)) {
                removePost($connection, $post_id, $username, $session);
            } else {
                exit(errorResponse(400, "Missing postid/username/session"));
            }
            break;

        case "search":
            $term = getValueFromKey($_POST, 'term');
            if (isset_notempty($term)) {
                $results = getPostsBySearch($connection, $term);
                $posts = array();
                while ($row = $results->fetch_assoc()) {
                    array_push($posts, array("id" => $row['id'], "username" => $row['username'], "content" => $row['content'], "timestamp" => $row['timestamp'], "views" => $row['views'], "title" => $row['title']));
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
            $post_id = getValueFromKey($_POST, 'post_id');
            $username = getValueFromKey($_POST, 'username');
            $results = '';
            if (isset_notempty($post_id)) {
                $result = getPostById($connection, $post_id);
                if (isset_notempty($result)) {
                    $post = array("post" => array(
                        "id" => $result['id'],
                        "username" => $result['username'], "title" => $result['title'],
                        "content" => $result['content'], "timestamp" => $result['timestamp'], "views" => $result['views']
                    ));
                    addToViews($connection, $post_id);
                    exit(dataResponse(200, "Success", $post));
                } else {
                    exit(errorResponse(400, "Post does not exsist"));
                }
            } else if (isset_notempty($username)) {
                $results = getPostsByUsername($connection, $username);
                if (isset_notempty($results)) {
                    $posts = array();
                    while ($row = $results->fetch_assoc()) {
                        array_push($posts, array(
                            "id" => $row['id'],
                            "username" => $row['username'], "title" => $row['title'],
                            "content" => $row['content'], "timestamp" => $row['timestamp'], "views" => $row['views']
                        ));
                    }
                    exit(dataResponse(200, "Success", array("posts" => $posts)));
                } else {
                    exit(errorResponse(400, "Post does not exsist"));
                }
            } else {
                if (!isset_notempty($limit))
                    $limit = 5;
                if (!isset_notempty($offset))
                    $offset = 0;
                switch ($order) {
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
                    array_push($posts, array(
                        "id" => $row['id'],
                        "username" => $row['username'], "title" => $row['title'],
                        "content" => $row['content'], "timestamp" => $row['timestamp'], "views" => $row['views']
                    ));
                }
                exit(dataResponse(200, "Success", array("posts" => $posts)));
            }

            break;
        case "popular":
            $limit = getValueFromKey($_POST, 'limit');
            $offset = getValueFromKey($_POST, 'offset');

            if (!isset_notempty($limit))
                $limit = 5;
            if (!isset_notempty($offset))
                $offset = 0;
            $results = getPostsTrending($connection, $limit);
            $posts = array();
            while ($row = $results->fetch_assoc()) {
                array_push($posts, array(
                    "id" => $row['id'],
                    "username" => $row['username'], "title" => $row['title'],
                    "content" => $row['content'], "timestamp" => $row['timestamp'], "views" => $row['views']
                ));
            }
            exit(dataResponse(200, "Success", array("posts" => $posts)));

        default:
            exit(errorResponse(400, "Invalid action"));
    }
} else {
    $connection->close();
    exit(errorResponse(400, "Missing action"));
}
