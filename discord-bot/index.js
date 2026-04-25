require('dotenv').config();
const { Client, GatewayIntentBits } = require('discord.js');
const express = require('express');
const DiscordService = require('./services/discordService');
const apiRoutes = require('./routes/api');

// 1. Initialize Discord Client
const client = new Client({
    intents: [
        GatewayIntentBits.Guilds,
        GatewayIntentBits.GuildMessages,
    ],
});

// 2. Initialize Express
const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
app.use(express.json());

// 3. Discord Bot Login
client.once('ready', () => {
    console.log(`[Discord] Logged in as ${client.user.tag}!`);
    
    // Initialize Discord Service after client is ready
    const discordService = new DiscordService(client);

    // 4. Register API Routes
    app.use('/api', apiRoutes(discordService));

    // 5. Start Express Server
    app.listen(PORT, () => {
        console.log(`[Express] Server running on http://localhost:${PORT}`);
    });
});

client.login(process.env.DISCORD_TOKEN).catch(err => {
    console.error('[Discord] Failed to login:', err);
});

// Global error handling for Express
app.use((err, req, res, next) => {
    console.error('[Express] Unhandled error:', err);
    res.status(500).json({ message: 'Internal Server Error' });
});
