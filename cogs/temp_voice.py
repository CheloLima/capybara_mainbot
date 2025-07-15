import discord
from discord.ext import commands
import asyncio

class TempVoice(commands.Cog):
    def __init__(self, bot):
        self.bot = bot
        # This list will store the IDs of all temporary channels
        self.temp_channels = []

    @commands.Cog.listener()
    async def on_voice_state_update(self, member, before, after):
        # Check if the user joined the trigger channel
        # Note: The trigger channel ID will be configurable later
        trigger_channel_id = 1234567890  # Placeholder

        if after.channel and after.channel.id == trigger_channel_id:
            # Create a new temporary voice channel
            guild = member.guild
            category = after.channel.category  # Or a configurable category
            channel_name = f"{member.display_name}'s Channel"

            overwrites = {
                guild.default_role: discord.PermissionOverwrite(connect=False),
                member: discord.PermissionOverwrite(manage_channels=True, connect=True)
            }

            new_channel = await guild.create_voice_channel(
                name=channel_name,
                category=category,
                overwrites=overwrites
            )

            # Move the user to their new channel
            await member.move_to(new_channel)

            # Add to temp channels list for tracking
            self.temp_channels.append(new_channel.id)

            # Send the control panel
            await self.send_control_panel(new_channel)

        # Check if a temporary channel is now empty
        if before.channel and before.channel.id in self.temp_channels and not before.channel.members:
            # Wait 30 seconds before deleting the channel
            await asyncio.sleep(30)
            # Check again if the channel is still empty
            if not before.channel.members:
                await before.channel.delete()
                self.temp_channels.remove(before.channel.id)

    async def send_control_panel(self, channel):
        embed = discord.Embed(
            title="Willkommen in deinem temporären Channel!",
            description="Du kannst deinen Channel mit den Buttons unten verwalten.\n\n"
                        "**Hinweis:** `/add_user` bietet Autocomplete!",
            color=0x3aff3a
        )
        embed.set_footer(text="© Capybara Crew 2025 – powered by Chelo Lima EIRL")

        view = TempVoiceControlView(self)

        await channel.send(embed=embed, view=view)

class TempVoiceControlView(discord.ui.View):
    def __init__(self, cog):
        super().__init__(timeout=None)
        self.cog = cog

    @discord.ui.button(label="➕ Benutzer hinzufügen", style=discord.ButtonStyle.green, custom_id="add_user_button")
    async def add_user_button(self, button: discord.ui.Button, interaction: discord.Interaction):
        # This will be handled by a separate /add_user command with a modal
        await interaction.response.send_message("Bitte benutze den `/add_user` Befehl, um Benutzer hinzuzufügen.", ephemeral=True)

    @discord.ui.button(label="🗑️ Channel löschen", style=discord.ButtonStyle.red, custom_id="delete_channel_button")
    async def delete_channel_button(self, button: discord.ui.Button, interaction: discord.Interaction):
        channel = interaction.channel
        if channel.id in self.cog.temp_channels:
            await channel.delete()
            self.cog.temp_channels.remove(channel.id)
        else:
            await interaction.response.send_message("Dies ist kein temporärer Channel.", ephemeral=True)


async def setup(bot):
    await bot.add_cog(TempVoice(bot))
