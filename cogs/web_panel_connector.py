import discord
from discord.ext import commands, tasks
import json
import aiohttp

class WebPanelConnector(commands.Cog):
    def __init__(self, bot):
        self.bot = bot
        self.update_web_panel.start()
        self.command_poller.start()

    def cog_unload(self):
        self.update_web_panel.cancel()
        self.command_poller.cancel()

    @tasks.loop(seconds=10)
    async def command_poller(self):
        await self.bot.wait_until_ready()
        async with aiohttp.ClientSession() as session:
            try:
                async with session.get('http://localhost/api.php?commands=true') as resp:
                    if resp.status == 200:
                        commands = await resp.json()
                        for command in commands:
                            action = command.get('action')
                            cog = command.get('cog')
                            if action == 'load':
                                self.bot.load_extension(f'cogs.{cog}')
                            elif action == 'unload':
                                self.bot.unload_extension(f'cogs.{cog}')
                            elif action == 'reload':
                                self.bot.reload_extension(f'cogs.{cog}')
            except aiohttp.ClientConnectorError as e:
                print(f"Error connecting to web panel: {e}")

    @tasks.loop(seconds=60)
    async def update_web_panel(self):
        await self.bot.wait_until_ready()
        guild = self.bot.get_guild(int(self.bot.config['guild_id']))
        if not guild:
            return

        data = {
            'guild_name': guild.name,
            'member_count': guild.member_count,
            'bot_ping': round(self.bot.latency * 1000),
            'cogs': list(self.bot.cogs.keys())
        }

        async with aiohttp.ClientSession() as session:
            try:
                async with session.post('http://localhost/api.php', json=data) as resp:
                    if resp.status != 200:
                        print(f"Error updating web panel: {resp.status}")
            except aiohttp.ClientConnectorError as e:
                print(f"Error connecting to web panel: {e}")


def setup(bot):
    bot.add_cog(WebPanelConnector(bot))
