<?php
require_once 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

<div class="navbar">
    <h2>Inicio del Login</h2>
    <a href="logout.php">Cerrar sesión</a>
</div>

<div class="container">
    <div class="card">
        <h1>Bienvenido, <?= htmlspecialchars($user['nombre']) ?></h1>
        <div class="info">
            <p><span>Email:</span> <?= htmlspecialchars($user['email']) ?></p>
        </div>
    </div>
    <div class="footer">
        Desarrollado por Christian Alvarado - Desarrollador de Software Jr
    </div>
</div>
</body>

</html>