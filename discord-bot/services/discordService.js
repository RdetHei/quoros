const { EmbedBuilder } = require('discord.js');

class DiscordService {
    constructor(client) {
        this.client = client;
    }

    /**
     * Send new chapter notification to a specific channel
     * @param {Object} data - { title, summary, url }
     */
    async sendChapterNotification(data) {
        try {
            const channelId = process.env.CHANNEL_ID;
            const channel = await this.client.channels.fetch(channelId);

            if (!channel) {
                console.error(`[DiscordService] Channel with ID ${channelId} not found.`);
                return false;
            }

            const roleId = process.env.MENTION_ROLE_ID;
            const mention = roleId ? `<@&${roleId}>` : '@everyone';

            const embed = new EmbedBuilder()
                .setTitle(data.title)
                .setURL(data.url)
                .setDescription(data.summary)
                .setColor(0x0099FF)
                .setTimestamp()
                .setFooter({ text: 'Mural Real-time Update' });

            await channel.send({
                content: `${mention} **Chapter Baru Telah Rilis!**`,
                embeds: [embed]
            });

            console.log(`[DiscordService] Notification sent for: ${data.title}`);
            return true;
        } catch (error) {
            console.error('[DiscordService] Error sending notification:', error);
            throw error;
        }
    }
}

module.exports = DiscordService;
