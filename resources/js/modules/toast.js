// Store toast global (Alpine.store('toast')).
//
// Dipakai oleh semua layout untuk menampilkan kartu popup info yang seragam:
// pesan flash dari server (session) maupun aksi AJAX di sisi klien.
// Setiap kartu tampil 3 detik (dwell) lalu menghilang dengan animasi keluar.

const DEFAULT_TITLES = {
    success: 'Berhasil',
    error: 'Gagal',
    warning: 'Perhatian',
    info: 'Info',
};

export function createToastStore() {
    return {
        items: [],
        seq: 0,

        /** Lama kartu tampil sebelum menghilang (milidetik). */
        dwell: 3000,

        /** Jumlah maksimum kartu yang tampil bersamaan. */
        limit: 4,

        /**
         * Tampilkan satu kartu popup.
         *
         * @param {{type?: string, title?: string, message?: string}} payload
         * @returns {number} id kartu
         */
        push(payload = {}) {
            const type = payload.type || 'info';
            const id = ++this.seq;

            this.items.push({
                id,
                type,
                title: payload.title || DEFAULT_TITLES[type] || DEFAULT_TITLES.info,
                message: payload.message || '',
            });

            if (this.items.length > this.limit) {
                this.items.splice(0, this.items.length - this.limit);
            }

            window.setTimeout(() => this.dismiss(id), this.dwell);

            return id;
        },

        /** Tutup kartu lebih awal (mis. tombol close). */
        dismiss(id) {
            this.items = this.items.filter((item) => item.id !== id);
        },
    };
}
