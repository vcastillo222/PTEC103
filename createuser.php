 <?php
session_start();
include("d.php");

if (!isset($_SESSION['staff'])) {
    echo "Access denied. Staff only.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_user = $_POST['new_user'];
    $new_pass = md5($_POST['new_password']);

    $sql = "INSERT INTO users (username, password) VALUES ('$new_user', '$new_pass')";
    if ($conn->query($sql) === TRUE) {
        echo "User created successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create New User</title>


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

 
    </style>
</head>
<body>
<h2>Create New User</h2>
<form method="POST" action="createuser.php">
    Username: <input type="text" name="new_user" required><br><br>
    Password: <input type="password" name="new_password" required><br><br>
    <input type="submit" value="Create User">
</form>
</body>
</html>
