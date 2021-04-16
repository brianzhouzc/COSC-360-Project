<?php
require_once "database.php";
$filename = './img/img_avatar.png';
if (isset($_GET['username'])) {
    $sql = "SELECT avatar FROM users WHERE username = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $_GET['username']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    header("Content-type: image/png");
    if (isset($row)) {
        echo $row["avatar"];
    } else {
        $handle = fopen($filename, "rb");
        echo (fread($handle, filesize($filename)));
        fclose($handle);
    }
} else {
    $handle = fopen($filename, "rb");
    echo (fread($handle, filesize($filename)));
    fclose($handle);
}
