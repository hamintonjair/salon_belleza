<?php
require_once 'vendor/autoload.php'; // Asegúrate de que el autoload de Composer está incluido

$session = session();
// Configura los parámetros de la API de Google
$client = new Google_Client();
$client->setClientId(getenv('GOOGLE_CLIENT_ID'));
$client->setClientSecret(getenv('GOOGLE_CLIENT_SECRET'));
$client->setRedirectUri(getenv('GOOGLE_REDIRECT_URI'));
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
