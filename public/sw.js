/* Quoros PWA — cache bab (HTML & JSON infinite-scroll) + aset /build & /storage */
const VERSION = 'quoros-sw-2026-05-01';
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

  if (url.pathname.startsWith('/build/') || url.pathname.startsWith('/storage/')) {
    event.respondWith(
      caches.match(req).then(
        (cached) =>
          cached ||
          fetch(req).then((res) => {
            if (res.ok) {
              const copy = res.clone();
              caches.open(STATIC_CACHE).then((cache) => cache.put(req, copy));
            }
            return res;
          }),
      ),
    );
  }
});
