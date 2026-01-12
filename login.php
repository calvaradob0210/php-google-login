<?php
require_once 'config.php';

// Si el usuario ya está logueado, redirigir al dashboard
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit();
}

// Crear URL de login de Google
$login_url = $google_client->createAuthUrl();

// Procesar login manual
$login_error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_manual'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $mysqli->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && isset($user['password']) && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        header("Location: dashboard.php");
        exit();
    } else {
        $login_error = "Correo o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Iniciar sesión</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/login.css">
</head>
<body>
<div class="container">

    <h1>Iniciar sesión</h1>
    <div class="registro-text">¿No tienes una cuenta? <a href="form_registro.php">Regístrate</a></div>

    <!-- Login manual -->
    <?php if($login_error): ?>
        <div class="error"><?= htmlspecialchars($login_error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Correo electrónico" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit" name="login_manual" class="submit-btn">Iniciar sesión</button>
    </form>

    <!-- Login con Google -->
    <div class="social-login">
        <a href="<?= $login_url ?>" class="google-btn">
            <img src="img/logo.png" alt="Google Logo">
            Continuar con Google
        </a>
    </div>

    <div class="footer-text">
        Al iniciar sesión, aceptas nuestros Términos y Condiciones.
    </div>
</div>
</body>
</html>
