# Práctica 7 - Aplicación web con Dockerfile y Docker Compose

**Yauge Pedro Volquez Terrero**

En esta práctica hice una página sencilla en PHP conectada a MySQL. Usé Docker Compose para levantar la aplicación y la base de datos, cada una en su propio contenedor.

## Cómo la hice

Primero creé la carpeta `src` y dentro coloqué el archivo `index.php`. La página consulta los mensajes guardados en la tabla `mensajes` y los muestra junto con la versión del servidor MySQL.

Después preparé el `Dockerfile` con la imagen `php:8.3-apache`. Le instalé la extensión `pdo_mysql` para poder hacer la conexión desde PHP.

En el archivo `docker-compose.yml` dejé configurados los dos servicios:

- `web`: construye la imagen usando el Dockerfile y permite abrir la página desde el puerto 8080. También monta la carpeta `src`, así puedo modificar el código y ver los cambios sin reconstruir la imagen.
- `db`: utiliza MySQL 8.0. Configuré el nombre de la base de datos, el usuario y las contraseñas mediante variables de entorno. Para conservar los datos, usé un volumen.

Agregué un healthcheck para que el servicio web espere hasta que MySQL esté listo. También dejé el archivo `db/init.sql`, que crea la tabla e inserta un mensaje cuando se inicializa la base de datos por primera vez.

## Cómo ejecutar el proyecto

Para descargarlo y levantar los contenedores:

```bash
git clone https://github.com/yaugepedro/Practica-7-Practica-Web-App-con-Dockerfile-y-Docker-Compose.git
cd Practica-7-Practica-Web-App-con-Dockerfile-y-Docker-Compose
docker compose up -d --build
```

Luego abre http://localhost:8080 en el navegador. La primera vez puede tardar un poco mientras MySQL termina de prepararse.

Para detener y eliminar los contenedores:

```bash
docker compose down
```

Los datos quedan guardados en el volumen. Si quieres borrarlos también y empezar de nuevo, ejecuta:

```bash
docker compose down -v
```
