<?php
require 'database.php';
require_once 'helper.php';
require_once 'users.php';
require_once 'posts.php';

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
        //post does not exist
    }
}
