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
    <title>Payment Options</title>
</head>
<body>

<h1>Choose Payment Method</h1>

<form action="" method="post">
    <h2>Plates in your order:</h2>
    <ul>
        <?php foreach ($_SESSION['cart'] as $item) {
            echo "<li>$item</li>";
        } ?>
    </ul>

    <h2>Payment Options:</h2>
    <input type="radio" name="payment" value="cash" required> Cash<br>
    <input type="radio" name="payment" value="card" required> Card<br><br>

    <button type="submit" name="pay">Proceed</button>
</form>

<?php
if (isset($_POST['pay'])) {
    $payment = $_POST['payment'];
    if ($payment == 'cash') {
        header("Location: cash.php");
        exit;
    } else {
        echo "<p>Redirecting to card payment gateway...</p>";
        // Here you would integrate card payment logic
    }
}
?>

</body>
</html>
