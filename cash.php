<?php
session_start();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Your cart is empty!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cash Payment</title>
</head>
<body>

<h1>Cash Payment</h1>

<p>I WOULD LIKE TO PAY WITH CASH FOR THE FOLLOWING PLATES:</p>

<ul>
    <?php foreach ($_SESSION['cart'] as $item) {
        echo "<li>$item</li>";
    } ?>
</ul>

<p>Please give this list to the cashier.</p>

</body>
</html>
