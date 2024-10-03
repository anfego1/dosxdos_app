# PWA para DOSPORDOS GRUPO IMAGEN S.L.

Se ha desarrollado desarrollado en la fase 2 y se ha desplegado a producción la aplicación alojada en los dominios dosxdos.app y dosxdos.app.iidos.com La cual se ha integrado con el CRM y el ERP. La aplicación es una PWA (Aplicación Web Progresiva) que está siendo desarrollada debido a la necesidad de la empresa DOSPORDOS GRUPO IMAGEN S.L. de administrar los datos de la ejecución de lo servicios de montaje y la evidencia fotográfica de los mismos (sin pérdidas de calidad), lo cual requiere para su flujo de trabajo una interfaz de usuario muy personalizada, funcionamiento sin conexión a internet, y gran capacidad de almacenamiento.

La aplicación dosxdos.app (https://dosxdos.app) se preparó para ser multiplataforma e instalable en cualquier sistema operativo (mobile o de escritorio), así mismo se desarrolló funcionamiento estable sin conexión a internet muy debido a las condiciones adversas de la conexión en el contexto laboral de los usuarios. 

El Backend fue desarrollado como una APIRESTFULL que permite la integración de la aplicación con cualquier otro software o desarrollo futuro. Estamos escribiendo actualmente la documentación de la API para futuros usos.

## Características
Funcionamiento ONLINE y OFFLINE a través de SERVICE WORKER personalizado para manejar una CACHÉ persistente que se actualiza si hay cambios en el código fuente, lo cual asegura 100% el funcionamiento OFFLINE actualizado.

Experiencia y administración de USUARIOS. Se desarrollan tres interfaces de usuario, que a su vez se personalizaron y sirven a distintos tipos de usuarios: Administrador, Cliente, Oficinas, Montadores, etc.

Integración con ZOHO CRM y ZOHO BOOKS a través de las API.

Gestión de datos de ejecución de servicios.

Almacenamiento y administración de la evidencia fotográfica de los montajes realizados.

Almacenamiento y administración de la evidencia de la firma de los montajes realizados.

No alteración de la calidad de las imágenes tomadas.

Creación y administración de comprimidos (sin pérdida de calidad) de las evidencias para la descarga del cliente final.

Bases de datos MYSQL relacionables sincronizadas al ERP Y CRM.

Uso de INDEXDB y STORAGE en el cliente, para la creación de bases de datos sincronizadas en el cliente.

Inicialmente la aplicación fue desplegada en VPS (servidor virtual privado) que se configuró (NGINX) para las necesidades de la aplicación.

Posteriormente la aplicación fue desplegada en el servidor dedicado de la empresa, el cual se configuró (Instancia o máquina virtual con APACHE) también para las necesidades de la aplicación.

Lenguajes y tecnologías usadas en dosxdos.app: PHP con el uso de la dependencia PHP MAILER, JAVASCRIPT con el uso de la dependecia DATATABLES, HTML5, CSS3, SCSS, MYSQL, APACHE, NGINX.

Los archivos siguientes deben ser reemplazados con los datos de la cuenta de ZOHO, y renombrados removiendo "_example" del nombre:
apirest/clases/cliente_zoho.json
apirest/clases/code_zoho.json
apirest/clases/conexion.json
apirest/clases/tokens_zoho.json

Instalar las dependecias del archivo composer.json a través de composer.
