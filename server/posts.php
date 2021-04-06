<?php
require 'database.php';
require_once 'helper.php';
require_once 'users.php';

function createPost($connection, $username, $session, $content)
{
    if (authenticateUser($connection, $username, $session)) {
        //$user = getUserByName($connection, $username);
        $sql = "INSERT INTO posts (username, content) VALUES (?, ?);";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ss", $username, $content);
        $stmt->execute();
    } else {
        // smth else
    }
}

function updatePost($connection, $post_id, $username, $session, $content)
{
    if (authenticateUser($connection, $username, $session)) {

        //$user = getUserByName($connection, $username);
        $sql = "UPDATE posts SET content = ? WHERE post";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ss", $username, $content);
        $stmt->execute();
    } else {
        // smth else
    }
}

function getPostById($connection, $post_id)
{
    $sql = "SELECT * FROM posts WHERE id = ?;";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();

    return $post;
}

function getPostsByUsername()
{
}
