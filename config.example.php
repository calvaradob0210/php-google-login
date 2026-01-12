<?php

require_once 'vendor/autoload.php';

session_start();



// Google OAuth2

$google_client = new Google_Client();

$google_client->setClientId('TU_CLIENT_ID');

$google_client->setClientSecret('TU_CLIENT_SECRET');

$google_client->setRedirectUri('http://localhost:8080/login/callback.php');

$google_client->addScope("email");

$google_client->addScope("profile");



// MySQL

$mysqli = new mysqli("localhost", "root", "", "login-php");

if ($mysqli->connect_errno) {

    die("Error al conectar a la base de datos: " . $mysqli->connect_error);

}

?>