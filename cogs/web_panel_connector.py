from discord.ext import commands

class WebPanelConnector(commands.Cog):
    def __init__(self, bot):
        self.bot = bot

def setup(bot):
    bot.add_cog(WebPanelConnector(bot))
