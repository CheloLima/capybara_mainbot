<?php
require_once 'includes/config.php';

$params = array(
    'client_id' => OAUTH2_CLIENT_ID,
    'redirect_uri' => OAUTH2_REDIRECT_URI,
    'response_type' => 'code',
    'scope' => 'identify guilds'
);

header('Location: ' . API_ENDPOINT . '/oauth2/authorize?' . http_build_query($params));
die();
?>
