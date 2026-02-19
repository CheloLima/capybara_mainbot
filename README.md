# Crew Discord Bot

Dies ist ein modularer Discord-Bot für Crews.

## Funktionen

*   **Modulares System**: Funktionen sind in Cogs organisiert, die aktiviert oder deaktiviert werden können.
*   **TempVoice-System**: Benutzer können ihre eigenen temporären Sprachkanäle erstellen.
*   **SelfRole-System**: Benutzer können sich über Buttons selbst Rollen zuweisen.
*   **Logging-System**: Bot- und Server-Ereignisse werden in einer MySQL-Datenbank protokolliert.
*   **Admin-Werkzeuge**: Enthält Befehle zur Verwaltung des Bots und des Servers.
*   **Web-Interface**: Eine webbasierte Oberfläche zur Konfiguration und Verwaltung.

## Befehle

### Admin
*   `/adminpanel`: Öffnet das Admin-Panel zur Bot-Konfiguration.
*   `/setup_selfroles`: Richtet die Self-Role-Nachricht mit Buttons ein.
*   `/broadcast_role [rolle]`: Vergibt eine Rolle an alle Mitglieder (nur für den Bot-Besitzer).

### TempVoice
*   `/add_user`: Fügt einen Benutzer zu deinem temporären Sprachkanal hinzu.

### Logging
*   `/logs [log_typ]`: Zeigt Protokolle aus der Datenbank an.

## Einrichtung

1.  **Repository klonen:**
    ```bash
    git clone https://github.com/your-repo/capybara-bot.git
    cd capybara-bot
    ```
2.  **Abhängigkeiten installieren:**
    ```bash
    pip install -r requirements.txt
    ```
3.  **Bot konfigurieren:**
    *   Benenne `config/config.example.json` in `config/config.json` um.
    *   Fülle die erforderlichen Werte in `config/config.json` aus:
        *   `bot_token`: Dein Discord-Bot-Token.
        *   `client_id`: Die Client-ID deines Bots.
        *   `guild_id`: Die ID deines Discord-Servers.
        *   `mysql`: Deine MySQL-Datenbank-Anmeldeinformationen.
4.  **Bot starten:**
    ```bash
    python bot.py
    ```

## Web-Interface

Das Web-Interface wird zur Konfiguration des Bots und zur Anzeige von Protokollen verwendet. Um das Web-Interface zu starten, führe den folgenden Befehl aus:

```bash
php -S localhost:8000 -t web
```

Das Web-Interface ist dann unter `http://localhost:8000` verfügbar.

---

© Capybara Crew 2025 – powered by Chelo Lima EIRL
