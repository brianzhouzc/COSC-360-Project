<?php
require_once "database.php";
if (isset($_GET['username'])) {
    $sql = "SELECT avatar,avatar_type FROM users WHERE username = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $_GET['username']);
    $stmt->execute();
    $result = $stmt->get_result();

    $row = $result->fetch_assoc();
    header("Content-type: " . $row["avatar_type"]);
    echo $row["avatar"];
}
