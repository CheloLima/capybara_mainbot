import discord
from discord.ext import commands
from discord import app_commands

class SelfRoles(commands.Cog):
    def __init__(self, bot):
        self.bot = bot
        self.bot.add_view(SelfRoleView())

    @app_commands.command(name="setup_selfroles", description="Richtet die Self-Role-Nachricht ein")
    @app_commands.checks.has_permissions(administrator=True)
    async def setup_selfroles(self, interaction: discord.Interaction):
        embed = discord.Embed(
            title="Self Roles",
            description="Klicke auf die Buttons unten, um deine Rollen zu erhalten.",
            color=0x3aff3a
        )
        embed.set_footer(text="© Capybara Crew 2025 – powered by Chelo Lima EIRL")

        view = SelfRoleView()

        await interaction.channel.send(embed=embed, view=view)
        await interaction.response.send_message("Die Self-Role-Nachricht wurde eingerichtet.", ephemeral=True)

class SelfRoleView(discord.ui.View):
    def __init__(self):
        super().__init__(timeout=None)
        # Roles will be configurable later via the web panel.
        # For now, we add some placeholder buttons.
        # The custom_id is what links the button to the role.
        self.add_item(discord.ui.Button(label="Rolle 1", style=discord.ButtonStyle.primary, custom_id="selfrole_123"))
        self.add_item(discord.ui.Button(label="Rolle 2", style=discord.ButtonStyle.primary, custom_id="selfrole_456"))

    async def interaction_check(self, interaction: discord.Interaction) -> bool:
        # This method is called before any button callback.
        # We use it to handle all self-role button clicks.
        if not interaction.data or not interaction.data.get("custom_id"):
            return False

        custom_id = interaction.data.get("custom_id")
        if not custom_id.startswith("selfrole_"):
            return False

        role_id = int(custom_id.split('_')[1])
        role = interaction.guild.get_role(role_id)

        if not role:
            await interaction.response.send_message("Diese Rolle existiert nicht mehr.", ephemeral=True)
            return False

        if role in interaction.user.roles:
            await interaction.user.remove_roles(role)
            await interaction.response.send_message(f"Die Rolle {role.name} wurde dir entfernt.", ephemeral=True)
        else:
            await interaction.user.add_roles(role)
            await interaction.response.send_message(f"Die Rolle {role.name} wurde dir hinzugefügt.", ephemeral=True)

        return True

async def setup(bot):
    await bot.add_cog(SelfRoles(bot))
