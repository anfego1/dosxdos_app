//asignar un nombre y versión al cache
const CACHE_NAME = 'dosxdos1',
  urlsToCache = [
    'http://localhost/dosxdos_app/index.html?utm_source=web_app_manifest',
    'http://localhost/dosxdos_app/index.html',
    'http://localhost/dosxdos_app/manifest.json',
    'http://localhost/dosxdos_app/serviceworker.js',
    'http://localhost/dosxdos_app/sw.js',
    'http://localhost/dosxdos_app/rutas_montador.html',
    'http://localhost/dosxdos_app/ruta_montador.html',
    'http://localhost/dosxdos_app/linea_montador.html',
    'http://localhost/dosxdos_app/fotos_y_firmas.html',
    'http://localhost/dosxdos_app/ot_completa.html',
    'http://localhost/dosxdos_app/ot.html',
    'http://localhost/dosxdos_app/css/fuentes/Roboto/Roboto-Light.ttf',
    'http://localhost/dosxdos_app/css/fuentes/Merriweather/Merriweather-Light.ttf',
    'http://localhost/dosxdos_app/css/fuentes/Merriweather/Merriweather-Bold.ttf',
    'http://localhost/dosxdos_app/css/fuentes/Lora/Lora-Regular.ttf',
    'http://localhost/dosxdos_app/css/fuentes/Lora/Lora-Medium.ttf',
    'http://localhost/dosxdos_app/css/fuentes/Lora/Lora-Bold.ttf',
    'http://localhost/dosxdos_app/img/candado.png',
    'http://localhost/dosxdos_app/img/casa.png',
    'http://localhost/dosxdos_app/img/casaWhite.png',
    'http://localhost/dosxdos_app/img/dosxdos.png',
    'http://localhost/dosxdos_app/img/email.png',
    'http://localhost/dosxdos_app/img/flechaAbajo.png',
    'http://localhost/dosxdos_app/img/fondo.jpg',
    'http://localhost/dosxdos_app/img/logoPwa1024.png',
    'http://localhost/dosxdos_app/img/logoPwa512.png',
    'http://localhost/dosxdos_app/img/logoPwa384.png',
    'http://localhost/dosxdos_app/img/logoPwa256.png',
    'http://localhost/dosxdos_app/img/logoPwa192.png',
    'http://localhost/dosxdos_app/img/logoPwa128.png',
    'http://localhost/dosxdos_app/img/logoPwa96.png',
    'http://localhost/dosxdos_app/img/logoPwa64.png',
    'http://localhost/dosxdos_app/img/logoPwa32.png',
    'http://localhost/dosxdos_app/img/logoPwa16.png',
    'http://localhost/dosxdos_app/img/logo300.png',
    'http://localhost/dosxdos_app/img/lupa.png',
    'http://localhost/dosxdos_app/img/reloj.png',
    'http://localhost/dosxdos_app/img/relojWhite.png',
    'http://localhost/dosxdos_app/img/usuario.png',
    'http://localhost/dosxdos_app/img/rutasWhite.png',
    'http://localhost/dosxdos_app/img/cerrar.png',
    'http://localhost/dosxdos_app/img/usuarios.png',
    'http://localhost/dosxdos_app/img/trash.png',
    'http://localhost/dosxdos_app/img/folder.png',
    'http://localhost/dosxdos_app/img/comprimido.png',
    'http://localhost/dosxdos_app/img/task.png',
    'http://localhost/dosxdos_app/img/work.png',
    'http://localhost/dosxdos_app/css/cdn_data_tables.css',
    'http://localhost/dosxdos_app/js/jquery.js',
    'http://localhost/dosxdos_app/js/data_tables.js',
    'http://localhost/dosxdos_app/js/cdn_data_tables.js',
    'http://localhost/dosxdos_app/js/index_db.js',
    'http://localhost/dosxdos_app/img/tienda.png',
    'http://localhost/dosxdos_app/img/clientes.png',
    'http://localhost/dosxdos_app/img/editar.png',
    'http://localhost/dosxdos_app/img/archivar.png',
    'http://localhost/dosxdos_app/img/back.png',
    'http://localhost/dosxdos_app/img/visible.png',
    'http://localhost/dosxdos_app/img/no_visible.png',
    'http://localhost/dosxdos_app/img/crear.png',
    'http://localhost/dosxdos_app/img/logo_clientes.png',
    'http://localhost/dosxdos_app/js/index_db.js',
    'http://localhost/dosxdos_app/img/logo2930.png',
    'http://localhost/dosxdos_app/img/logo_clientes.png',
    'http://localhost/dosxdos_app/img/instalar.png',
    'http://localhost/dosxdos_app/espanol.json',
    'http://localhost/dosxdos_app/english.json',
    'http://localhost/dosxdos_app/pv.html',
    'http://localhost/dosxdos_app/crear_pv.html',
    'http://localhost/dosxdos_app/editar_pv.html',
    'http://localhost/dosxdos_app/editar_pv.html',
    'http://localhost/dosxdos_app/js/fixed_header.js',
    'http://localhost/dosxdos_app/css/fuentes/Futura/Futura_Bold.otf',
    'http://localhost/dosxdos_app/css/fuentes/Futura/Futura_Light.otf',
    'http://localhost/dosxdos_app/css/fuentes/Futura/Futura_Medium.otf',
    'http://localhost/dosxdos_app/img/alerta.png',
    'http://localhost/dosxdos_app/img/saludo.png',
    'http://localhost/dosxdos_app/img/dm.png',
    'http://localhost/dosxdos_app/img/papelera.png',
    'http://localhost/dosxdos_app/horarios.html',
    'http://localhost/dosxdos_app/lineas_ot.html',
    'http://localhost/dosxdos_app/lineas.html',
    'http://localhost/dosxdos_app/usuarios_oficina.html',
    'http://localhost/dosxdos_app/dm.html',
    'http://localhost/dosxdos_app/reciclar.html',
    'http://localhost/dosxdos_app/rutas_inactivas.html',
    'http://localhost/dosxdos_app/img/trabajos.png',
    'http://localhost/dosxdos_app/img/clientes2.png',
    'http://localhost/dosxdos_app/img/sincronizar.png',
  ],

  urlsToUpdate = [
    'http://localhost/dosxdos_app/index.html?utm_source=web_app_manifest',
    'http://localhost/dosxdos_app/index.html',
    'http://localhost/dosxdos_app/manifest.json',
    'http://localhost/dosxdos_app/serviceworker.js',
    'http://localhost/dosxdos_app/sw.js',
    'http://localhost/dosxdos_app/rutas_montador.html',
    'http://localhost/dosxdos_app/ruta_montador.html',
    'http://localhost/dosxdos_app/linea_montador.html',
    'http://localhost/dosxdos_app/fotos_y_firmas.html',
    'http://localhost/dosxdos_app/ot_completa.html',
    'http://localhost/dosxdos_app/ot.html',
    'http://localhost/dosxdos_app/espanol.json',
    'http://localhost/dosxdos_app/english.json',
    'http://localhost/dosxdos_app/pv.html',
    'http://localhost/dosxdos_app/crear_pv.html',
    'http://localhost/dosxdos_app/editar_pv.html',
    'http://localhost/dosxdos_app/js/index_db.js',
    'http://localhost/dosxdos_app/img/logoPwa1024.png',
    'http://localhost/dosxdos_app/img/logoPwa512.png',
    'http://localhost/dosxdos_app/img/logoPwa384.png',
    'http://localhost/dosxdos_app/img/logoPwa256.png',
    'http://localhost/dosxdos_app/img/logoPwa192.png',
    'http://localhost/dosxdos_app/img/logoPwa128.png',
    'http://localhost/dosxdos_app/img/logoPwa96.png',
    'http://localhost/dosxdos_app/img/logoPwa64.png',
    'http://localhost/dosxdos_app/img/logoPwa32.png',
    'http://localhost/dosxdos_app/img/logoPwa16.png',
    'http://localhost/dosxdos_app/img/saludo.png',
    'http://localhost/dosxdos_app/horarios.html',
    'http://localhost/dosxdos_app/lineas_ot.html',
    'http://localhost/dosxdos_app/lineas.html',
    'http://localhost/dosxdos_app/usuarios_oficina.html',
    'http://localhost/dosxdos_app/dm.html',
    'http://localhost/dosxdos_app/reciclar.html',
    'http://localhost/dosxdos_app/rutas_inactivas.html',
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