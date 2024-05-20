# Webapp Pokémon

## Descripción

Esta es una webapp simple desarrollada en Laravel que permite al usuario buscar un Pokémon y ver sus habilidades en español. La aplicación guarda el historial de búsqueda del usuario, mostrando las últimas 10 búsquedas exitosas.

## Características

- Búsqueda de Pokémon a través de la API de Pokémon (https://pokeapi.co/).
- Visualización de habilidades del Pokémon en español.
- Historial de búsqueda de las últimas 10 búsquedas exitosas, específico por sesión.
- Capacidad de volver a buscar desde el historial con un solo clic.
- Actualización de resultados y del historial sin refrescar la página.

## Requisitos

- PHP >= 8.0
- Composer
- Laravel >= 10.x
- MySQL o cualquier otra base de datos soportada por Laravel

## Instalación

1. Clonar el repositorio:

    ```sh
    git clone https://github.com/melitacasola/webapp-pokemon.git
    cd webapp-pokemon
    ```

2. Instalar las dependencias de PHP usando Composer:

    ```sh
    composer install
    ```

3. Configurar el archivo `.env`:

    Copiar el archivo `.env.example` a `.env` y configurar las variables de entorno, especialmente las relacionadas con la base de datos:

    ```sh
    cp .env.example .env
    ```

    Configurar las variables de entorno en el archivo `.env`.
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=pokeApi
    DB_USERNAME=root
    DB_PASSWORD=

4. Generar la clave de la aplicación:

    ```sh
    php artisan key:generate
    ```

5. Migrar la base de datos:

    ```sh
    php artisan migrate
    ```

6. Instalar las dependencias de Node.js y compilar los assets:

    ```sh
    npm install
    
    ```
    (npm run dev no será necesaria si posees Laravel =< 10.0.0)

7. Iniciar el servidor de desarrollo:

    ```sh
    php artisan serve
    ```

    Ahora puedes acceder a la aplicación en `http://localhost:8000/buscar`.

## Uso

- Ingresa un término de búsqueda en el campo de búsqueda y presiona Enter o haz clic en el botón de búsqueda.
- Los resultados se mostrarán sin recargar la página.
- El historial de las últimas 10 búsquedas exitosas se mostrará debajo del campo de búsqueda.
- Haz clic en cualquier término del historial para realizar una búsqueda de nuevo.

## Tecnologías Utilizadas

- Laravel
- JavaScript (para las llamadas AJAX y la actualización de la vista sin recargar)
- MySQL (o cualquier otra base de datos soportada por Laravel)
- TailwindCSS

## Consideraciones adicionales

Durante el desarrollo, se decidió usar un proxy en el backend para realizar las llamadas a la API de Pokémon, mejorando la seguridad y permitiendo el manejo de errores de forma centralizada.

Además, he agregado en el método procesarBusqueda del PokemonController, una verificación antes de guardar la búsqueda en la base de datos, si un término de búsqueda ya existe en el historial para la sesión actual, se devuelve un mensaje indicando que la búsqueda ya ha sido realizada y no se guarda de nuevo en la base de datos. Esto evitará que el historial se llene con búsquedas repetidas.

    
    $existingSearch = SearchHistory::where('term', $term)
                ->where('session_id', $sessionId)
                ->first();

            if ($existingSearch) {
                return response()->json(['message' => 'Esta búsqueda ya ha sido realizada.'], 200);
            }
   
    
Si esta parte del codigo se comenta/borra - se guardan todos los términos de búsqueda exitosas de esa SESSION_ID sin importar si ya ha sido realizada la misma con anterioridad...

---

## Autor

Melissa Casola

