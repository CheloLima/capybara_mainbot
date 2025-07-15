import discord
from discord.ext import commands
from discord import app_commands

class AddUserModal(discord.ui.Modal, title='Benutzer zum Voice-Channel hinzufügen'):
    user_to_add = discord.ui.TextInput(
        label='Benutzer-ID oder Name',
        placeholder='Gib die Benutzer-ID oder den Namen des Benutzers ein',
    )

    async def on_submit(self, interaction: discord.Interaction):
        user_input = self.user_to_add.value
        # Try to get the user by name, then by ID
        user = interaction.guild.get_member_named(user_input)
        if not user:
            try:
                user = await interaction.guild.fetch_member(int(user_input))
            except (ValueError, discord.NotFound):
                pass

        if user and interaction.user.voice and interaction.user.voice.channel:
            await interaction.user.voice.channel.set_permissions(user, connect=True)
            await interaction.response.send_message(f"{user.mention} wurde zu deinem Voice-Channel hinzugefügt.", ephemeral=True)
        else:
            await interaction.response.send_message("Der Benutzer konnte nicht gefunden werden oder du bist in keinem Voice-Channel.", ephemeral=True)

class AdminTools(commands.Cog):
    def __init__(self, bot):
        self.bot = bot

    @app_commands.command(name="adminpanel", description="Konfiguriere den Bot")
    @app_commands.checks.has_permissions(administrator=True)
    async def adminpanel(self, interaction: discord.Interaction):
        embed = discord.Embed(
            title="Admin Panel",
            description="Dies ist das Admin-Panel. Konfigurationsoptionen werden hier hinzugefügt.",
            color=0x3aff3a
        )
        embed.set_footer(text="© Capybara Crew 2025 – powered by Chelo Lima EIRL")
        await interaction.response.send_message(embed=embed, ephemeral=True)

    @app_commands.command(name="add_user", description="Fügt einen Benutzer zu deinem temporären Voice-Channel hinzu")
    async def add_user(self, interaction: discord.Interaction):
        await interaction.response.send_modal(AddUserModal())

    @app_commands.command(name="broadcast_role", description="Vergibt eine Rolle an alle Mitglieder")
    @app_commands.checks.is_owner()
    async def broadcast_role(self, interaction: discord.Interaction, role: discord.Role):
        await interaction.response.defer(ephemeral=True)
        for member in interaction.guild.members:
            if not member.bot:
                await member.add_roles(role)
        await interaction.followup.send(f"Die Rolle {role.name} wurde an alle Mitglieder vergeben.")

async def setup(bot):
    await bot.add_cog(AdminTools(bot))
