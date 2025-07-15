import discord
from discord.ext import commands
from discord import app_commands

class SelfRoles(commands.Cog):
    def __init__(self, bot):
        self.bot = bot
        self.bot.add_view(SelfRoleView())

    @app_commands.command(name="setup_selfroles", description="Set up the self-role message")
    @app_commands.checks.has_permissions(administrator=True)
    async def setup_selfroles(self, interaction: discord.Interaction):
        embed = discord.Embed(
            title="Self Roles",
            description="Click the buttons below to get your roles.",
            color=0x3aff3a
        )
        embed.set_footer(text="© Capybara Crew 2025 – powered by Chelo Lima EIRL")

        view = SelfRoleView()

        await interaction.channel.send(embed=embed, view=view)
        await interaction.response.send_message("Self-role message has been set up.", ephemeral=True)

class SelfRoleView(discord.ui.View):
    def __init__(self):
        super().__init__(timeout=None)
        # Roles will be configurable later via the web panel.
        # For now, we add some placeholder buttons.
        # The custom_id is what links the button to the role.
        self.add_item(discord.ui.Button(label="Role 1", style=discord.ButtonStyle.primary, custom_id="selfrole_123"))
        self.add_item(discord.ui.Button(label="Role 2", style=discord.ButtonStyle.primary, custom_id="selfrole_456"))

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
            await interaction.response.send_message("This role no longer exists.", ephemeral=True)
            return False

        if role in interaction.user.roles:
            await interaction.user.remove_roles(role)
            await interaction.response.send_message(f"Removed the {role.name} role.", ephemeral=True)
        else:
            await interaction.user.add_roles(role)
            await interaction.response.send_message(f"Added the {role.name} role.", ephemeral=True)

        return True

def setup(bot):
    bot.add_cog(SelfRoles(bot))
