# Capybara Crew Discord Bot

This is a modular Discord bot for the Capybara Crew multigaming clan.

## Features

*   **Modular System**: Features are organized into cogs, which can be enabled or disabled.
*   **TempVoice System**: Users can create their own temporary voice channels.
*   **SelfRole System**: Users can assign roles to themselves using buttons.
*   **Logging System**: Bot and server events are logged to a MySQL database.
*   **Admin Tools**: Includes commands for managing the bot and server.
*   **Web Interface**: A web-based interface for configuration and management.

## Commands

### Admin
*   `/adminpanel`: Opens the admin panel for bot configuration.
*   `/setup_selfroles`: Sets up the self-role message with buttons.
*   `/broadcast_role [role]`: Gives a role to all members (bot owner only).

### TempVoice
*   `/add_user`: Adds a user to your temporary voice channel.

### Logging
*   `/logs [log_type]`: Displays logs from the database.

## Setup

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/your-repo/capybara-bot.git
    cd capybara-bot
    ```
2.  **Install dependencies:**
    ```bash
    pip install -r requirements.txt
    ```
3.  **Configure the bot:**
    *   Rename `config/config.example.json` to `config/config.json`.
    *   Fill in the required values in `config/config.json`:
        *   `bot_token`: Your Discord bot token.
        *   `client_id`: Your bot's client ID.
        *   `guild_id`: The ID of your Discord server.
        *   `mysql`: Your MySQL database credentials.
4.  **Run the bot:**
    ```bash
    python bot.py
    ```

## Web Interface

The web interface is used to configure the bot and view logs. To run the web interface, execute the following command:

```bash
python web/app.py
```

The web interface will be available at `http://127.0.0.1:5000`.

---

© Capybara Crew 2025 – powered by Chelo Lima EIRL
