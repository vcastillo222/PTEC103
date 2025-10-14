<?php
session_start();
include("d.php"); // your database connection file

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: forumab.php"); // redirect if not logged in
    exit();
}

$username = $_SESSION['user'];

// Get user points from database
$sql = "SELECT points FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $points = $row['points'];
} else {
    $points = 0;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Puntos de Recompensa</title>
<style>
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f8f8f8;
    margin: 0;
    padding: 0;
}

header {
    background-color: #d52b1e; /* Peru red */
    color: white;
    padding: 15px;
    text-align: center;
    font-size: 24px;
    font-weight: bold;
}

.container {
    max-width: 800px;
    margin: 50px auto;
    background: white;
    border-radius: 10px;
    padding: 40px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    text-align: center;
}

.points {
    font-size: 80px;
    color: #d52b1e;
    font-weight: bold;
    margin: 20px 0;
}

.info {
    color: #555;
    font-size: 18px;
}

.redeem-section {
    margin-top: 40px;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}

.redeem-box {
    background-color: #fff3e0;
    border: 2px solid #f7b731;
    border-radius: 10px;
    width: 200px;
    padding: 20px;
    text-align: center;
    transition: 0.3s;
}

.redeem-box:hover {
    background-color: #f7b731;
    color: white;
    cursor: pointer;
}

.separator {
    margin: 60px 0 30px 0;
    border-top: 3px solid #d52b1e;
}

footer {
    text-align: center;
    color: #888;
    padding: 20px;
    font-size: 14px;
}
</style>
</head>
<body>

<!-- keep your existing NAV BAR above this -->

<header>Mis Puntos de Recompensa</header>

<div class="container">
    <p>Hola, <strong><?php echo htmlspecialchars($username); ?></strong></p>
    <div class="points"><?php echo $points; ?></div>
    <p class="info">Puntos acumulados disponibles</p>

    <div class="separator"></div>

    <h3>Canjea tus puntos</h3>
    <div class="redeem-section">
        <div class="redeem-box">☕ Café gratis<br><small>500 puntos</small></div>
        <div class="redeem-box">🍔 Combo especial<br><small>1200 puntos</small></div>
        <div class="redeem-box">🎁 Producto sorpresa<br><small>800 puntos</small></div>
        <div class="redeem-box">🧃 Bebida premium<br><small>600 puntos</small></div>
        <div class="redeem-box">🎫 Descuento 10%<br><small>300 puntos</small></div>
    </div>

    <div class="separator"></div>

    <p>¡Sigue comprando y acumulando más puntos!</p>
</div>

<footer>
    © 2025 Perú Rewards Program
</footer>

</body>
</html>
