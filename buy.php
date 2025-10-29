<?php
session_start();
include 'd.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get payment method safely
$method = $_POST['method'] ?? null;

// Determine user
$isGuest = empty($_SESSION['username']);
$username = $_SESSION['username'] ?? null;
$session_id = session_id();
$user_id = null;

// Logged-in user: fetch user_id
if (!$isGuest && $username) {
    $stmt = $conn->prepare("SELECT id FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $user_id = $res['id'] ?? null;
    $stmt->close();
    $buyerName = $username;
} else {
    $buyerName = "Guest-" . substr($session_id, 0, 6);
}

// Fetch cart items
if (!$isGuest) {
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id=? AND payment_method IS NULL");
    $stmt->bind_param("i", $user_id);
} else {
    $stmt = $conn->prepare("SELECT * FROM cart WHERE session_id=? AND payment_method IS NULL");
    $stmt->bind_param("s", $session_id);
}
$stmt->execute();
$cart = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Empty cart check
if (empty($cart)) {
    echo "⚠️ Your cart is empty!";
    exit;
}

// Calculate totals
$total = 0;
$totalPoints = 0;
foreach ($cart as $item) {
    $qty = $item['quantity'] ?? 1;
    $total += $item['price'] * $qty;
    $totalPoints += ($item['points'] ?? 0) * $qty;
}

$now = date('Y-m-d H:i:s');

if ($method === 'cash') {
    foreach ($cart as $item) {
        $itemPoints = $item['points'] ?? 0;

        // 1️⃣ Update cart with username and payment_method
        $stmt = $conn->prepare("
            UPDATE cart SET user_id=?, username=?, payment_method=?, updated_at=? WHERE id=?
        ");
        $stmt->bind_param("isssi", $user_id, $buyerName, $method, $now, $item['id']);
        $stmt->execute();
        $stmt->close();

        // 2️⃣ Insert into orders
        $stmt = $conn->prepare("
            INSERT INTO orders
            (user_id, session_id, username, item_id, quantity, price, points, payment_method, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "issiiidss",
            $user_id,
            $session_id,
            $buyerName,
            $item['item_id'],
            $item['quantity'],
            $item['price'],
            $itemPoints,
            $method,
            $now
        );
        $stmt->execute();
        $stmt->close();
    }

    // 3️⃣ Delete paid items from cart
    if (!$isGuest) {
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id=? AND payment_method='cash'");
        $stmt->bind_param("i", $user_id);
    } else {
        $stmt = $conn->prepare("DELETE FROM cart WHERE session_id=? AND payment_method='cash'");
        $stmt->bind_param("s", $session_id);
    }
    $stmt->execute();
    $stmt->close();

    // 4️⃣ Show confirmation
    echo "<h1>💵 Payment Pending Confirmation</h1>";
    echo "<p><strong>Buyer:</strong> " . htmlspecialchars($buyerName) . "</p>";
    echo "<p>Payment will be confirmed by the staff.</p>";
    echo "<p><strong>Total Paid:</strong> S/ " . number_format($total, 2) . "</p>";
    echo "<p><strong>Payment Method:</strong> Cash</p>";
    echo '<a href="menu.php"><button>Back to Menu</button></a>';
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Payment Options</title>
<style>
body { font-family: Arial, sans-serif; background:#fffaf0; text-align:center; padding:40px; }
h1 { color:#c8102e; }
ul { text-align:left; display:inline-block; }
button { background:#e63946; color:white; padding:10px 20px; border:none; border-radius:10px; cursor:pointer; font-weight:bold; margin:5px; transition: background .3s; }
button:hover { background:#d62828; }
.modal { display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); background:#f1faee; padding:30px; border:2px solid #457b9d; border-radius:10px; z-index:1000; }
.modal button { width:120px; margin-top:10px; }
.modal-overlay { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:900; }
</style>
</head>
<body>

<h1>💳 Confirm Payment</h1>

<h2>Cart Summary</h2>
<ul>
<?php foreach ($cart as $item): ?>
<li>Item ID <?= htmlspecialchars($item['item_id']) ?> x <?= $item['quantity'] ?? 1 ?> - S/<?= number_format(($item['price'] * ($item['quantity'] ?? 1)),2) ?> (<?= ($item['points'] ?? 0) * ($item['quantity'] ?? 1) ?> pts)</li>
<?php endforeach; ?>
</ul>
<p style="font-weight:bold;">Total: S/ <?= number_format($total,2) ?> | Points: <?= $totalPoints ?></p>

<!-- Pay button opens modal -->
<button id="payBtn">Pay</button>

<!-- Modal -->
<div class="modal-overlay" id="overlay"></div>
<div class="modal" id="paymentModal">
    <h2>Select Payment Method</h2>
    <form method="post">
        <button type="submit" name="confirm_payment" value="cash" onclick="document.getElementById('method').value='cash'">Cash</button>
        <button type="submit" name="confirm_payment" value="card" onclick="document.getElementById('method').value='card'">Card</button>
        <input type="hidden" name="method" id="method" value="">
    </form>
    <br>
    <button onclick="closeModal()">Cancel</button>
</div>

<script>
const payBtn = document.getElementById('payBtn');
const modal = document.getElementById('paymentModal');
const overlay = document.getElementById('overlay');

payBtn.onclick = () => {
    modal.style.display = 'block';
    overlay.style.display = 'block';
};

function closeModal() {
    modal.style.display = 'none';
    overlay.style.display = 'none';
}
</script>

</body>
</html>
