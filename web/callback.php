<?php
session_start();
require_once 'includes/config.php';

if (!isset($_GET['code'])) {
    header('Location: index.php');
    exit;
}

$code = $_GET['code'];

$data = array(
    'client_id' => OAUTH2_CLIENT_ID,
    'client_secret' => OAUTH2_CLIENT_SECRET,
    'grant_type' => 'authorization_code',
    'code' => $code,
    'redirect_uri' => OAUTH2_REDIRECT_URI,
    'scope' => 'identify guilds'
);

$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
);

$context  = stream_context_create($options);
$result = file_get_contents(API_ENDPOINT . '/oauth2/token', false, $context);
$token_data = json_decode($result, true);

if (!isset($token_data['access_token'])) {
    header('Location: index.php');
    exit;
}

$access_token = $token_data['access_token'];

$options = array(
    'http' => array(
        'header'  => "Authorization: Bearer " . $access_token
    )
);

$context = stream_context_create($options);
$result = file_get_contents(API_ENDPOINT . '/users/@me', false, $context);
$user_data = json_decode($result, true);

$_SESSION['user_id'] = $user_data['id'];
$_SESSION['username'] = $user_data['username'];
$_SESSION['avatar'] = $user_data['avatar'];

header('Location: dashboard.php');
exit;
?>
