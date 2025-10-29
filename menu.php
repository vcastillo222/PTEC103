<?php
session_start();
include 'd.php'; // database connection

// Logout handling
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: menu.php?guest=1");
    exit();
}

// Guest access
if (isset($_GET['guest'])) {
    $_SESSION['guest'] = true;
    unset($_SESSION['username']);
}

// Determine user status
$isGuest = isset($_SESSION['guest']) && $_SESSION['guest'] === true;
$username = $_SESSION['username'] ?? null;
$session_id = session_id();
$user_id = null;

// Logged-in user: fetch ID from users table
if (!$isGuest && $username) {
    $stmt = $conn->prepare("SELECT id FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $user_id = $res['id'] ?? null;
    $stmt->close();
}

// Add item to cart
if (isset($_POST['add_to_cart'])) {
    $item_id = intval($_POST['item_id']);
    $stmt = $conn->prepare("SELECT * FROM menu_items WHERE id=?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $item = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($item) {
        // Check if item already in cart
        $check = $conn->prepare("SELECT id, quantity FROM cart WHERE item_id=? AND (user_id=? OR session_id=?)");
        $check->bind_param("iis", $item_id, $user_id, $session_id);
        $check->execute();
        $res = $check->get_result()->fetch_assoc();
        $check->close();

        if ($res) {
            // Update quantity
            $update = $conn->prepare("UPDATE cart SET quantity = quantity + 1, updated_at = NOW() WHERE id=?");
            $update->bind_param("i", $res['id']);
            $update->execute();
            $update->close();
        } else {
            // Insert new cart row, include username if logged in
            $insert = $conn->prepare("
                INSERT INTO cart 
                (user_id, username, session_id, item_id, quantity, price, points, created_at, updated_at) 
                VALUES (?, ?, ?, ?, 1, ?, ?, NOW(), NOW())
            ");
            $insert->bind_param("issidd", $user_id, $username, $session_id, $item_id, $item['price'], $item['points']);
            $insert->execute();
            $insert->close();
        }
    }
}

// Clear cart
if (isset($_POST['clear_cart'])) {
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id=? OR session_id=?");
    $stmt->bind_param("is", $user_id, $session_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch menu items
$menuItems = $conn->query("SELECT * FROM menu_items")->fetch_all(MYSQLI_ASSOC);

// Fetch cart items
$cartQuery = $conn->prepare("
    SELECT c.id, c.quantity, c.price, c.points, m.name 
    FROM cart c 
    JOIN menu_items m ON c.item_id = m.id 
    WHERE c.user_id=? OR c.session_id=?
");
$cartQuery->bind_param("is", $user_id, $session_id);
$cartQuery->execute();
$cartItems = $cartQuery->get_result()->fetch_all(MYSQLI_ASSOC);
$cartQuery->close();

// Calculate totals
$total = 0;
$totalPoints = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
    $totalPoints += $item['points'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Menú Peruano</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<style>
body{font-family:'Roboto',sans-serif;background:#fff8f0;margin:0;text-align:center;}
h1{background:#c8102e;color:white;padding:15px;margin:0;font-size:22px;}
.menu-grid{display:flex;flex-wrap:wrap;justify-content:center;gap:20px;margin:30px;}
.menu-item{width:150px;background:#f1faee;border:2px solid #457b9d;border-radius:10px;padding:10px;transition:transform .2s;cursor:pointer;}
.menu-item:hover{transform:scale(1.05);}
.menu-item img{width:100%;border-radius:8px;}
.menu-item p{margin:8px 0;color:#1d3557;font-weight:bold;}
.cart-box{background:#f1faee;border:2px solid #e63946;border-radius:10px;width:400px;margin:30px auto;padding:15px;}
.cart-box h2{color:#e76f51;}
ul{list-style:none;padding:0;text-align:left;}
li{margin:5px 0;}
button{background:#e63946;color:white;border:none;padding:10px 20px;border-radius:10px;cursor:pointer;font-weight:bold;transition:background .3s;}
button:hover{background:#d62828;}
.total{margin-top:10px;font-weight:bold;}
nav{background:#c8102e;height:80px;width:100%;}
nav ul{margin:0;padding:0;display:flex;justify-content:right;align-items:center;height:80px;list-style:none;}
nav ul li{margin:0 10px;}
nav ul li a{color:white;font-size:18px;text-decoration:none;text-transform:uppercase;padding:7px 13px;border-radius:3px;}
li a.active,li a:hover{background:lightsalmon;transition:.5s;}
.message{background:#fff3cd;color:#856404;padding:10px;border-radius:8px;width:fit-content;margin:10px auto;}
.guest-notice{background:#fff3cd;border:2px solid #f0ad4e;padding:15px;border-radius:8px;width:80%;margin:20px auto;text-align:center;}
.guest-notice a{background:#c8102e;color:white;padding:8px 12px;border-radius:6px;text-decoration:none;}
</style>
</head>
<body>
<header>
<nav>
<ul>
<li><a class="active" href="index.html">Inicio</a></li>
<li><a href="point.php">Puntos</a></li>
<li><a href="premium.php">Menú VIP</a></li>
<li><a href="forumon.php">Foro Perú</a></li>
<li><a href="menu.php">Menú</a></li>
<li><a href="staff.php">Staff</a></li>
<?php if (!$isGuest && $username): ?>
<li><a href="menu.php?logout=1">Cerrar sesión</a></li>
<?php endif; ?>
</ul>
</nav>
</header>

<h1><?= $isGuest ? "Modo Invitado" : "Menú de " . htmlspecialchars($username ?? 'Invitado') ?></h1>

<div class="menu-grid">
<?php foreach ($menuItems as $item): ?>
<div class="menu-item">
<form method="post">
<input type="hidden" name="item_id" value="<?= $item['id'] ?>">
<input type="hidden" name="add_to_cart" value="1">
<img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" onclick="this.parentElement.submit()">
<p><?= htmlspecialchars($item['name']) ?></p>
<p>Puntos: <?= $item['points'] ?></p>
<p>Precio: S/<?= number_format($item['price'],2) ?></p>
</form>
</div>
<?php endforeach; ?>
</div>

<div class="cart-box">
<h2>Carro</h2>
<?php if (!empty($cartItems)): ?>
<ul>
<?php foreach ($cartItems as $item): ?>
<li><?= htmlspecialchars($item['name']) ?> x <?= $item['quantity'] ?> - S/<?= number_format($item['price'] * $item['quantity'],2) ?> (<?= $item['points'] * $item['quantity'] ?> pts)</li>
<?php endforeach; ?>
</ul>
<div class="total">Total puntos: <?= $totalPoints ?></div>
<div class="total">Total: S/<?= number_format($total,2) ?></div>

<form method="post">
<button type="submit" name="clear_cart">Vaciar carro</button>
<a href="buy.php"><button type="button">Pagar</button></a>
</form>
<?php else: ?>
<p>El carro está vacío.</p>
<?php endif; ?>
</div>

<?php if ($isGuest): ?>
<div class="guest-notice">
    <p style="margin:5px 0;font-weight:600;">¿Quieres guardar tus puntos? Inicia sesión en tu cuenta <a href="menulog.php">Login</a></p>
    <p style="margin:5px 0;">Si quieres una cuenta, pídele a un mesero que te añada una cuenta para cuando nos visites otra vez y puedas canjear tus premios!</p>
</div>
<?php endif; ?>

</body>
</html>
