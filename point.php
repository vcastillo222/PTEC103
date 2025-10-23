<?php
session_start();
include("d.php");

// Redirect if not logged in
if (!isset($_SESSION['username']) || $_SESSION['username'] === '') {
    header("Location: forumab.php");
    exit();
}

$username = $_SESSION['username'];

// Handle redeem action (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['redeem'])) {
    $cost = intval($_POST['cost']);

    $stmt = $conn->prepare("SELECT points FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $points = 0;
    if ($result && $row = $result->fetch_assoc()) $points = (int)$row['points'];
    $stmt->close();

    if ($points >= $cost) {
        $new_points = $points - $cost;
        $update = $conn->prepare("UPDATE users SET points = ? WHERE username = ?");
        $update->bind_param("is", $new_points, $username);
        $update->execute();
        $update->close();

        echo json_encode(['status'=>'success','message'=>'Canje exitoso','new_points'=>$new_points]);
    } else {
        echo json_encode(['status'=>'error','message'=>'No tienes suficientes puntos para este canje.']);
    }
    exit;
}

// Get user points normally
$stmt = $conn->prepare("SELECT points FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user_points = 0;
if ($result && $row = $result->fetch_assoc()) $user_points = (int)$row['points'];
$stmt->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Recompensas Perú</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{
  --peru-red:#d52b1e;--peru-white:#fff;--accent-gold:#f2c94c;
  --muted:#6b6b6b;--card-bg:#fff8ef;--shadow:0 6px 18px rgba(0,0,0,0.08);
}
body{margin:0;font-family:"Poppins",sans-serif;background:linear-gradient(180deg,#fff 0%,#fff8f6 100%);color:#222;}
nav{background:#fff;box-shadow:var(--shadow);}
nav ul{margin:0;padding:0;list-style:none;display:flex;flex-wrap:wrap;justify-content:right;}
nav ul li a{display:block;padding:14px 20px;color:#222;text-decoration:none;font-weight:600;}
nav ul li a:hover,nav ul li a.active{background:var(--peru-red);color:#fff;}
.page{max-width:1100px;margin:28px auto;padding:0 18px;}
.hero{background:linear-gradient(90deg,var(--peru-red),#b71b16);color:#fff;border-radius:12px;padding:18px;display:flex;align-items:center;justify-content:space-between;box-shadow:var(--shadow);}
.brand{display:flex;align-items:center;gap:12px;}
.brand h1{margin:0;font-size:20px;}
.brand p{margin:0;font-size:13px;opacity:.95;}
.flag{width:46px;height:30px;border-radius:4px;border:2px solid rgba(255,255,255,0.15);
background:repeating-linear-gradient(90deg,var(--peru-red)0 33%,var(--peru-white)33% 66%,var(--peru-red)66% 100%);
box-shadow:0 3px 8px rgba(0,0,0,0.12) inset;}
.login-area{display:flex;align-items:center;gap:12px;}
.points-badge{background:rgba(255,255,255,0.12);padding:8px 12px;border-radius:999px;font-weight:600;display:flex;gap:8px;align-items:center;font-size:14px;}
.login-btn{background:#fff;color:var(--peru-red);border:0;padding:10px 14px;border-radius:8px;cursor:pointer;font-weight:700;box-shadow:0 6px 14px rgba(213,43,30,0.14);}
.three-col{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-top:20px;}
.img-card{background:var(--card-bg);border-radius:12px;padding:10px;text-align:center;box-shadow:var(--shadow);min-height:140px;display:flex;flex-direction:column;justify-content:center;align-items:center;}
.img-card img{max-width:100%;height:110px;object-fit:cover;border-radius:8px;box-shadow:0 6px 12px rgba(0,0,0,0.07);}
.img-card small{margin-top:8px;color:var(--muted);}
.separator{margin:30px 0;height:1px;background:linear-gradient(90deg,transparent,rgba(0,0,0,0.06),transparent);position:relative;}
.separator::after{content:"⊹  Programa de Recompensas  ⊹";position:absolute;left:50%;top:-11px;transform:translateX(-50%);background:#fff;padding:0 12px;font-size:13px;color:#666;}
.lead-text{background:linear-gradient(180deg,rgba(240,240,240,0.8),rgba(255,255,255,0.85));padding:18px;border-radius:10px;box-shadow:0 4px 10px rgba(0,0,0,0.04);line-height:1.5;margin-bottom:20px;}
.rewards-grid{display:grid;grid-template-columns:repeat(6,1fr);gap:12px;margin-top:14px;}
.reward-card{background:#fff;border-radius:8px;padding:12px;text-align:center;box-shadow:var(--shadow);display:flex;flex-direction:column;justify-content:space-between;}
.reward-card h3{margin:0;font-size:14px;}
.points{font-weight:800;font-size:18px;color:var(--peru-red);}
.reward-card button{border:0;background:linear-gradient(180deg,#e60000,#b30000);color:#fff;padding:7px 10px;border-radius:6px;cursor:pointer;font-weight:700;transition:opacity .12s ease;}
.reward-card button:hover{opacity:.9;}
.anden-motif{margin-top:28px;height:36px;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#8a6b3a;font-weight:700;letter-spacing:2px;background:repeating-linear-gradient(90deg,#f6e9d6 0 8px,#f2e3c9 8px 16px);}
@media(max-width:1000px){.rewards-grid{grid-template-columns:repeat(3,1fr)}.three-col{grid-template-columns:repeat(2,1fr)}}
@media(max-width:600px){.rewards-grid{grid-template-columns:repeat(2,1fr)}.three-col{grid-template-columns:1fr}.hero{flex-direction:column;gap:12px;align-items:flex-start}}
</style>
</head>
<body>
<header>
  <nav>
    <ul>
      <li><a class="active" href="index.html">INICIO</a></li>
      <li><a href="point.php">Puntos</a></li>
      <li><a href="premium.php">Menú VIP</a></li>
      <li><a href="forumon.php">Foro Perú</a></li>
      <li><a href="menu.php">Menú</a></li>
      <li><a href="staff.php">STAFF</a></li>
      <li><a href="logout.php">Cerrar sesión</a></li>
    </ul>
  </nav>
</header>

<div class="page">
  <header class="hero">
    <div class="brand">
      <div class="flag"></div>
      <div>
        <h1>Recompensas Perú</h1>
        <p>¡Bienvenido, <?php echo htmlspecialchars($username); ?>!</p>
      </div>
    </div>
    <div class="login-area">
      <div class="points-badge">Puntos: <strong id="points-display"><?php echo $user_points; ?></strong></div>
    </div>
  </header>

  <section class="three-col">
    <div class="img-card">
      <img src="https://via.placeholder.com/600x400/ffdede/000000?text=Producto+1" alt="Producto 1">
      <small>Promo: 10% de descuento con 200 puntos</small>
    </div>
    <div class="img-card">
      <img src="https://via.placeholder.com/600x400/fff7ea/000000?text=Producto+2" alt="Producto 2">
      <small>Canjea: Empanada gratis (350 pts)</small>
    </div>
    <div class="img-card">
      <img src="https://via.placeholder.com/600x400/fff1f0/000000?text=Producto+3" alt="Producto 3">
      <small>Acumula el doble los fines de semana</small>
    </div>
  </section>

  <div class="separator"></div>

  <section class="lead-text">
    <p><strong>¿Cómo funciona?</strong> Acumula puntos por cada compra. Cada 1 Sol = 1 punto. Suma puntos y canjéalos por descuentos, productos o acceso a promociones VIP.</p>
  </section>

  <section>
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
      <h2 style="margin:0">Recompensas populares</h2>
      <small class="muted">Canjea con tus puntos</small>
    </div>
    <div class="rewards-grid">
      <div class="reward-card"><h3>Café peruano - Americano</h3><div class="muted">120 pts</div><button class="redeem" data-cost="120">Canjear</button></div>
      <div class="reward-card"><h3>Alfajor artesano</h3><div class="muted">80 pts</div><button class="redeem" data-cost="80">Canjear</button></div>
      <div class="reward-card"><h3>Descuento 10%</h3><div class="muted">200 pts</div><button class="redeem" data-cost="200">Canjear</button></div>
      <div class="reward-card"><h3>Empanada</h3><div class="muted">350 pts</div><button class="redeem" data-cost="350">Canjear</button></div>
      <div class="reward-card"><h3>Combo desayuno</h3><div class="muted">450 pts</div><button class="redeem" data-cost="450">Canjear</button></div>
      <div class="reward-card"><h3>Acceso VIP</h3><div class="muted">1200 pts</div><button class="redeem" data-cost="1200">Canjear</button></div>
    </div>
  </section>

  <div class="anden-motif">✦ INCA ✦</div>
</div>

<script>
document.querySelectorAll('.redeem').forEach(btn=>{
  btn.addEventListener('click',()=>{
    const cost=parseInt(btn.dataset.cost);
    if(!confirm(`¿Deseas canjear este premio por ${cost} puntos?`))return;
    fetch('',{
      method:'POST',
      headers:{'Content-Type':'application/x-www-form-urlencoded'},
      body:`redeem=1&cost=${cost}`
    }).then(res=>res.json())
    .then(data=>{
      if(data.status==='success'){
        document.getElementById('points-display').textContent=data.new_points;
        alert('✅ '+data.message);
      }else{
        alert('⚠️ '+data.message);
      }
    })
    .catch(()=>alert('Error al procesar el canje.'));
  });
});
</script>
</body>
</html>

