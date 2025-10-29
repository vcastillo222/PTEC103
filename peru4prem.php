<?php
session_start();
include 'd.php'; // tu conexión a la base de datos usando $conn

// Verificar login
if (!isset($_SESSION['username']) || $_SESSION['username'] === '') {
    header("Location: menu.php?guest=1");
    exit();
}

$username = $_SESSION['username'];

// Verificar si el usuario tiene acceso VIP
$stmt = $conn->prepare("SELECT vip_access FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user || !$user['vip_access']) {
    echo "<h2>Acceso Denegado</h2>";
    echo "<p>Solo los usuarios que hayan canjeado el Menú VIP pueden acceder a esta sección.</p>";
    echo "<p><a href='menu.php'>Volver al menú</a></p>";
    exit();
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="peru1.css">
  <link rel="stylesheet" href="peru4.css">
  <title>Menú premium</title>
  <style>
    nav{background:#c8102e;height:80px;width:100%;}
    nav ul{float:right;margin-right:20px;}
    nav ul li{display:inline-block;line-height:80px;margin:0 5px;}
    nav ul li a{color:white;font-size:18px;padding:7px 13px;border-radius:3px;text-transform:uppercase;text-decoration:none;}
    li a.active, li a:hover{background:lightsalmon;transition:.5s;}
    .menu-grid{display:flex;flex-wrap:wrap;justify-content:center;gap:20px;margin:30px;}
    .menu-item{width:250px;background:#f1faee;border-radius:10px;padding:10px;box-shadow:0 4px 8px rgba(0,0,0,0.1);}
    .menu-item img{width:100%;border-radius:8px;}
    .menu-item h2{margin:10px 0 5px 0;}
    .menu-item p{margin:5px 0;}
    .menu-item .price{font-weight:bold;margin:5px 0;}
    .menu-item .tag{font-size:12px;color:#555;}
  </style>
</head>
<body>
<header>
<nav>
    <input type="checkbox" id="check">
    <label for="check" class="checkbtn">
        <i class="fas fa-bars"></i>
    </label>
    <a href="#" class="enlace">
        <img src="logo.png" alt="" class="logo">
    </a>
    <ul>
        <li><a class="active" href="http://localhost/publicperu/index.html">INICIO</a></li>
        <li><a href="http://localhost/publicperu/peru2html.html">Puntos</a></li>
        <li><a href="http://localhost/publicperu/premium.php">Menú VIP</a></li>
        <li><a href="http://localhost/publicperu/forumon.php">Foro perú</a></li>
        <li><a href="http://localhost/publicperu/peru3html.html">menu</a></li>
        <li><a href="http://localhost/publicperu/staff.php">STAFF</a></li>
    </ul>
</nav>
</header>

<main>
<h2>Welcome to the Premium Menu, <?= htmlspecialchars($username); ?>!</h2>
<div class="menu-grid">
  <div class="menu-item">
    <img src="https://source.unsplash.com/400x300/?salmon,dish" alt="Grilled Salmon">
    <h2>ASDF ASDF</h2>
    <p>ASFD ASFD, ASDF</p>
    <div class="price">$18</div>
    <div class="tag">ASFD AFD</div>
  </div>
  <div class="menu-item">
    <img src="https://source.unsplash.com/400x300/?risotto,dish" alt="Mushroom Risotto">
    <h2></h2>
    <p>ASDF ASDF, SDF ASDF</p>
    <div class="price">$15</div>
    <div class="tag">ASDF</div>
  </div>
  <div class="menu-item">
    <img src="https://source.unsplash.com/400x300/?octopus,dish" alt="Charred Octopus">
    <h2>ASDF ADSF</h2>
    <p>ASDF ASDF, ASDF ASDF</p>
    <div class="price">$20</div>
    <div class="tag">ASDF’s Pick</div>
  </div>
</div>
</main>
</body>
</html>
