import discord
from discord.ext import commands
import json
import os

# Load configuration
with open('config/config.json', 'r') as f:
    config = json.load(f)

# Bot setup
intents = discord.Intents.default()
intents.members = True
bot = commands.Bot(command_prefix='/', intents=intents)
bot.config = config

@bot.event
async def on_ready():
    print(f'Logged in as {bot.user.name}')
    print('------')

# Load cogs
for filename in os.listdir('./cogs'):
    if filename.endswith('.py'):
        bot.load_extension(f'cogs.{filename[:-3]}')

bot.run(config['bot_token'])
