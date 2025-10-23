<?php
session_start();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Your cart is empty!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Opciones de Pago</title>
    <style>
        body { font-family: Arial, sans-serif; text-align:center; margin-top:50px; background:#fffaf0; }
        h1 { color:#c8102e; }
        form { display:inline-block; background:#f8f9fa; padding:20px; border-radius:10px; border:2px solid #c8102e; }
        button { background:#c8102e; color:white; border:none; padding:10px 20px; border-radius:10px; cursor:pointer; }
        button:hover { background:#a60f24; }
    </style>
</head>
<body>

<h1>Elige método de pago</h1>

<form action="" method="post">
    <h2>Platos en tu pedido:</h2>
    <ul style="text-align:left;">
        <?php 
        foreach ($_SESSION['cart'] as $item) {
            echo "<li>{$item['name']} - S/{$item['price']}</li>";
        }
        
        $total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'];
} ?>
        <p style="font-size:22px; font-weight:bold;">S/ <?= number_format($total, 2) ?></p>
    </ul>

    <h2>Opciones de pago:</h2>
    <label><input type="radio" name="payment" value="cash" required> Efectivo</label><br>
    <label><input type="radio" name="payment" value="card" required> Tarjeta</label><br><br>

    <button type="submit" name="pay">Proceder</button>
</form>

<?php
if (isset($_POST['pay'])) {
    $payment = $_POST['payment'];
    if ($payment == 'cash') {
        header("Location: cash.php");
        exit;
    } else {
        echo "<p>Redirigiendo a la pasarela de pago con tarjeta...</p>";
        // Aquí iría la lógica para el pago con tarjeta
    }
}
?>

</body>
</html>
