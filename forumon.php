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
        $stmt = $conn->prepare("INSERT INTO foruma (username, comment) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $comment);
        $stmt->execute();
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    
    <style>
        


nav{
    background: #c8102e !important;
    height: 80px;
    width: 100%;
}

.enlace{
    position: absolute;
    padding: 20px 50px;
}

.logo{
    height: 40px;
}

nav ul{
    float: right;
    margin-right: 20px;
}

nav ul li{
    display: inline-block;
    line-height: 80px;
    margin: 0 5px;
}

nav ul li a{
    color: white;
    font-size: 18px;
    padding: 7px 13px;
    border-radius: 3px;
    text-transform: uppercase;
}

li a.active, li a:hover{
    background: lightsalmon;
    transition: .5s;
}

.checkbtn{
    font-size: 30px;
    color: #c8102e;
    float: right;
    line-height: 80px;
    margin-right: 40px;
    cursor: pointer;
    display: none;
}

#check{
    display: none;
}

 
 

    </style>
            <nav>
    <input type="checkbox" id="check">
    <label for="check" class="checkbtn">
        <i class="fas fa-bars"></i>
    </label>
    <a href="#" class="enlace">
        <img src="logo.png" alt="" class="logo">
    </a>
    <ul>
        <li><a class="active" href="http://localhost/publichtml/index.html">INICIO</a></li>
        <li><a href="http://localhost/publichtml/peru2html.html ">Contacto</a></li>
        <li><a href="http://localhost/publichtml/peru3html.html">promociones</a></li>
        <li><a href="http://localhost/publichtml/forumab.php">Foro perú</a></li>
        <li><a href="http://localhost/publichtml/premium.php">Menú VIP</a></li>
        <li><a href="http://localhost/publichtml/staff.php ">STAFF</a></li>
    </ul>
</nav>

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
