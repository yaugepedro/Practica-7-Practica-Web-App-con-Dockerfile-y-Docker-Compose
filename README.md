\# Práctica 7 - Aplicación web con Dockerfile y Docker Compose



Yauge Pedro Volquez Terrero



Aplicación web en PHP que se conecta a una base de datos MySQL. Los dos servicios corren en contenedores con Docker Compose.



\## Lo que hice



Creé una carpeta `src` con un `index.php`. Esa página se conecta a MySQL, lee un mensaje de la tabla `mensajes` y lo muestra en pantalla junto con la versión del servidor.



Para el servidor de aplicación hice un `Dockerfile` que parte de la imagen `php:8.3-apache` e instala la extensión `pdo\_mysql`, que es la que permite que PHP se comunique con MySQL.



En el `docker-compose.yml` definí dos servicios:



\- `web`: construye la imagen de PHP con el Dockerfile, publica el puerto 8080 y monta mi carpeta `src` dentro del contenedor.

\- `db`: usa la imagen `mysql:8.0`, con el nombre de la base de datos, el usuario y las contraseñas como variables de entorno. Los datos se guardan en un volumen para que no se pierdan al apagar el contenedor.



El servicio `web` espera a que MySQL esté listo antes de arrancar, usando un healthcheck. El archivo `db/init.sql` crea la tabla y el mensaje la primera vez que se inicia la base de datos.



\## Cómo probarlo



```

git clone https://github.com/yaugepedro/hola-mundo-php-mysql.git

cd hola-mundo-php-mysql

docker compose up -d --build

```



Abrir http://localhost:8080. MySQL tarda unos segundos en arrancar la primera vez.



Para apagarlo:



```

docker compose down

```



Para borrar también los datos de la base de datos:



```

docker compose down -v

```

