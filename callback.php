<?php
require_once 'config.php';

if (isset($_GET['code'])) {
    $token = $google_client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (!isset($token['error'])) {
        $google_client->setAccessToken($token['access_token']);
        $google_service = new Google_Service_Oauth2($google_client);
        $data = $google_service->userinfo->get();

        $nombre = $data->name;
        $email = $data->email;
        $google_id = $data->id;

        // Buscar por google_id o email
        $stmt = $mysqli->prepare("SELECT * FROM usuarios WHERE google_id = ? OR email = ?");
        $stmt->bind_param("ss", $google_id, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (empty($user['google_id'])) {
                $stmt = $mysqli->prepare("UPDATE usuarios SET google_id = ? WHERE id = ?");
                $stmt->bind_param("si", $google_id, $user['id']);
                $stmt->execute();
                $user['google_id'] = $google_id;
            }
            $_SESSION['user'] = $user;
        } else {
            $stmt = $mysqli->prepare("INSERT INTO usuarios (nombre, email, google_id) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nombre, $email, $google_id);
            $stmt->execute();
            $_SESSION['user'] = [
                'id' => $mysqli->insert_id,
                'nombre' => $nombre,
                'email' => $email,
                'google_id' => $google_id
            ];
        }

        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error al iniciar sesión con Google.";
    }
} else {
    echo "No se recibió código de Google.";
}
