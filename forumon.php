<?php
session_start();
include("d.php");

if (!isset($_SESSION['username'])) {
    header("Location: forumab.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $comment = trim($_POST['comment']);
    $username = $_SESSION['username'];
    if (!empty($comment)) {
        $stmt = $conn->prepare("INSERT INTO forum (username, comment) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $comment);
        $stmt->execute();
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forum</title>
</head>
<body>
<h2>Forum</h2>

<form method="POST" action="forumon.php">
    <textarea name="comment" rows="3" cols="40" placeholder="Write a comment..."></textarea><br><br>
    <input type="submit" value="Post Comment">
</form>

<h3>Comments:</h3>
<?php
$sql = "SELECT username, comment, created_at FROM foruma ORDER BY id DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<p><b>" . htmlspecialchars($row['username']) . "</b> (" . $row['created_at'] . "):<br>"
             . nl2br(htmlspecialchars($row['comment'])) . "</p><hr>";
    }
} else {
    echo "<p>No comments yet.</p>";
}
?>
</body>
</html>
