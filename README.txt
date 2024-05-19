Webapp Pokémon

Descripción
-----------
Esta es una webapp simple desarrollada en Laravel que permite al usuario buscar un Pokémon y ver sus habilidades en español. La aplicación guarda el historial de búsqueda del usuario, mostrando las últimas 10 búsquedas exitosas.

Características
---------------
- Búsqueda de Pokémon a través de la API de Pokémon (https://pokeapi.co/).
- Visualización de habilidades del Pokémon en español.
- Historial de búsqueda de las últimas 10 búsquedas exitosas, específico por sesión.
- Capacidad de volver a buscar desde el historial con un solo clic.
- Actualización de resultados y del historial sin refrescar la página.

Requisitos
----------
- PHP >= 8.0
- Composer
- Laravel >= 10.x
- MySQL o cualquier otra base de datos soportada por Laravel (phpMyAdmin)

Instalación
-----------
1. Clonar el repositorio:

    git clone https://github.com/melitacasola/webapp-pokemon.git
    cd webapp-pokemon

2. Instalar las dependencias de PHP usando Composer:

    composer install

3. Configurar el archivo `.env`:

    Copiar el archivo `.env.example` a `.env` y configurar las variables de entorno, especialmente las relacionadas con la base de datos:

    cp .env.example .env

    Configurar las siguientes variables de entorno en el archivo `.env`:

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=pokeApi
    DB_USERNAME=root
    DB_PASSWORD=



4. Generar la clave de la aplicación:

    php artisan key:generate

5. Migrar la base de datos:

    php artisan migrate

6. Iniciar el servidor de desarrollo:

    php artisan serve

    Ahora puedes acceder a la aplicación en `http://localhost:8000/buscar`.

Uso
---
- Ingresa un término de búsqueda en el campo de búsqueda y presiona Enter o haz clic en el botón de búsqueda.
- Los resultados se mostrarán sin recargar la página.
- El historial de las últimas 10 búsquedas exitosas se mostrará debajo del campo de búsqueda.
- Haz clic en cualquier término del historial para realizar una búsqueda de nuevo.

Tecnologías Utilizadas
----------------------
- Laravel v.10.x
- JavaScript (para las llamadas AJAX y la actualización de la vista sin recargar)
- MySQL (o cualquier otra base de datos soportada por Laravel)

Consideraciones adicionales
----------------------------
Durante el desarrollo, se solicitó usar un proxy en el backend para realizar las llamadas a la API de Pokémon, mejorando la seguridad y permitiendo el manejo de errores de forma centralizada.

Autor
-----
Melissa Casola
