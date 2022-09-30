const preLoad = function () {
    return caches.open("offline").then(function (cache) {
        // caching index and important routes
        return cache.addAll(filesToCache);
    });
};

self.addEventListener("install", function (event) {
    event.waitUntil(preLoad());
});

const filesToCache = [
    '/',
    '/home',
    '/admin',
    '/admin/dashboard',
    '/admin/banner',
    '/admin/categoria',
    '/admin/producto',
    '/index.php',
    '/offline.html',
    '/storage/logo-gonzalito-blanco-1.png',
    '/storage/loading-orange.gif',
    '/storage/favicon.png',
    '/admin/banner/show',
    '/storage/banners/gonzalo.png',
    '/storage/banners/gonzalo0.png',
    '/storage/banners/gonzalo1.png',
    '/storage/banners/gonzalo2.png',
    '/storage/banners/gonzalo3.jpg',
    '/storage/banners/gonzalo4.jpeg',
   /* "{!! asset(Setting::getValue('app_favicon')) !!}",*/
    "https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback",
    "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css",
    "https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css",
    '/template/admin/dist/css/adminlte.min.css',
    '/template/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css',
    '/template/admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css',
    '/template/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css',
    '/logo.png',
    '/manifest.json',
    '/template/admin/plugins/jquery/jquery.min.js',
    /*"{{ asset('template/admin/dist/css/adminlte.min.css') }}",
    "{{ asset('template/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}",
    "{{ asset('template/admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}",
    "{{ asset('template/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}",
    "{{ asset('logo.PNG') }}",
    "{{ asset('/manifest.json') }}",
    "{{ asset(Setting::getValue('app_loading_gif')) }}",
    "{{ asset('template/admin/plugins/jquery/jquery.min.js') }}",*/
    "https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css",
    "https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js",
    '/template/admin/plugins/jquery-ui/jquery-ui.min.js',
    '/template/admin/plugins/datatables/jquery.dataTables.min.js',
    '/template/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js',
    '/template/admin/plugins/bootstrap/js/bootstrap.bundle.min.js',
    '/template/admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js',
    '/template/admin/dist/js/adminlte.js',
    '/template/admin/dist/img/user2-160x160.jpg',
    /*"{{ asset('template/admin/plugins/jquery-ui/jquery-ui.min.js') }}",
    "{{ asset('template/admin/plugins/datatables/jquery.dataTables.min.js') }}",
    "{{ asset('template/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}",
    "{{ asset('template/admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}",
    "{{ asset('template/admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}",
    "{{ asset('template/admin/dist/js/adminlte.js') }}",*/
];

const checkResponse = function (request) {
    return new Promise(function (fulfill, reject) {
        fetch(request).then(function (response) {
            if (response.status !== 404) {
                fulfill(response);
            } else {
                reject();
            }
        }, reject);
    });
};

const addToCache = function (request) {
    return caches.open("offline").then(function (cache) {
        return fetch(request).then(function (response) {
            return cache.put(request, response);
        });
    });
};

const returnFromCache = function (request) {
    return caches.open("offline").then(function (cache) {
        return cache.match(request).then(function (matching) {
            if (!matching || matching.status === 404) {
                return cache.match("offline.html");
            } else {
                return matching;
            }
        });
    });
};

self.addEventListener("fetch", function (event) {
    event.respondWith(checkResponse(event.request).catch(function () {
        return returnFromCache(event.request);
    }));
    if(!event.request.url.startsWith('http')){
        event.waitUntil(addToCache(event.request));
    }
});
