function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

async function jsonFetch(url, options = {}) {
    const headers = {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrfToken(),
        ...(options.body instanceof FormData ? {} : { 'Content-Type': 'application/json' }),
        ...(options.headers || {}),
    };

    const response = await fetch(url, { ...options, headers });
    const data = await response.json().catch(() => ({}));

    if (!response.ok) {
        const error = new Error(data.message || 'Permintaan gagal.');
        error.status = response.status;
        error.payload = data;
        throw error;
    }

    return data;
}

export function chatLounge(config) {
    return {
        messages: config.messages || [],
        packs: config.packs || [],
        emojis: config.emojis || [],
        feedUrl: config.feedUrl,
        storeUrl: config.storeUrl,
        reactionUrl: config.reactionUrl,
        destroyUrlTemplate: config.destroyUrlTemplate,
        loginUrl: config.loginUrl,
        canPost: Boolean(config.canPost),
        body: '',
        sticker: null,
        parent: null,
        showEmoji: false,
        showStickers: false,
        stickerPack: 0,
        sending: false,
        error: '',
        pollTimer: null,
        stickToBottom: true,

        get lastId() {
            return this.messages.reduce((max, item) => Math.max(max, item.id), 0);
        },

        init() {
            this.$nextTick(() => this.scrollToBottom(true));
            this.pollTimer = setInterval(() => this.poll(), 4000);
            this.$watch('messages', () => {
                if (this.stickToBottom) {
                    this.$nextTick(() => this.scrollToBottom());
                }
            });
        },

        destroy() {
            if (this.pollTimer) {
                clearInterval(this.pollTimer);
            }
        },

        onScroll(event) {
            const el = event.target;
            this.stickToBottom = el.scrollHeight - el.scrollTop - el.clientHeight < 80;
        },

        scrollToBottom(force = false) {
            const scroller = this.$refs.scroller;
            if (!scroller) return;
            if (force || this.stickToBottom) {
                scroller.scrollTop = scroller.scrollHeight;
            }
        },

        insertEmoji(emoji) {
            this.body = `${this.body}${emoji}`;
            this.showEmoji = false;
            this.$nextTick(() => this.$refs.composer?.focus());
        },

        pickSticker(item) {
            this.sticker = item;
            this.showStickers = false;
            this.$nextTick(() => this.$refs.composer?.focus());
        },

        setReply(message) {
            this.parent = {
                id: message.id,
                name: message.user?.name || 'Pengguna',
                excerpt: message.body || message.sticker?.label || 'Stiker',
            };
            this.$nextTick(() => this.$refs.composer?.focus());
        },

        async poll() {
            try {
                const data = await jsonFetch(`${this.feedUrl}?after=${this.lastId}`);
                if (!data.messages?.length) return;

                const existing = new Set(this.messages.map((item) => item.id));
                const incoming = data.messages.filter((item) => !existing.has(item.id));
                if (incoming.length) {
                    this.messages = [...this.messages, ...incoming];
                }
            } catch (error) {
                // Keep the lounge usable even if a poll fails.
            }
        },

        async send() {
            if (!this.canPost) {
                window.location.href = this.loginUrl;
                return;
            }

            const body = this.body.trim();
            if (!body && !this.sticker) {
                this.error = 'Tulis pesan atau pilih stiker.';
                return;
            }

            this.sending = true;
            this.error = '';

            try {
                const data = await jsonFetch(this.storeUrl, {
                    method: 'POST',
                    body: JSON.stringify({
                        body: body || null,
                        sticker: this.sticker?.id || null,
                        parent_id: this.parent?.id || null,
                    }),
                });

                if (data.message && !this.messages.some((item) => item.id === data.message.id)) {
                    this.messages = [...this.messages, data.message];
                }

                this.body = '';
                this.sticker = null;
                this.parent = null;
                this.stickToBottom = true;
                this.$nextTick(() => this.scrollToBottom(true));
            } catch (error) {
                this.error = error.message;
            } finally {
                this.sending = false;
            }
        },

        reactionEndpoint(id) {
            return this.reactionUrl.replace('__ID__', String(id));
        },

        applyReaction(message, payload) {
            message.score = payload.score;
            message.user_vote = payload.user_vote;
            message.emojis = payload.emojis;
        },

        async vote(message, reactionType) {
            if (!this.canPost) {
                window.location.href = this.loginUrl;
                return;
            }

            try {
                const payload = await jsonFetch(this.reactionEndpoint(message.id), {
                    method: 'POST',
                    body: JSON.stringify({ reaction_type: reactionType }),
                });
                this.applyReaction(message, payload);
            } catch (error) {
                this.error = error.message;
            }
        },

        async react(message, emoji) {
            if (!this.canPost) {
                window.location.href = this.loginUrl;
                return;
            }

            try {
                const payload = await jsonFetch(this.reactionEndpoint(message.id), {
                    method: 'POST',
                    body: JSON.stringify({ emoji }),
                });
                this.applyReaction(message, payload);
            } catch (error) {
                this.error = error.message;
            }
        },

        async remove(message) {
            if (!message.can_delete) return;
            if (!window.confirm('Hapus pesan ini?')) return;

            try {
                await jsonFetch(this.destroyUrlTemplate.replace('__ID__', String(message.id)), {
                    method: 'DELETE',
                });
                this.messages = this.messages.filter((item) => item.id !== message.id);
            } catch (error) {
                this.error = error.message;
            }
        },
    };
}

export function communityComposer() {
    return {
        sticker: '',
        stickerPreview: null,
        showEmoji: false,
        showStickers: false,
        stickerPack: 0,

        insertEmoji(emoji) {
            const field = this.$refs.composer;
            if (!field) return;
            const start = field.selectionStart ?? field.value.length;
            const end = field.selectionEnd ?? field.value.length;
            field.value = field.value.slice(0, start) + emoji + field.value.slice(end);
            field.dispatchEvent(new Event('input'));
            this.showEmoji = false;
            this.$nextTick(() => {
                field.focus();
                const cursor = start + emoji.length;
                field.setSelectionRange(cursor, cursor);
            });
        },

        pickSticker(item) {
            this.sticker = item.id;
            this.stickerPreview = item;
            this.showStickers = false;
        },

        clearSticker() {
            this.sticker = '';
            this.stickerPreview = null;
        },
    };
}
