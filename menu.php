<?php
session_start();
include 'd.php'; // database connection

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle adding item to cart
if (isset($_POST['add_to_cart'])) {
    $item_id = $_POST['item_id'];

    // Fetch item info from database
    $stmt = $pdo->prepare("SELECT * FROM menu_items WHERE id = ?");
    $stmt->execute([$item_id]);
    $item = $stmt->fetch();

    if ($item) {
        // Add item to session cart (store id, name, price)
        $_SESSION['cart'][] = [
            'id' => $item['id'],
            'name' => $item['name'],
            'price' => $item['price']
        ];
    }
}

// Handle clearing cart
if (isset($_POST['clear_cart'])) {
    $_SESSION['cart'] = [];
}

// Fetch menu items from database
$stmt = $pdo->query("SELECT * FROM menu_items");
$menuItems = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Peruvian Menu</title>
    <link href="https://fonts.googleapis.com/css2?family=Righteous&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; background: #FFF8F0; margin:0; color:#333;}
        h1 {text-align:center; font-family:'Righteous', cursive; color:#E63946; padding:20px 0;}
        .menu-grid {display:flex; flex-wrap:wrap; justify-content:center; gap:15px; padding:0 20px;}
        .menu-item {flex:1 0 21%; background:#F1FAEE; border:3px solid #457B9D; border-radius:15px; padding:10px; text-align:center; transition: transform 0.2s, box-shadow 0.2s;}
        .menu-item img {width:100%; border-radius:10px; cursor:pointer; transition: transform 0.2s;}
        .menu-item img:hover {transform: scale(1.05);}
        .menu-item:hover {transform: translateY(-5px); box-shadow:0 5px 15px rgba(0,0,0,0.2);}
        .menu-item p {font-weight:bold; margin-top:10px; color:#1D3557;}
        .cart {margin:30px auto; padding:15px; max-width:600px; background:#F1FAEE; border:3px solid #E63946; border-radius:15px;}
        .cart h2 {font-family:'Righteous', cursive; color:#E76F51; margin-bottom:10px;}
        .cart ul {list-style-type:square; padding-left:20px;}
        .cart button, .cart a button {margin-top:10px; padding:10px 20px; background:#E63946; color:white; border:none; border-radius:10px; font-weight:bold; cursor:pointer; transition: background 0.2s;}
        .cart button:hover {background:#D62828;}
        .cart a button {text-decoration:none;}
        footer {text-align:center; padding:15px; background:#457B9D; color:#FFF8F0; margin-top:40px;}


  

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
</head>
<body>
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
        <li><a href="http://localhost/publicperu/peru2html.html "> Puntos </a></li>
        <li><a href="http://localhost/publicperu/premium.php">Menú VIP</a></li>
        <li><a href="http://localhost/publicperu/forumon.php">Foro perú</a></li>
        <li><a href="http://localhost/publicperu/menu.php">menu</a></li>
        <li><a href="http://localhost/publicperu/staff.php">STAFF</a></li>

    </ul>
</nav>

<h1>Peruvian Menu</h1>

<div class="menu-grid">
    <?php foreach ($menuItems as $item): ?>
        <div class="menu-item">
            <form method="post">
                <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                <img src="<?= $item['image'] ?>" alt="<?= $item['name'] ?>" onclick="this.parentElement.submit()">
                <p><?= $item['name'] ?> - S/<?= number_format($item['price'], 2) ?></p>
                <input type="hidden" name="add_to_cart" value="1">
            </form>
        </div>
    <?php endforeach; ?>
</div>

<div class="cart">
    <h2>Your Cart</h2>
    <?php if (!empty($_SESSION['cart'])): ?>
        <ul>
            <?php foreach ($_SESSION['cart'] as $cartItem): ?>
                <li><?= $cartItem['name'] ?> - S/<?= number_format($cartItem['price'], 2) ?></li>
            <?php endforeach; ?>
        </ul>
        <form method="post">
            <button type="submit" name="clear_cart">Clear Cart</button>
        </form>
        <a href="page.php"><button>Buy</button></a>
    <?php else: ?>
        <p>Cart is empty.</p>
    <?php endif; ?>
</div>

<footer>Enjoy the flavors of Peru!</footer>

</body>
</html>
