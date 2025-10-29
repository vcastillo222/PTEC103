<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<?php
// Start PHP tag so the file can be run as PHP if needed
?>
<!DOCTYPE html>
<html>
    <head>
        <title> peru contacto
        </title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

 <link rel="stylesheet" href="peru2html.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    </head>
    <body>

 
  <style id="styles">
    :root{
      --peru-red: #d52b1e;
      --peru-white: #ffffff;
      --accent-gold: #f2c94c;
      --muted: #6b6b6b;
      --card-bg: #fff8ef;
      --max-width: 1100px;
      --shadow: 0 6px 18px rgba(0,0,0,0.08);
      --radius: 10px;
    }

    *{box-sizing:border-box}
    body{
      margin:0;
      font-family: "Helvetica Neue", Arial, sans-serif;
      background: linear-gradient(180deg, #fff 0%, #fff8f6 100%);
      color:#222;
      -webkit-font-smoothing:antialiased;
      -moz-osx-font-smoothing:grayscale;
      padding-bottom: 60px;
    }

    /* Placeholder for your NAV (you said you have it) */
    /* Keep your existing nav above the .page container in the final layout */
    .page{
      max-width: var(--max-width);
      margin: 28px auto;
      padding: 0 18px;
    }

    /* Top header */
    .hero{
      background: linear-gradient(90deg, var(--peru-red), #b71b16);
      color: var(--peru-white);
      border-radius: 12px;
      padding: 18px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      box-shadow: var(--shadow);
    }

    .hero .brand {
      display:flex;
      align-items:center;
      gap:12px;
    }
    .brand .flag{
      width:46px;
      height:30px;
      border-radius:4px;
      border:2px solid rgba(255,255,255,0.15);
      background: repeating-linear-gradient(
        90deg,
        var(--peru-red) 0 33%,
        var(--peru-white) 33% 66%,
        var(--peru-red) 66% 100%
      );
      box-shadow: 0 3px 8px rgba(0,0,0,0.12) inset;
    }
    .brand h1{
      margin:0;
      font-size:20px;
      letter-spacing:0.6px;
    }
    .brand p{margin:0;font-size:13px;opacity:0.95}

    /* Login button area (you said: only button) */
    .login-area{
      display:flex;
      align-items:center;
      gap:12px;
    }
    .points-badge{
      background: rgba(255,255,255,0.12);
      padding:8px 12px;
      border-radius:999px;
      font-weight:600;
      display:flex;
      gap:10px;
      align-items:center;
      font-size:14px;
    }
    .login-btn{
      background: var(--peru-white);
      color: var(--peru-red);
      border: 0;
      padding:10px 14px;
      border-radius:8px;
      cursor:pointer;
      font-weight:700;
      box-shadow: 0 6px 14px rgba(213,43,30,0.14);
      transition: transform .12s ease, box-shadow .12s ease;
      text-decoration:none;
    }
    .login-btn:hover{ transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.12);}

    /* 3-column images */
    .three-col{
      display:grid;
      grid-template-columns: repeat(3, 1fr);
      gap:14px;
      margin-top:20px;
    }
    .img-card{
      background: var(--card-bg);
      border-radius: 12px;
      padding:10px;
      text-align:center;
      box-shadow: var(--shadow);
      min-height:140px;
      display:flex;
      flex-direction:column;
      justify-content:center;
      align-items:center;
      overflow:hidden;
    }
    .img-card img{
      max-width:100%;
      height:110px;
      object-fit:cover;
      border-radius:8px;
      box-shadow: 0 6px 12px rgba(0,0,0,0.07);
    }
    .img-card small{display:block;margin-top:8px;color:var(--muted)}

    /* Separator */
    .separator{
      margin:30px 0;
      height:1px;
      background: linear-gradient(90deg, transparent, rgba(0,0,0,0.06), transparent);
      position:relative;
    }
    .separator::after{
      content:"⊹  Programa de Recompensas  ⊹";
      position:absolute;
      left:50%;
      top:-11px;
      transform:translateX(-50%);
      background: #fff;
      padding:0 12px;
      font-size:13px;
      color:#666;
      letter-spacing:0.6px;
    }

    /* Text block */
    .lead-text{
      background: linear-gradient(180deg, rgba(240,240,240,0.8), rgba(255,255,255,0.85));
      padding:18px;
      border-radius:10px;
      color:#333;
      box-shadow: 0 4px 10px rgba(0,0,0,0.04);
      line-height:1.5;
      margin-bottom:20px;
    }

    /* 6-column rewards */
    .rewards-grid{
      display:grid;
      grid-template-columns: repeat(6, 1fr);
      gap:12px;
      margin-top:14px;
    }
    .reward-card{
      background: #fff;
      border-radius:8px;
      padding:12px;
      text-align:center;
      box-shadow: var(--shadow);
      min-height:120px;
      display:flex;
      flex-direction:column;
      justify-content:space-between;
      gap:8px;
    }
    .reward-card h3{
      margin:0;
      font-size:14px;
    }
    .points {
      font-weight:800;
      font-size:18px;
      color:var(--peru-red);
    }
    .reward-card button{
      border:0;
      background: linear-gradient(180deg, var(--peru-red), #b71b16);
      color:#fff;
      padding:7px 10px;
      border-radius:6px;
      cursor:pointer;
      font-weight:700;
      transition:opacity .12s ease, transform .12s ease;
    }
    .reward-card button:active{ transform: translateY(1px); }
    .muted{font-size:12px;color:var(--muted)}

    /* Responsive */
    @media (max-width: 1000px){
      .rewards-grid{ grid-template-columns: repeat(3, 1fr); }
      .three-col{ grid-template-columns: repeat(2,1fr); }
    }
    @media (max-width: 600px){
      .rewards-grid{ grid-template-columns: repeat(2, 1fr); }
      .three-col{ grid-template-columns: 1fr; }
      .hero{ flex-direction:column; gap:12px; align-items:flex-start; }
      .hero .brand h1{ font-size:18px }
    }

    /* small Andean motif at bottom */
    .anden-motif{
      margin-top:28px;
      height:36px;
      border-radius:8px;
      background-image: linear-gradient(90deg, rgba(0,0,0,0.03), transparent);
      display:flex;
      align-items:center;
      justify-content:center;
      color:#8a6b3a;
      font-weight:700;
      letter-spacing:2px;
      background:
        linear-gradient(90deg, rgba(0,0,0,0.02), rgba(0,0,0,0.01)),
        repeating-linear-gradient(90deg, #f6e9d6 0 8px, #f2e3c9 8px 16px);
    }
  </style>
</head>
<body>


                <header >
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
        <li><a href="http://localhost/publicperu/peru2.php "> Puntos </a></li>
        <li><a href="http://localhost/publicperu/premium.php">Menú VIP</a></li>
        <li><a href="http://localhost/publicperu/forumon.php">Foro perú</a></li>
        <li><a href="http://localhost/publicperu/menu.php">menu</a></li>
        <li><a href="http://localhost/publicperu/staff.php">STAFF</a></li>

    </ul>
</nav>
  </header>
  <div class="page">
    <header class="hero" role="banner">
      <div class="brand">
        <div class="flag" aria-hidden="true"></div>
        <div>
          <h1>Recompensas Perú</h1>
          <p>Gana puntos en cada compra — canjéalos por promociones locales</p>
        </div>
      </div>

      <div class="login-area">
        <!-- points-badge shows placeholder; real points shown after login (handled by your login system) -->
        <div class="points-badge" title="Puntos actuales (sólo visibles si inicias sesión)">
          <span style="font-size:13px">Puntos:</span>
          <strong id="points-display">—</strong>
        </div>

        <!-- You said you already have the login; replace href with your login page URL -->
        <a class="login-btn" href="forumab.php" id="login-btn">Iniciar sesión</a>
      </div>
    </header>

    <!-- 3 images in boxes -->
    <section class="three-col" aria-label="Imagenes destacadas">
      <div class="img-card">
        <!-- Replace src with your own images -->
        <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='600' height='400'><rect width='100%' height='100%' fill='%23fdeeea'/><text x='50%' y='50%' dominant-baseline='middle' text-anchor='middle' fill='%23d52b1e' font-size='20'>Producto 1</text></svg>" alt="Producto 1">
        <small>Promo: 10% de descuento con 200 puntos</small>
      </div>

      <div class="img-card">
        <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='600' height='400'><rect width='100%' height='100%' fill='%23fff7ea'/><text x='50%' y='50%' dominant-baseline='middle' text-anchor='middle' fill='%23b71b16' font-size='20'>Producto 2</text></svg>" alt="Producto 2">
        <small>Canjea: Empanada gratis (350 pts)</small>
      </div>

      <div class="img-card">
        <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='600' height='400'><rect width='100%' height='100%' fill='%23fff1f0'/><text x='50%' y='50%' dominant-baseline='middle' text-anchor='middle' fill='%23d52b1e' font-size='20'>Producto 3</text></svg>" alt="Producto 3">
        <small>Acumula el doble los fines de semana</small>
      </div>
    </section>

    <!-- Separator -->
    <div class="separator" aria-hidden="true"></div>

    <!-- Text block -->
    <section class="lead-text" aria-label="Explicación del programa">
      <p><strong>¿Cómo funciona?</strong> Acumula puntos por cada compra. Cada 1 Sol = 1 punto. Suma puntos y canjéalos por descuentos, productos o acceso a promociones VIP. Los puntos son personales y están vinculados a tu cuenta — recuerda iniciar sesión para ver tu saldo y canjear.</p>
    </section>

    <!-- 6-column reward grid -->
    <section aria-label="Recompensas">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
        <h2 style="margin:0">Recompensas populares</h2>
        <small class="muted">Canjea con tus puntos</small>
      </div>

      <div class="rewards-grid">
        <!-- repeat 6 cards -->
        <div class="reward-card">
          <div>
            <h3>Café peruano - Americano</h3>
            <div class="muted">Tamaño único</div>
          </div>
          <div>
            <div class="points">120 pts</div>
            <button class="redeem" data-cost="120">Canjear</button>
          </div>
        </div>

        <div class="reward-card">
          <div>
            <h3>Alfajor artesano</h3>
            <div class="muted">Dulce tradicional</div>
          </div>
          <div>
            <div class="points">80 pts</div>
            <button class="redeem" data-cost="80">Canjear</button>
          </div>
        </div>

        <div class="reward-card">
          <div>
            <h3>Descuento 10%</h3>
            <div class="muted">En tu próxima compra</div>
          </div>
          <div>
            <div class="points">200 pts</div>
            <button class="redeem" data-cost="200">Canjear</button>
          </div>
        </div>

        <div class="reward-card">
          <div>
            <h3>Empanada</h3>
            <div class="muted">Sabor local</div>
          </div>
          <div>
            <div class="points">350 pts</div>
            <button class="redeem" data-cost="350">Canjear</button>
          </div>
        </div>

        <div class="reward-card">
          <div>
            <h3>Combo desayuno</h3>
            <div class="muted">Café + snack</div>
          </div>
          <div>
            <div class="points">450 pts</div>
            <button class="redeem" data-cost="450">Canjear</button>
          </div>
        </div>

        <div class="reward-card">
          <div>
            <h3>Acceso VIP</h3>
            <div class="muted">Promociones exclusivas</div>
          </div>
          <div>
            <div class="points">1200 pts</div>
            <button class="redeem" data-cost="1200">Canjear</button>
          </div>
        </div>
      </div>
    </section>

    <div class="anden-motif" aria-hidden="true">✦ INCA ✦</div>

  </div>

  <!-- Minimal JS to show behavior: clicking redeem requires login -->
  <script>
    (function(){
      const pointsDisplay = document.getElementById('points-display');
      pointsDisplay.textContent = 'Inicia sesión';

      document.querySelectorAll('.redeem').forEach(btn=>{
        btn.addEventListener('click', function(e){
          const loggedIn = false;
          const cost = this.getAttribute('data-cost');
          if(!loggedIn){
            if(confirm('Necesitas iniciar sesión para canjear ' + cost + ' puntos. ¿Ir a iniciar sesión?')) {
              window.location.href = 'forumab.php';
            }
            return;
          }
        });
      });
    })();
  </script>

</body>
</html>


</body>
</html>
