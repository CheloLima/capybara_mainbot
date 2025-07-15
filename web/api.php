<?php
// This file will be used as a simple API endpoint
// The bot will write data to a file, and the web panel will read it.

$data_file = 'data.json';

function get_data() {
    global $data_file;
    if (!file_exists($data_file)) {
        return array();
    }
    $json = file_get_contents($data_file);
    return json_decode($json, true);
}

function save_data($data) {
    global $data_file;
    $json = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents($data_file, $json);
}

$command_file = 'commands.json';

function get_commands() {
    global $command_file;
    if (!file_exists($command_file)) {
        return array();
    }
    $json = file_get_contents($command_file);
    return json_decode($json, true);
}

function save_commands($commands) {
    global $command_file;
    $json = json_encode($commands, JSON_PRETTY_PRINT);
    file_put_contents($command_file, $json);
}

$config_file = 'config_web.json';

function get_web_config() {
    global $config_file;
    if (!file_exists($config_file)) {
        return array();
    }
    $json = file_get_contents($config_file);
    return json_decode($json, true);
}

function save_web_config($config) {
    global $config_file;
    $json = json_encode($config, JSON_PRETTY_PRINT);
    file_put_contents($config_file, $json);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['action'])) {
        // This is a command for the bot
        $commands = get_commands();
        $commands[] = $input;
        save_commands($commands);
        echo json_encode(array('status' => 'command queued'));
    } elseif (isset($input['web_config'])) {
        // This is a web config update
        save_web_config($input['web_config']);
        echo json_encode(array('status' => 'success'));
    } else {
        // This is data from the bot
        save_data($input);
        echo json_encode(array('status' => 'success'));
    }
} else {
    // Check if the request is for commands
    if (isset($_GET['commands'])) {
        $commands = get_commands();
        // Clear the commands after fetching them
        save_commands([]);
        echo json_encode($commands);
    } elseif (isset($_GET['web_config'])) {
        echo json_encode(get_web_config());
    } else {
        echo json_encode(get_data());
    }
}
?>
