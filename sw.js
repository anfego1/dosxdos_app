//asignar un nombre y versión al cache
const CACHE_NAME = 'dosxdos1',
  urlsToCache = [
    'https://dosxdos.app.iidos.com/index.html?utm_source=web_app_manifest',
    'https://dosxdos.app.iidos.com/index.html',
    'https://dosxdos.app.iidos.com/manifest.json',
    'https://dosxdos.app.iidos.com/serviceworker.js',
    'https://dosxdos.app.iidos.com/sw.js',
    'https://dosxdos.app.iidos.com/rutas_montador.html',
    'https://dosxdos.app.iidos.com/ruta_montador.html',
    'https://dosxdos.app.iidos.com/linea_montador.html',
    'https://dosxdos.app.iidos.com/fotos_y_firmas.html',
    'https://dosxdos.app.iidos.com/ot_completa.html',
    'https://dosxdos.app.iidos.com/ot.html',
    'https://dosxdos.app.iidos.com/css/fuentes/Roboto/Roboto-Light.ttf',
    'https://dosxdos.app.iidos.com/css/fuentes/Merriweather/Merriweather-Light.ttf',
    'https://dosxdos.app.iidos.com/css/fuentes/Merriweather/Merriweather-Bold.ttf',
    'https://dosxdos.app.iidos.com/css/fuentes/Lora/Lora-Regular.ttf',
    'https://dosxdos.app.iidos.com/css/fuentes/Lora/Lora-Medium.ttf',
    'https://dosxdos.app.iidos.com/css/fuentes/Lora/Lora-Bold.ttf',
    'https://dosxdos.app.iidos.com/img/candado.png',
    'https://dosxdos.app.iidos.com/img/casa.png',
    'https://dosxdos.app.iidos.com/img/casaWhite.png',
    'https://dosxdos.app.iidos.com/img/dosxdos.png',
    'https://dosxdos.app.iidos.com/img/email.png',
    'https://dosxdos.app.iidos.com/img/flechaAbajo.png',
    'https://dosxdos.app.iidos.com/img/fondo.jpg',
    'https://dosxdos.app.iidos.com/img/logoPwa1024.png',
    'https://dosxdos.app.iidos.com/img/logoPwa512.png',
    'https://dosxdos.app.iidos.com/img/logoPwa384.png',
    'https://dosxdos.app.iidos.com/img/logoPwa256.png',
    'https://dosxdos.app.iidos.com/img/logoPwa192.png',
    'https://dosxdos.app.iidos.com/img/logoPwa128.png',
    'https://dosxdos.app.iidos.com/img/logoPwa96.png',
    'https://dosxdos.app.iidos.com/img/logoPwa64.png',
    'https://dosxdos.app.iidos.com/img/logoPwa32.png',
    'https://dosxdos.app.iidos.com/img/logoPwa16.png',
    'https://dosxdos.app.iidos.com/img/logo300.png',
    'https://dosxdos.app.iidos.com/img/lupa.png',
    'https://dosxdos.app.iidos.com/img/reloj.png',
    'https://dosxdos.app.iidos.com/img/relojWhite.png',
    'https://dosxdos.app.iidos.com/img/usuario.png',
    'https://dosxdos.app.iidos.com/img/rutasWhite.png',
    'https://dosxdos.app.iidos.com/img/cerrar.png',
    'https://dosxdos.app.iidos.com/img/usuarios.png',
    'https://dosxdos.app.iidos.com/img/trash.png',
    'https://dosxdos.app.iidos.com/img/folder.png',
    'https://dosxdos.app.iidos.com/img/comprimido.png',
    'https://dosxdos.app.iidos.com/img/task.png',
    'https://dosxdos.app.iidos.com/img/work.png',
    'https://dosxdos.app.iidos.com/css/cdn_data_tables.css',
    'https://dosxdos.app.iidos.com/js/jquery.js',
    'https://dosxdos.app.iidos.com/js/data_tables.js',
    'https://dosxdos.app.iidos.com/js/cdn_data_tables.js',
    'https://dosxdos.app.iidos.com/js/index_db.js',
    'https://dosxdos.app.iidos.com/img/tienda.png',
    'https://dosxdos.app.iidos.com/img/clientes.png',
    'https://dosxdos.app.iidos.com/img/editar.png',
    'https://dosxdos.app.iidos.com/img/archivar.png',
    'https://dosxdos.app.iidos.com/img/back.png',
    'https://dosxdos.app.iidos.com/img/visible.png',
    'https://dosxdos.app.iidos.com/img/no_visible.png',
    'https://dosxdos.app.iidos.com/img/crear.png',
    'https://dosxdos.app.iidos.com/img/logo_clientes.png',
    'https://dosxdos.app.iidos.com/js/index_db.js',
    'https://dosxdos.app.iidos.com/img/logo2930.png',
    'https://dosxdos.app.iidos.com/img/logo_clientes.png',
    'https://dosxdos.app.iidos.com/img/instalar.png',
    'https://dosxdos.app.iidos.com/espanol.json',
    'https://dosxdos.app.iidos.com/english.json',
    'https://dosxdos.app.iidos.com/pv.html',
    'https://dosxdos.app.iidos.com/crear_pv.html',
    'https://dosxdos.app.iidos.com/editar_pv.html',
    'https://dosxdos.app.iidos.com/editar_pv.html',
    'https://dosxdos.app.iidos.com/js/fixed_header.js',
    'https://dosxdos.app.iidos.com/css/fuentes/Futura/Futura_Bold.otf',
    'https://dosxdos.app.iidos.com/css/fuentes/Futura/Futura_Light.otf',
    'https://dosxdos.app.iidos.com/css/fuentes/Futura/Futura_Medium.otf',
    'https://dosxdos.app.iidos.com/img/alerta.png',
    'https://dosxdos.app.iidos.com/img/saludo.png',
    'https://dosxdos.app.iidos.com/img/dm.png',
    'https://dosxdos.app.iidos.com/img/papelera.png',
    'https://dosxdos.app.iidos.com/horarios.html',
    'https://dosxdos.app.iidos.com/lineas_ot.html',
    'https://dosxdos.app.iidos.com/lineas.html',
    'https://dosxdos.app.iidos.com/usuarios_oficina.html',
    'https://dosxdos.app.iidos.com/dm.html',
    'https://dosxdos.app.iidos.com/reciclar.html',
    'https://dosxdos.app.iidos.com/rutas_inactivas.html',
    'https://dosxdos.app.iidos.com/img/trabajos.png',
    'https://dosxdos.app.iidos.com/img/clientes2.png',
    'https://dosxdos.app.iidos.com/img/sincronizar.png',
  ],

  urlsToUpdate = [
    'https://dosxdos.app.iidos.com/index.html?utm_source=web_app_manifest',
    'https://dosxdos.app.iidos.com/index.html',
    'https://dosxdos.app.iidos.com/manifest.json',
    'https://dosxdos.app.iidos.com/serviceworker.js',
    'https://dosxdos.app.iidos.com/sw.js',
    'https://dosxdos.app.iidos.com/rutas_montador.html',
    'https://dosxdos.app.iidos.com/ruta_montador.html',
    'https://dosxdos.app.iidos.com/linea_montador.html',
    'https://dosxdos.app.iidos.com/fotos_y_firmas.html',
    'https://dosxdos.app.iidos.com/ot_completa.html',
    'https://dosxdos.app.iidos.com/ot.html',
    'https://dosxdos.app.iidos.com/espanol.json',
    'https://dosxdos.app.iidos.com/english.json',
    'https://dosxdos.app.iidos.com/pv.html',
    'https://dosxdos.app.iidos.com/crear_pv.html',
    'https://dosxdos.app.iidos.com/editar_pv.html',
    'https://dosxdos.app.iidos.com/js/index_db.js',
    'https://dosxdos.app.iidos.com/img/logoPwa1024.png',
    'https://dosxdos.app.iidos.com/img/logoPwa512.png',
    'https://dosxdos.app.iidos.com/img/logoPwa384.png',
    'https://dosxdos.app.iidos.com/img/logoPwa256.png',
    'https://dosxdos.app.iidos.com/img/logoPwa192.png',
    'https://dosxdos.app.iidos.com/img/logoPwa128.png',
    'https://dosxdos.app.iidos.com/img/logoPwa96.png',
    'https://dosxdos.app.iidos.com/img/logoPwa64.png',
    'https://dosxdos.app.iidos.com/img/logoPwa32.png',
    'https://dosxdos.app.iidos.com/img/logoPwa16.png',
    'https://dosxdos.app.iidos.com/img/saludo.png',
    'https://dosxdos.app.iidos.com/horarios.html',
    'https://dosxdos.app.iidos.com/lineas_ot.html',
    'https://dosxdos.app.iidos.com/lineas.html',
    'https://dosxdos.app.iidos.com/usuarios_oficina.html',
    'https://dosxdos.app.iidos.com/dm.html',
    'https://dosxdos.app.iidos.com/reciclar.html',
    'https://dosxdos.app.iidos.com/rutas_inactivas.html',
  ];

//durante la fase de instalación, generalmente se almacena en caché los activos estáticos
self.addEventListener('install', e => {
  e.waitUntil(
    caches.keys()
      .then(cacheNames => {
        return Promise.all(
          cacheNames.map(cacheName => {
            // Eliminamos lo que ya no se necesita en caché
            if (CACHE_NAME != cacheName) {
              return caches.delete(cacheName, { force: true });
            }
            return false;
          })
        );
      })
      .then(() => caches.open(CACHE_NAME))
      .then(cache => {
        return Promise.all(
          urlsToCache.map(url => {
            return fetch(url, { cache: 'no-store' })
              .then(response => {
                if (!response.ok) {
                  console.error(`Error al intentar agregar un elemento a la caché: Failed to fetch ${url}`);
                }
                return cache.put(url, response);
              });
          })
        );
      })
      .then(() => self.skipWaiting())
      .catch(err => console.log('Falló registro de caché', err))
  );
});

//una vez que se instala el SW, se activa y busca los recursos para hacer que funcione sin conexión
self.addEventListener('activate', e => {
  e.waitUntil(
    caches.keys()
      .then(cacheNames => {
        return Promise.all(
          cacheNames.map(cacheName => {
            // Eliminamos lo que ya no se necesita en caché
            if (CACHE_NAME != cacheName) {
              return caches.delete(cacheName, { force: true });
            }
            return false;
          })
        );
      })
      // Le indica al SW activar el cache actual
      .then(() => self.clients.claim())
      .then(() => self.skipWaiting())
      .catch(err => console.log('Falló la activación de caché', err))
  )
})


self.addEventListener('fetch', event => {
  event.respondWith(
    (async function () {
      if (urlsToCache.includes(event.request.url)) {
        if (navigator.onLine) {
          try {
            const response = await fetch(event.request, { cache: 'no-store' });
            if (urlsToUpdate.includes(event.request.url)) {
              const cache = await caches.open(CACHE_NAME);
              cache.put(event.request, response.clone());
              console.log('Respuesta del servidor y se actualizó en caché: ' + event.request.url);
              return response;
            } else {
              console.log('Respuesta del servidor: ' + event.request.url);
              return response;
            }
          } catch (error) {
            console.error('Error en la solicitud: ' + event.request.url + '-' + error);
          }
        } else {
          const response = await caches.match(event.request);
          console.log('Respuesta de caché: ' + event.request.url);
          return response;
        }
      } else {
        console.log('Respuesta del servidor: ' + event.request.url);
        return fetch(event.request, { cache: 'no-store' });
      }
    })()
  );
});