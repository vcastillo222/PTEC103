<?php
session_start();
include("d.php");

if (!isset($_SESSION['staff'])) {
    echo "Access denied. Staff only.";
    exit();
}

// Handle all POST actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';

    switch ($action) {

        case "create_user":
            $new_user = $_POST['new_user'];
            $new_pass = $_POST['new_password'];
            $sql = "INSERT INTO users (username, password) VALUES ('$new_user', '$new_pass')";
            $conn->query($sql) ? print("<script>alert('✅ User created successfully!');</script>")
                                : print("<script>alert('❌ Error: " . addslashes($conn->error) . "');</script>");
            break;

        case "edit_user":
            $username = $_POST['username'];
            $new_pass = $_POST['new_password'];
            $sql = "UPDATE users SET password='$new_pass' WHERE username='$username'";
            $conn->query($sql) && $conn->affected_rows > 0
                ? print("<script>alert('✅ Password updated for $username');</script>")
                : print("<script>alert('❌ User not found or error updating.');</script>");
            break;

        case "add_points":
            $username = $_POST['username'];
            $points = (int)$_POST['points'];
            $sql = "UPDATE users SET points = points + $points WHERE username='$username'";
            $conn->query($sql) && $conn->affected_rows > 0
                ? print("<script>alert('✅ Added $points points to $username');</script>")
                : print("<script>alert('❌ User not found or error adding points.');</script>");
            break;

        case "create_menu":
            $name = $_POST['menu_name'];
            $link = $_POST['menu_link'];
            $sql = "INSERT INTO menu_items (name, description) VALUES ('$name', '$link')";
            $conn->query($sql) ? print("<script>alert('✅ Menu item created successfully!');</script>")
                                : print("<script>alert('❌ Error: " . addslashes($conn->error) . "');</script>");
            break;

        case "add_staff":
            $staff_user = $_POST['staff_user'];
            $staff_pass = $_POST['staff_password'];
            $sql = "INSERT INTO staff (staff_user, staff_pass) VALUES ('$staff_user', '$staff_pass')";
            $conn->query($sql) ? print("<script>alert('✅ Staff member added!');</script>")
                                : print("<script>alert('❌ Error: " . addslashes($conn->error) . "');</script>");
            break;

        case "confirmbuy":
        case "cancelbuy":
            $order_id = intval($_POST['order_id']);
            $actionType = $_POST['action']; // confirmbuy or cancelbuy

            $stmt = $conn->prepare("SELECT * FROM orders WHERE id=? AND state='new'");
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            $order = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if ($order) {
                if ($actionType === 'confirmbuy' && $order['user_id']) {
                    $pointsToAdd = intval($_POST['points_to_add']);
                    $stmt = $conn->prepare("UPDATE users SET points = points + ? WHERE id=?");
                    $stmt->bind_param("ii", $pointsToAdd, $order['user_id']);
                    $stmt->execute();
                    $stmt->close();

                    $stmt = $conn->prepare("UPDATE orders SET points=?, state='approved' WHERE id=?");
                    $stmt->bind_param("ii", $pointsToAdd, $order_id);
                    $stmt->execute();
                    $stmt->close();

                    echo "<script>alert('✅ Order approved and points added.');</script>";
                } elseif ($actionType === 'cancelbuy') {
                    $stmt = $conn->prepare("UPDATE orders SET state='cancelled' WHERE id=?");
                    $stmt->bind_param("i", $order_id);
                    $stmt->execute();
                    $stmt->close();

                    echo "<script>alert('❌ Order cancelled successfully.');</script>";
                }
            } else {
                echo "<script>alert('⚠️ Order not found or already processed.');</script>";
            }
            break;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Staff Control Panel</title>
<style>
nav{
    background: #c8102e !important;
    height: 80px;
    width: 100%;
}
.enlace{position:absolute;padding:20px 50px;}
.logo{height:40px;}
nav ul{float:right;margin-right:20px;}
nav ul li{display:inline-block;line-height:80px;margin:0 5px;}
nav ul li a{color:white;font-size:18px;padding:7px 13px;border-radius:3px;text-transform:uppercase;}
li a.active,li a:hover{background:lightsalmon;transition:.5s;}
:root {
  --redFire: rgba(244,91,105,1);
  --blueQueen: rgba(69,105,144,1);
  --fontAsap: 'Asap', sans-serif;
}
body {
  background-color: var(--redFire);
  font-family: var(--fontAsap);
}
.options-box {
  background-color: white;
  padding: 25px;
  border-radius: 10px;
  width: 400px;
  margin: 120px auto 30px auto;
  box-shadow: 5px 10px 10px rgba(2,128,144,0.2);
}
.options-box h3 { margin-bottom: 15px; color: var(--blueQueen); text-align: center; }
.option-item { display:block; margin:8px 0; font-size:16px; }
.dynamic-box {
  background-color: white;
  padding: 20px;
  border-radius: 10px;
  width: 90%;
  max-width: 1000px;
  margin: 0 auto;
  box-shadow: 5px 10px 10px rgba(2,128,144,0.2);
  overflow-x:auto;
}
.dynamic-box input, .dynamic-box button {
  display:block;
  margin-top:10px;
  padding:5px 10px;
  font-size:14px;
  border-radius:5px;
  border:1px solid #ccc;
}
.dynamic-box button {
  background-color: var(--redFire);
  color:white;
  border:none;
  cursor:pointer;
}
.dynamic-box button:hover { background-color: rgb(221,77,89); }
</style>
</head>
<body>
<nav>
<a href="#" class="enlace"><img src="logo.png" alt="" class="logo"></a>
<ul>
<li><a class="active" href="index.html">INICIO</a></li>
<li><a href="peru2.php">Puntos</a></li>
<li><a href="premium.php">Menú VIP</a></li>
<li><a href="forumon.php">Foro Perú</a></li>
<li><a href="menu.php">Menú</a></li>
<li><a href="staff.php">STAFF</a></li>
</ul>
</nav>

<div class="options-box">
<h3>Options</h3>
<p>Choose an option:</p>
<label class="option-item"><input type="radio" name="action" value="create_user"> Create a new user</label>
<label class="option-item"><input type="radio" name="action" value="edit_user"> Edit an existing user</label>
<label class="option-item"><input type="radio" name="action" value="create_menu"> Create a new menu item</label>
<label class="option-item"><input type="radio" name="action" value="add_points"> Add points to an existing user</label>
<label class="option-item"><input type="radio" name="action" value="add_staff"> Add staff member</label>
<label class="option-item"><input type="radio" name="action" value="confirmbuy"> Confirm/Cancel Orders</label>
</div>

<div id="dynamicBox" class="dynamic-box">
<p style="text-align:center; color:gray;">Please select an option above.</p>
</div>

<script>
const box = document.getElementById("dynamicBox");
const radios = document.querySelectorAll('input[name="action"]');

radios.forEach(radio => {
    radio.addEventListener("change", function() {
        let html = "";
        switch(this.value) {
            case "create_user":
                html = `<h3>Create New User</h3>
<form method="POST"><input type="hidden" name="action" value="create_user">
<label>Username:</label><input type="text" name="new_user" required>
<label>Password:</label><input type="password" name="new_password" required>
<button type="submit">Create User</button></form>`;
                break;
            case "edit_user":
                html = `<h3>Edit Existing User</h3>
<form method="POST"><input type="hidden" name="action" value="edit_user">
<label>Username:</label><input type="text" name="username" required>
<label>New Password:</label><input type="password" name="new_password" required>
<button type="submit">Update Password</button></form>`;
                break;
            case "create_menu":
                html = `<h3>Create Menu Item</h3>
<form method="POST"><input type="hidden" name="action" value="create_menu">
<label>Menu Name:</label><input type="text" name="menu_name" required>
<label>Menu Link (URL):</label><input type="text" name="menu_link" required>
<button type="submit">Create Menu</button></form>`;
                break;
            case "add_points":
                html = `<h3>Add Points to User</h3>
<form method="POST"><input type="hidden" name="action" value="add_points">
<label>Username:</label><input type="text" name="username" required>
<label>Points to Add:</label><input type="number" name="points" required>
<button type="submit">Add Points</button></form>`;
                break;
            case "add_staff":
                html = `<h3>Add Staff Member</h3>
<form method="POST"><input type="hidden" name="action" value="add_staff">
<label>Username:</label><input type="text" name="staff_user" required>
<label>Password:</label><input type="password" name="staff_password" required>
<button type="submit">Add Staff</button></form>`;
                break;
            case "confirmbuy":
                html = `<?php
$orders = $conn->query("SELECT o.id, o.user_id, o.item_id, o.quantity, o.price, o.points, o.state,
                        u.username, m.name AS item_name, m.points AS item_points
                        FROM orders o
                        LEFT JOIN users u ON o.user_id=u.id
                        LEFT JOIN menu_items m ON o.item_id=m.id
                        WHERE o.state='new'
                        ORDER BY o.id ASC")->fetch_all(MYSQLI_ASSOC);
if($orders):
?>
<h3>Pending Orders (State: new)</h3><div style="overflow:auto;"><table style="width:100%; border-collapse: collapse; text-align:center;">
<tr style="background:#c8102e;color:white;">
<th>ID</th><th>User</th><th>Item</th><th>Qty</th><th>Price</th><th>Points</th><th>Total Points</th><th>Total Price</th><th>State</th><th>Actions</th>
</tr>
<?php foreach($orders as $order):
$totalPrice = $order['price']*$order['quantity'];
$totalPoints = ($order['item_points']??0)*$order['quantity'];
$displayUser = $order['username']??"Guest (Order #{$order['id']})"; ?>
<tr>
<td><?= $order['id'] ?></td>
<td><?= htmlspecialchars($displayUser) ?></td>
<td><?= htmlspecialchars($order['item_name']??'Unknown') ?></td>
<td><?= $order['quantity'] ?></td>
<td>S/ <?= number_format($order['price'],2) ?></td>
<td><?= $order['item_points'] ?></td>
<td><?= $totalPoints ?></td>
<td>S/ <?= number_format($totalPrice,2) ?></td>
<td><?= $order['state'] ?></td>
<td>
<form method="POST" style="display:inline-block;margin:0;">
<input type="hidden" name="action" value="confirmbuy">
<input type="hidden" name="order_id" value="<?= $order['id'] ?>">
<input type="number" name="points_to_add" value="<?= $totalPoints ?>" required style="width:60px;">
<button type="submit" style="background:#28a745;">Approve</button>
</form>
<form method="POST" style="display:inline-block;margin:0;">
<input type="hidden" name="action" value="cancelbuy">
<input type="hidden" name="order_id" value="<?= $order['id'] ?>">
<button type="submit" style="background:#dc3545;">Cancel</button>
</form>
</td>
</tr>
<?php endforeach; ?>
</table></div>
<?php endif; ?>`;
                break;
            default:
                html = `<p style="text-align:center;color:gray;">Please select an option above.</p>`;
        }
        box.innerHTML = html;
    });
});
</script>
</body>
</html>
