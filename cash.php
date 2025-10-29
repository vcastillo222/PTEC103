<?php
session_start();
include 'd.php'; // für Punkte-Update, falls nötig

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "⚠️ Your cart is empty!";
    exit;
}

// Gesamtpreis und Punkte berechnen
$total = 0;
$totalPoints = 0;
foreach ($_SESSION['cart'] as $item) {
    $quantity = $item['quantity'] ?? 1;
    $total += $item['price'] * $quantity;
    $totalPoints += ($item['points'] ?? 0) * $quantity;
}

// Nutzerstatus prüfen
$isGuest = isset($_SESSION['guest']) && $_SESSION['guest'] === true;
$username = $_SESSION['username'] ?? null;

// Zahlung bestätigen
if (isset($_POST['confirm'])) {
    if (!$isGuest && $username) {
        // Punkte hinzufügen
        $stmt = $conn->prepare("UPDATE users SET points = points + ? WHERE username=?");
        $stmt->bind_param("is", $totalPoints, $username);
        $stmt->execute();
        $stmt->close();
        $message = "✅ Payment successful. You earned $totalPoints points!";
    } else {
        $message = "✅ Payment successful. No points added (guest checkout).";
    }

    // Warenkorb leeren
    $_SESSION['cart'] = [];

    echo "<h1>$message</h1>";
    echo "<p>Thank you for your purchase! You paid <strong>S/ " . number_format($total, 2) . "</strong> in cash.</p>";
    echo '<a href="menu.php"><button>Back to Menu</button></a>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cash Payment</title>
    <style>
        body { font-family: Arial, sans-serif; background:#fff8f0; text-align:center; padding:40px; }
        h1 { color:#c8102e; }
        .box { background:#f1faee; border:2px solid #457b9d; border-radius:10px; padding:20px; display:inline-block; }
        button { background:#e63946; color:white; padding:10px 20px; border:none; border-radius:10px; cursor:pointer; font-weight:bold; transition:background .3s; margin-top:15px; }
        button:hover { background:#d62828; }
    </style>
</head>
<body>

<h1>💵 Pay with Cash</h1>

<div class="box">
    <h2>Your total:</h2>
    <p style="font-size:22px; font-weight:bold;">S/ <?= number_format($total, 2) ?></p>
    <form method="post">
        <button type="submit" name="confirm">Confirm Payment</button>
    </form>
</div>

</body>
</html>
