import discord
from discord.ext import commands
from discord import app_commands

class AddUserModal(discord.ui.Modal, title='Add User to Voice Channel'):
    user_to_add = discord.ui.TextInput(
        label='User ID or Name',
        placeholder='Enter the User ID or name of the user to add',
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
            await interaction.response.send_message(f"Added {user.mention} to your voice channel.", ephemeral=True)
        else:
            await interaction.response.send_message("Could not find the user or you are not in a voice channel.", ephemeral=True)

class AdminTools(commands.Cog):
    def __init__(self, bot):
        self.bot = bot

    @app_commands.command(name="adminpanel", description="Configure the bot")
    @app_commands.checks.has_permissions(administrator=True)
    async def adminpanel(self, interaction: discord.Interaction):
        embed = discord.Embed(
            title="Admin Panel",
            description="This is the admin panel. Configuration options will be added here.",
            color=0x3aff3a
        )
        embed.set_footer(text="© Capybara Crew 2025 – powered by Chelo Lima EIRL")
        await interaction.response.send_message(embed=embed, ephemeral=True)

    @app_commands.command(name="add_user", description="Add a user to your temporary voice channel")
    async def add_user(self, interaction: discord.Interaction):
        await interaction.response.send_modal(AddUserModal())

    @app_commands.command(name="broadcast_role", description="Give a role to all members")
    @app_commands.checks.is_owner()
    async def broadcast_role(self, interaction: discord.Interaction, role: discord.Role):
        await interaction.response.defer(ephemeral=True)
        for member in interaction.guild.members:
            if not member.bot:
                await member.add_roles(role)
        await interaction.followup.send(f"The {role.name} role has been given to all members.")

def setup(bot):
    bot.add_cog(AdminTools(bot))
