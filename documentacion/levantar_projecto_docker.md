1. Definir credenciales de bbdd

    - .env definir

        ```
        APP_PORT=82

        FORWARD_DB_PORT=3309
        DB_DATABASE=iflow-vtex
        DB_USERNAME=my_user
        DB_PASSWORD=password
        ```

    - setear las mismas credenciales de bbdd manualmente en app_local.php
        ```
        'host' => 'mysql',
        'username' => 'my_user',
        'password' => 'password',
        'database' => 'my_app',
        ```

2. Crear las tablas de bbdd manualmente
    - En el servicio myapp-mysql registrarse con la mismo usuario y contraseña:
        ```
        mysql -u my_user -p
        ```
    - Pegar el contenido del archivo config/schema/schema_db.sql
3. Levantar el contenedor con los servicios:
    ```
    docker-compose up -d
    ```
4. La app corre en el puerto del .env y la base de datos en el puerto seteado en el mismo archivo
    ```
    http://localhost:82
    ```
5. Bajar el contenedor
    ```
    docker-compose down
    ```
6. Para pegarle a testing cambiamos en app_local:

    ```
    'url_quotations' => 'https://tn.iflow21.com/tmp/ws_rate_vtex.php',
    'host_tracking' => 'https://test-tracking.iflow21.com',
    'host_api' => 'https://test-api.iflow21.com'`,

    // agregado para una sola tienda
    'api_client' => 'Cliente_test',
    'api_key'    => '123456',
    ```
