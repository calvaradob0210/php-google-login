<?php
require_once 'config.php';

// Crear URL de login de Google
$login_url = $google_client->createAuthUrl();

$registro_error = "";
$registro_exito = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Verificar si ya existe el email
    $stmt = $mysqli->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $registro_error = "El correo ya está registrado.";
    } else {
        $stmt = $mysqli->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $email, $password);
        $stmt->execute();
        $registro_exito = "Registro exitoso. Puedes iniciar sesión ahora.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registro Manual</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/registro.css">
</head>
<body>
<div class="container">

    <h1>Registro</h1>

    <?php if($registro_error): ?>
        <div class="message error"><?= htmlspecialchars($registro_error) ?></div>
    <?php endif; ?>
    <?php if($registro_exito): ?>
        <div class="message success"><?= htmlspecialchars($registro_exito) ?></div>
    <?php endif; ?>

    <!-- Formulario registro manual -->
    <form method="POST">
        <input type="text" name="nombre" placeholder="Nombre completo" required>
        <input type="email" name="email" placeholder="Correo electrónico" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit" class="submit-btn">Registrarse</button>
    </form>

    <!-- Botón Google -->
    <div class="social-login">
        <a href="<?= $login_url ?>" class="google-btn">
            <img src="img/logo.png" alt="Google Logo">
            Continuar con Google
        </a>
    </div>

    <div class="footer-text">
        ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
    </div>
</div>
</body>
</html>
