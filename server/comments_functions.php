<?php
require 'database.php';
require_once 'helper.php';
require_once 'posts_functions.php';
require_once 'users_functions.php';


function getCommentsByPostId($connection, $post_id)
{
    $post = getPostById($connection, $post_id);

    if (isset($post)) {
        $sql = "SELECT * FROM comments WHERE post_id = ? ORDER BY timestamp DESC;";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    } else {
        return null;
    }
}

function createComment($connection, $post_id, $content, $username, $session)
{
    if (authenticateUser($connection, $username, $session)) {
        $post = getPostById($connection, $post_id);

        if (isset($post)) {
            $sql = "INSERT INTO comments (post_id, username, content) VALUES (?, ?, ?);";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("sss", $post_id, $username, $content);
            $stmt->execute();
            exit(dataResponse(200, "Success"));
        } else {
            exit(errorResponse(400, "Invalid post"));
        }
    } else {
        exit(errorResponse(400, "Unauthorize user"));
    }
}
