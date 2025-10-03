<?php
session_start();
include("d.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $staff_user = $_POST['staff_user'];
    $staff_pass = md5($_POST['staff_password']);

    $sql = "SELECT * FROM staff WHERE username='$staff_user' AND password='$staff_pass'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $_SESSION['staff'] = $staff_user;
        header("Location: createuser.php");
        exit();
    } else {
        echo "Invalid staff credentials";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Staff Login</title>


    <style>
        


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

 :root {
  --greenSeaweed: rgba(2, 128, 144, 1);
  --blueQueen: rgba(69, 105, 144, 1);
  --redFire: rgba(244, 91, 105, 1);
  --fontAsap: 'Asap', sans-serif;
}

body {
  background-color: var(--redFire);
  font-family: var(--fontAsap);
}

main{
  flex: 1; /* This ensures the main content takes up available space */
  padding-top: 60px; /* Ensure content doesn't get hidden under the navbar */
}

.login {
  overflow: hidden;
  background-color: white;
  padding: 40px 30px 30px 30px;
  border-radius: 10px;
  position: absolute;
  top: 50%;
  left: 50%;
  width: 400px;
  transform: translate(-50%, -50%);
  transition: transform 300ms, box-shadow 300ms;
  box-shadow: 5px 10px 10px rgba(2, 128, 144, 0.2);
}

.login::before,
.login::after {
  content: '';
  position: absolute;
  width: 600px;
  height: 600px;
  border-top-left-radius: 40%;
  border-top-right-radius: 45%;
  border-bottom-left-radius: 35%;
  border-bottom-right-radius: 40%;
  z-index: -1;
}

.login::before {
  left: 40%;
  bottom: -130%;
  background-color: rgba(69, 105, 144, 0.15);
  animation: waves 6s infinite linear;
}

.login::after {
  left: 35%;
  bottom: -125%;
  background-color: rgba(2, 128, 144, 0.2);
  animation: waves 7s infinite linear;
}

.login input {
  font-family: var(--fontAsap);
  display: block;
  border-radius: 5px;
  font-size: 16px;
  background: white;
  width: 100%;
  border: 0;
  padding: 10px 10px;
  margin: 15px -10px;
}

.login button {
  font-family: var(--fontAsap);
  cursor: pointer;
  color: #fff;
  font-size: 16px;
  text-transform: uppercase;
  width: 80px;
  border: 0;
  padding: 10px 0;
  margin-top: 10px;
  margin-left: -5px;
  border-radius: 5px;
  background-color: var(--redFire);
  transition: background-color 300ms;
}

.login button:hover {
  background-color: rgb(221, 77, 89); /* slightly darker than redFire */
}

@keyframes waves {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}


    </style>

             <nav>
    <input type="checkbox" id="check">
    <label for="check" class="checkbtn">
        <i class="fas fa-bars"></i>
    </label>
    <a href="#" class="enlace">
        <img src="logo.png" alt="" class="logo">
    </a>
    <ul>
        <li><a class="active" href="http://localhost/publichtml/index.html">INICIO</a></li>
        <li><a href="http://localhost/publichtml/peru2html.html ">Contacto</a></li>
        <li><a href="http://localhost/publichtml/peru3html.html">promociones</a></li>
        <li><a href="http://localhost/publichtml/forumab.php">Foro perú</a></li>
        <li><a href="http://localhost/publichtml/premium.php">Menú VIP</a></li>
        <li><a href="http://localhost/publichtml/ ">STAFF</a></li>
    </ul>
</nav>
</head>
<body>
<h2>Staff Login</h2>
<form class="login" method="POST" action="staff.php">
    Username: <input type="text" name="staff_user"><br><br>
    Password: <input type="password" name="staff_password"><br><br>
    <input type="submit" value="Login">
</form>
</body>
</html>
