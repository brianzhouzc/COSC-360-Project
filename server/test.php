<?php
require_once 'database.php';

$term = 'test';
$term = "%" . $term . "%";
$sql = "SELECT * FROM posts WHERE (content LIKE ?) OR (username LIKE ?) OR (title LIKE ?);";
$stmt = $connection->prepare($sql);
$stmt->bind_param("sss", $term, $term, $term);
$stmt->execute();
$result = $stmt->get_result();
