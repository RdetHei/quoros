/* Quoros PWA — cache bab (HTML & JSON infinite-scroll) + aset /build */
const VERSION = 'quoros-sw-2026-11-09';
const CHAPTER_CACHE = `${VERSION}-chapter`;
const STATIC_CACHE = `${VERSION}-static`;

function isSameOrigin(url) {
  return url.origin === self.location.origin;
}

function isChapterReadPath(pathname) {
  return /^\/novels\/[^/]+\/read\//.test(pathname);
}

self.addEventListener('install', (event) => {
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches
      .keys()
      .then((keys) =>
        Promise.all(
          keys
            .filter((key) => key !== CHAPTER_CACHE && key !== STATIC_CACHE)
            .map((key) => caches.delete(key)),
        ),
      )
      .then(() => self.clients.claim()),
  );
});

self.addEventListener('fetch', (event) => {
  const req = event.request;
  if (req.method !== 'GET') return;

  const url = new URL(req.url);
  if (!isSameOrigin(url)) return;

  if (isChapterReadPath(url.pathname)) {
    event.respondWith(
      fetch(req)
        .then((res) => {
          if (res.ok) {
            const copy = res.clone();
            caches.open(CHAPTER_CACHE).then((cache) => cache.put(req, copy));
          }
          return res;
        })
        .catch(() =>
          caches.match(req).then((cached) => {
            if (cached) return cached;
            if (req.mode === 'navigate') {
              return caches.match('/offline.html');
            }
            return new Response(JSON.stringify({ error: 'offline' }), {
              status: 503,
              headers: { 'Content-Type': 'application/json' },
            });
          }),
        ),
    );
    return;
  }

  // Hanya aset Vite yang ber-hash yang di-cache. /storage TIDAK diintersep:
  // file di sana sudah dikirim dengan Cache-Control dari server, jadi cache
  // HTTP browser yang menanganinya — menghindari cache ganda yang bisa
  // menyajikan respons rusak/kedaluwarsa.
  if (url.pathname.startsWith('/build/')) {
    event.respondWith(
      caches.match(req).then(
        (cached) =>
          cached ||
          fetch(req).then((res) => {
            if (res.ok) {
              const copy = res.clone();
              caches
                .open(STATIC_CACHE)
                .then((cache) => cache.put(req, copy))
                .catch(() => {});
            }
            return res;
          }),
      ),
    );
  }
});
