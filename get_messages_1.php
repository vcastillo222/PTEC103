<?php
include "db.php";

$result = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
$messages = [];

while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

header("Content-Type: application/json");
echo json_encode($messages);

