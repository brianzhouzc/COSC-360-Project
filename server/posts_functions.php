<?php
require_once 'database.php';
require_once 'helper.php';
require_once 'users_functions.php';

function createPost($connection, $username, $session, $content)
{
    if (authenticateUser($connection, $username, $session)) {
        //$user = getUserByName($connection, $username);
        $sql = "INSERT INTO posts (username, content) VALUES (?, ?);";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ss", $username, $content);
        $stmt->execute();
        exit(dataResponse(200, "Success"));
    } else {
        exit(errorResponse(400, "Unauthorized user!"));
    }
}

function editPost($connection, $post_id, $username, $session, $content)
{
    if (authenticateUser($connection, $username, $session)) {
        $post = getPostById($connection, $post_id);
        if (strcmp($post['username'], $username) == 0) {
            //$user = getUserByName($connection, $username);
            $sql = "UPDATE posts SET content = ? WHERE post_id = ?;";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("ss", $post_id, $content);
            $stmt->execute();
            exit(dataResponse(200, "Success"));
        } else {
            exit(errorResponse(400, "User does not own post"));
        }
    } else {
        exit(errorResponse(400, "Unauthorize user"));
    }
}

function removePost($connection, $post_id, $username, $session)
{
    if (authenticateUser($connection, $username, $session)) {
        $post = getPostById($connection, $post_id);
        if (strcmp($post['username'], $username) == 0) {
            //$user = getUserByName($connection, $username);
            $sql = "DELETE FROM posts WHERE id = ?;";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("i", $post_id);
            $stmt->execute();
            exit(dataResponse(200, "Success"));
        } else {
            exit(errorResponse(400, "User does not own post"));
        }
    } else {
        exit(errorResponse(400, "Unauthorize user"));
    }
}

function getPostsBySearch($connection, $term)
{
    $sql = "SELECT * FROM posts WHERE content LIKE %?% OR username LIKE %?%;";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $term, $term);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
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

function getPostsByUsername($connection, $username)
{
    $sql = "SELECT * FROM posts WHERE username = ?;";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}

function getPostsDESC($connection, $limit = 5, $offset = 0)
{
    $sql = "SELECT * FROM posts ORDER BY timestamp DESC LIMIT ? OFFSET ?;";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ii",  $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function getPostsASC($connection, $limit = 5, $offset = 0)
{
    $sql = "SELECT * FROM posts ORDER BY timestamp ASC LIMIT ? OFFSET ?;";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ii",  $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function getPostsTrending($connection, $limit = 5, $offset = 0)
{
    $sql = "SELECT * FROM posts ORDER BY views DESC LIMIT ? OFFSET ?;";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ii",  $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function getLatestPost($connection)
{
    return getPostsDESC($connection, 1);
}
