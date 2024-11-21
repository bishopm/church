var staticCacheName = "pwa-v" + new Date().getTime();
var filesToCache = [
    '/',
    '/church/css/bootstrap.min.css',
    '/church/css/custom.css',
    '/church/css/leaflet.css',
    '/church/css/output.css',
    '/church/css/images/marker-icon-2x.png',
    '/church/css/images/marker-icon.png',
    '/church/css/images/marker-shadow.png',
    '/church/js/barcodescanner.js',
    '/church/js/bootstrap-bundle.min.js',
    '/church/js/custom.js',
    '/church/js/zxing.min.js',
    '/offline',
    '/church/images/icons/icon-72x72.png',
    '/church/images/icons/icon-96x96.png',
    '/church/images/icons/icon-128x128.png',
    '/church/images/icons/icon-144x144.png',
    '/church/images/icons/icon-152x152.png',
    '/church/images/icons/icon-192x192.png',
    '/church/images/icons/icon-384x384.png',
    '/church/images/icons/icon-512x512.png',
    '/church/images/aerial.png',
    '/church/images/blacklogo.png',
    '/church/images/blog.png',
    '/church/images/bwidelogo.png',
    '/church/images/calendar.png',
    '/church/images/church.png',
    '/church/images/circle.png',
    '/church/images/growslide.png',
    '/church/images/knowslide.png',
    '/church/images/showslide.png',
    '/church/images/welcomeslide.png'
];

// Cache on install
self.addEventListener("install", event => {
    this.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName)
            .then(cache => {
                return cache.addAll(filesToCache);
            })
    )
});

// Clear cache on activate
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                    .filter(cacheName => (cacheName.startsWith("pwa-")))
                    .filter(cacheName => (cacheName !== staticCacheName))
                    .map(cacheName => caches.delete(cacheName))
            );
        })
    );
});

// Serve from Cache
self.addEventListener("fetch", event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                return response || fetch(event.request);
            })
            .catch(() => {
                return caches.match('offline');
            })
    )
});