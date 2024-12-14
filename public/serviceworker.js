var staticCacheName = "pwa-v" + new Date().getTime();

var filesToCache = [
  '/offline',
  '/css/app/custom-stylesheet.css',
  '/js/app/custom-javascript.js',
  '/js/app/printer.js',
  '/js/app/indexeddb.js',
  '/images/icons/icon-72x72.png',
  '/images/icons/icon-96x96.png',
  '/images/icons/icon-128x128.png',
  '/images/icons/icon-144x144.png',
  '/images/icons/icon-152x152.png',
  '/images/icons/icon-192x192.png',
  '/images/icons/icon-384x384.png',
  '/images/icons/icon-512x512.png',
  '/images/icons/splash_screens/iPhone_16_Pro_Max_landscape.png',
  '/images/icons/splash_screens/iPhone_16_Pro_landscape.png',
  '/images/icons/splash_screens/iPhone_16_Plus__iPhone_15_Pro_Max__iPhone_15_Plus__iPhone_14_Pro_Max_landscape.png',
  '/images/icons/splash_screens/iPhone_16__iPhone_15_Pro__iPhone_15__iPhone_14_Pro_landscape.png',
  '/images/icons/splash_screens/iPhone_14_Plus__iPhone_13_Pro_Max__iPhone_12_Pro_Max_landscape.png',
  '/images/icons/splash_screens/iPhone_14__iPhone_13_Pro__iPhone_13__iPhone_12_Pro__iPhone_12_landscape.png',
  '/images/icons/splash_screens/iPhone_13_mini__iPhone_12_mini__iPhone_11_Pro__iPhone_XS__iPhone_X_landscape.png',
  '/images/icons/splash_screens/iPhone_11_Pro_Max__iPhone_XS_Max_landscape.png',
  '/images/icons/splash_screens/iPhone_11__iPhone_XR_landscape.png',
  '/images/icons/splash_screens/iPhone_8_Plus__iPhone_7_Plus__iPhone_6s_Plus__iPhone_6_Plus_landscape.png',
  '/images/icons/splash_screens/iPhone_8__iPhone_7__iPhone_6s__iPhone_6__4.7__iPhone_SE_landscape.png',
  '/images/icons/splash_screens/4__iPhone_SE__iPod_touch_5th_generation_and_later_landscape.png',
  '/images/icons/splash_screens/13__iPad_Pro_M4_landscape.png',
  '/images/icons/splash_screens/12.9__iPad_Pro_landscape.png',
  '/images/icons/splash_screens/11__iPad_Pro_M4_landscape.png',
  '/images/icons/splash_screens/11__iPad_Pro__10.5__iPad_Pro_landscape.png',
  '/images/icons/splash_screens/10.9__iPad_Air_landscape.png',
  '/images/icons/splash_screens/10.5__iPad_Air_landscape.png',
  '/images/icons/splash_screens/10.2__iPad_landscape.png',
  '/images/icons/splash_screens/9.7__iPad_Pro__7.9__iPad_mini__9.7__iPad_Air__9.7__iPad_landscape.png',
  '/images/icons/splash_screens/8.3__iPad_Mini_landscape.png',
  '/images/icons/splash_screens/iPhone_16_Pro_Max_portrait.png',
  '/images/icons/splash_screens/iPhone_16_Pro_portrait.png',
  '/images/icons/splash_screens/iPhone_16_Plus__iPhone_15_Pro_Max__iPhone_15_Plus__iPhone_14_Pro_Max_portrait.png',
  '/images/icons/splash_screens/iPhone_16__iPhone_15_Pro__iPhone_15__iPhone_14_Pro_portrait.png',
  '/images/icons/splash_screens/iPhone_14_Plus__iPhone_13_Pro_Max__iPhone_12_Pro_Max_portrait.png',
  '/images/icons/splash_screens/iPhone_14__iPhone_13_Pro__iPhone_13__iPhone_12_Pro__iPhone_12_portrait.png',
  '/images/icons/splash_screens/iPhone_13_mini__iPhone_12_mini__iPhone_11_Pro__iPhone_XS__iPhone_X_portrait.png',
  '/images/icons/splash_screens/iPhone_11_Pro_Max__iPhone_XS_Max_portrait.png',
  '/images/icons/splash_screens/iPhone_11__iPhone_XR_portrait.png',
  '/images/icons/splash_screens/iPhone_8_Plus__iPhone_7_Plus__iPhone_6s_Plus__iPhone_6_Plus_portrait.png',
  '/images/icons/splash_screens/iPhone_8__iPhone_7__iPhone_6s__iPhone_6__4.7__iPhone_SE_portrait.png',
  '/images/icons/splash_screens/4__iPhone_SE__iPod_touch_5th_generation_and_later_portrait.png',
  '/images/icons/splash_screens/13__iPad_Pro_M4_portrait.png',
  '/images/icons/splash_screens/12.9__iPad_Pro_portrait.png',
  '/images/icons/splash_screens/11__iPad_Pro_M4_portrait.png',
  '/images/icons/splash_screens/11__iPad_Pro__10.5__iPad_Pro_portrait.png',
  '/images/icons/splash_screens/10.9__iPad_Air_portrait.png',
  '/images/icons/splash_screens/10.5__iPad_Air_portrait.png',
  '/images/icons/splash_screens/10.2__iPad_portrait.png',
  '/images/icons/splash_screens/9.7__iPad_Pro__7.9__iPad_mini__9.7__iPad_Air__9.7__iPad_portrait.png',
  '/images/icons/splash_screens/8.3__iPad_Mini_portrait.png',

];

self.addEventListener("install", event => {
  this.skipWaiting();
  event.waitUntil(
    caches.open(staticCacheName)
      .then(cache => {
        return Promise.all(filesToCache.map((url) => {
          return cache.add(url).catch((error) => {
            console.error(`Failed to cache ${url}:`, error);
          });
        })
        );
      })
  )
});

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

self.addEventListener('fetch', (event) => {
  event.respondWith(
    fetch(event.request)
      .catch(() => {
        return caches.match(event.request)
          .then((response) => {
            if (response) {
              return response;
            }

            if (event.request.mode === 'navigate') {
              return caches.match('/offline');
            }

            return new Response('', {
              status: 408,
              statusText: 'Request timed out.'
            });
          });
      })
  );
});

if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/serviceworker.js')
      .then(registration => {
        console.log('ServiceWorker registration successful');
      })
      .catch(err => {
        console.log('ServiceWorker registration failed: ', err);
      });
  });
}
