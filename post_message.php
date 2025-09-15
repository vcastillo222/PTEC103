<?php
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data["content"])) {
    $stmt = $conn->prepare("INSERT INTO messages (content) VALUES (?)");
    $stmt->bind_param("s", $data["content"]);
    $stmt->execute();
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => "Empty message"]);
}

