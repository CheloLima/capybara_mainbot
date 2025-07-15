import discord
from discord.ext import commands
from discord import app_commands
from utils.db import get_db_connection, create_tables

class LoggingSystem(commands.Cog):
    def __init__(self, bot):
        self.bot = bot
        create_tables()

    @app_commands.command(name="logs", description="Zeigt Logs aus der Datenbank an")
    @app_commands.describe(log_type="Die Art des anzuzeigenden Logs")
    @app_commands.choices(log_type=[
        app_commands.Choice(name="Haupt-Logs", value="main_logs"),
        app_commands.Choice(name="Channel-Logs", value="channel_logs"),
        app_commands.Choice(name="Straf-Logs", value="punishment_logs"),
        app_commands.Choice(name="Audit/Benutzer-Logs", value="user_logs"),
    ])
    async def logs(self, interaction: discord.Interaction, log_type: app_commands.Choice[str]):
        conn = get_db_connection()
        cursor = conn.cursor(dictionary=True)
        # Fetch the last 10 log entries for the selected log type
        query = f"SELECT * FROM {log_type.value} ORDER BY timestamp DESC LIMIT 10"
        cursor.execute(query)
        logs = cursor.fetchall()

        embed = discord.Embed(
            title=f"{log_type.name}",
            color=0x3aff3a
        )

        if not logs:
            embed.description = "Keine Logs gefunden."
        else:
            for log in logs:
                embed.add_field(
                    name=f"ID: {log['id']} | {log['timestamp']}",
                    value=f"```{log}```",
                    inline=False
                )

        embed.set_footer(text="© Capybara Crew 2025 – powered by Chelo Lima EIRL")
        await interaction.response.send_message(embed=embed, ephemeral=True)

    @commands.Cog.listener()
    async def on_guild_channel_create(self, channel):
        conn = get_db_connection()
        cursor = conn.cursor()
        query = "INSERT INTO channel_logs (channel_id, channel_name, event, user_id, user_name) VALUES (%s, %s, %s, %s, %s)"

        # Getting the user who created the channel from the audit log
        async for entry in channel.guild.audit_logs(limit=1, action=discord.AuditLogAction.channel_create):
            user = entry.user
            cursor.execute(query, (channel.id, channel.name, "create", user.id, user.name))
            break

        conn.commit()
        cursor.close()
        conn.close()

    @commands.Cog.listener()
    async def on_guild_channel_delete(self, channel):
        conn = get_db_connection()
        cursor = conn.cursor()
        query = "INSERT INTO channel_logs (channel_id, channel_name, event, user_id, user_name) VALUES (%s, %s, %s, %s, %s)"

        async for entry in channel.guild.audit_logs(limit=1, action=discord.AuditLogAction.channel_delete):
            user = entry.user
            cursor.execute(query, (channel.id, channel.name, "delete", user.id, user.name))
            break

        conn.commit()
        cursor.close()
        conn.close()

    @commands.Cog.listener()
    async def on_member_join(self, member):
        conn = get_db_connection()
        cursor = conn.cursor()
        query = "INSERT INTO user_logs (user_id, user_name, event) VALUES (%s, %s, %s)"
        cursor.execute(query, (member.id, member.name, "join"))
        conn.commit()
        cursor.close()
        conn.close()

    @commands.Cog.listener()
    async def on_member_remove(self, member):
        conn = get_db_connection()
        cursor = conn.cursor()
        query = "INSERT INTO user_logs (user_id, user_name, event) VALUES (%s, %s, %s)"
        cursor.execute(query, (member.id, member.name, "leave"))
        conn.commit()
        cursor.close()
        conn.close()

def setup(bot):
    bot.add_cog(LoggingSystem(bot))
