const express = require('express');
const router = express.Router();

module.exports = (discordService) => {
    // Middleware for Authorization
    const authMiddleware = (req, res, next) => {
        const authHeader = req.headers.authorization;
        const secretToken = process.env.SECRET_TOKEN;

        if (!authHeader || authHeader !== `Bearer ${secretToken}`) {
            console.warn('[API] Unauthorized access attempt detected.');
            return res.status(401).json({ message: 'Unauthorized' });
        }
        next();
    };

    // POST /api/new-chapter
    router.post('/new-chapter', authMiddleware, async (req, res) => {
        const { title, summary, url } = req.body;

        // Validation
        if (!title || !url) {
            return res.status(400).json({ message: 'Title and URL are required' });
        }

        console.log(`[API] Received new chapter request: ${title}`);

        try {
            const success = await discordService.sendChapterNotification({ title, summary, url });
            if (success) {
                return res.status(200).json({ message: 'Notification sent' });
            } else {
                return res.status(500).json({ message: 'Failed to send notification' });
            }
        } catch (error) {
            console.error('[API] Error handling request:', error);
            return res.status(500).json({ message: 'Internal Server Error' });
        }
    });

    return router;
};
