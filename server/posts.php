<?php
require 'database.php';
require_once 'helper.php';
require_once 'users_functions.php';

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    switch ($action) {
        case "create":
            break;

        case "edit":
            break;

        case "remove":
            break;

        case "search":
            break;

        case "get":
            $order = $_POST['order'];
            $limit = $_POST['limit'];
            $offset = $_POST['offset'];

            $results = getPostsDESC($connection);
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