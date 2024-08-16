<?php
require_once 'vendor/autoload.php'; // Asegúrate de que el autoload de Composer está incluido

$session = session();
// Configura los parámetros de la API de Google
$client = new Google_Client();
$client->setClientId('216204466403-nkj558s6rv2eo1u9o1fni98kdo1tpffn.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/salon_belleza/');
$client->addScope('email');
$client->addScope('profile');

// Maneja el flujo de autenticación
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    // Obtén la información del usuario
    $oauth = new Google_Service_Oauth2($client);
    $userInfo = $oauth->userinfo->get();

    // Guardar la información del usuario en la sesión
   
    $dato = [
        'id' => $userInfo->id,
        'name' => $userInfo->name,
        'email' => $userInfo->email,
        'picture' => $userInfo->picture,
        'activo' => true,
    ];
    $session->set($dato);
    // Redirigir al usuario a la página de inicio
    header('Location: http://localhost/salon_belleza/dashboard');
    exit;
}

// Genera el URL para la autenticación
$authUrl = $client->createAuthUrl();
?>
