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

async def load_cogs():
    for filename in os.listdir('./cogs'):
        if filename.endswith('.py'):
            await bot.load_extension(f'cogs.{filename[:-3]}')

async def main():
    async with bot:
        await load_cogs()
        await bot.start(config['bot_token'])

if __name__ == "__main__":
    import asyncio
    asyncio.run(main())
