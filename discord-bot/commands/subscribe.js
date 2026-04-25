const { SlashCommandBuilder } = require('discord.js');

module.exports = {
    data: new SlashCommandBuilder()
        .setName('subscribe')
        .setDescription('Dapatkan role Novel Updates untuk notifikasi chapter baru'),
    async execute(interaction) {
        const roleName = process.env.ROLE_NAME || 'Novel Updates';
        let role = interaction.guild.roles.cache.find(r => r.name === roleName);

        // Jika role belum ada, coba buat (memerlukan permission Manage Roles)
        if (!role) {
            try {
                role = await interaction.guild.roles.create({
                    name: roleName,
                    color: 'Blue',
                    reason: 'Role untuk notifikasi update novel otomatis',
                });
            } catch (error) {
                return interaction.reply({ 
                    content: `Gagal menemukan atau membuat role "${roleName}". Pastikan bot memiliki izin "Manage Roles".`, 
                    ephemeral: true 
                });
            }
        }

        const member = interaction.member;
        if (member.roles.cache.has(role.id)) {
            await member.roles.remove(role);
            return interaction.reply({ 
                content: `Berhasil! Kamu telah berhenti berlangganan dari notifikasi novel.`, 
                ephemeral: true 
            });
        } else {
            await member.roles.add(role);
            return interaction.reply({ 
                content: `Berhasil! Kamu sekarang akan menerima notifikasi setiap ada chapter baru.`, 
                ephemeral: true 
            });
        }
    },
};
