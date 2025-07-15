<?php
function get_db_connection() {
    $config = json_decode(file_get_contents(__DIR__ . '/../../config/config.json'), true);

    $conn = new mysqli(
        $config['mysql']['host'],
        $config['mysql']['user'],
        $config['mysql']['password'],
        $config['mysql']['database']
    );

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}
?>
