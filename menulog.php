<?php
session_start();
include 'd.php'; // Database connection

// Logout handling
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: menu.php?guest=1");
    exit();
}

// Initialize message
$message = '';



// Login handling
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($user && $user['password'] === $password) { // Plain password, or use password_verify if hashed
        $_SESSION['username'] = $username;
        unset($_SESSION['guest']);
        header("Location: menu.php");
        exit();
    } else {
        $message = "❌ Usuario o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login - Menú Peruano</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<style>
body{font-family:'Roboto',sans-serif;background:#fff8f0;margin:0;text-align:center;}
h1{background:#c8102e;color:white;padding:15px;margin:0;font-size:22px;}
form{background:#f1faee;border:2px solid #457b9d;border-radius:10px;width:300px;margin:50px auto;padding:20px;text-align:center;}
input[type=text],input[type=password]{width:90%;padding:8px;margin:8px 0;border-radius:6px;border:1px solid #ccc;}
button{background:#e63946;color:white;border:none;padding:10px 20px;border-radius:10px;cursor:pointer;font-weight:bold;margin-top:10px;}
button:hover{background:#d62828;}
.message{background:#fff3cd;color:#856404;padding:10px;border-radius:8px;width:fit-content;margin:10px auto;}
.logout-link{display:block;margin-top:20px;text-decoration:none;color:#c8102e;font-weight:bold;}
</style>
</head>
<body>

<h1>Iniciar sesión</h1>

<?php if ($message): ?>
<div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<form method="post">
<input type="text" name="username" placeholder="Usuario" required><br>
<input type="password" name="password" placeholder="Contraseña" required><br>
<button type="submit">Login</button>

<p><a href="menu.php?guest=1">Entrar como invitado</a></p>

</form>

<a href="menu.php?guest=1" class="logout-link">Entrar como invitado</a>

</body>
</html>
