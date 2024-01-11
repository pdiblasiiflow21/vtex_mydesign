Requerimientos:

-   PHP 7.4 o superior
-   MySQL 5.1.10 o superior
-   extensión mbstring
-   extensión intl
-   extensión curl

Instrucciones para la instalación

1. Copiar archivo de configuración
   cp config/app_local.example.php app_local.php

2. Editar los datos de acceso en archivo app_local.php

3. Importar el esquema de la base de dato dentro de config/schema/schema_db.sql

4. Existe un webhook que debe ser público que es usado por vtex. https://www.midominio.com/stores/hook-order

# Cronjob.

### Para configurar el cronjob, se debe ingresar en el servicio myapp-webserver y en la terminal ingresar

```
crontab -e
```

### luego agregar al final las siguientes dos instrucciones:

## Sincroniza las tarifas, transportistas, muelles y almacenes

```
0 * * * * touch /app/logs/sync.log && cd /app && /bin/sh bin/cake cronjob sync > /app/logs/sync.log 2>&1
```

## Informa a vtex los datos para el seguimiento. (esto es para mantener actualizado por si vtex no pudo informar por el webohook)

```
*/5 * * * * touch /app/logs/tracking.log && cd /app && /bin/sh bin/cake cronjob tracking > /app/logs/tracking.log 2>&1
```
