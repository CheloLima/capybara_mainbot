import mysql.connector
from mysql.connector import Error
import json

def get_db_connection():
    with open('config/config.json', 'r') as f:
        config = json.load(f)

    try:
        conn = mysql.connector.connect(
            host=config['mysql']['host'],
            user=config['mysql']['user'],
            password=config['mysql']['password'],
            database=config['mysql']['database']
        )
        return conn
    except Error as e:
        print(f"Error connecting to MySQL: {e}")
        return None

def create_tables():
    conn = get_db_connection()
    if conn is None:
        return

    cursor = conn.cursor()

    # Create main_logs table
    cursor.execute("""
        CREATE TABLE IF NOT EXISTS main_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            level VARCHAR(10),
            message TEXT
        )
    """)

    # Create channel_logs table
    cursor.execute("""
        CREATE TABLE IF NOT EXISTS channel_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            channel_id BIGINT,
            channel_name VARCHAR(255),
            event VARCHAR(50),
            user_id BIGINT,
            user_name VARCHAR(255)
        )
    """)

    # Create punishment_logs table
    cursor.execute("""
        CREATE TABLE IF NOT EXISTS punishment_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            user_id BIGINT,
            user_name VARCHAR(255),
            moderator_id BIGINT,
            moderator_name VARCHAR(255),
            punishment_type VARCHAR(50),
            reason TEXT
        )
    """)

    # Create user_logs table
    cursor.execute("""
        CREATE TABLE IF NOT EXISTS user_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            user_id BIGINT,
            user_name VARCHAR(255),
            event VARCHAR(50)
        )
    """)

    # Create audit_logs table
    cursor.execute("""
        CREATE TABLE IF NOT EXISTS audit_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            user_id BIGINT,
            user_name VARCHAR(255),
            action TEXT
        )
    """)

    conn.commit()
    cursor.close()
    conn.close()
