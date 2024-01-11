## Iniciar

    ```
    /bin/bash bin/cake cronjob tracking > /dev/null
    ```

## Pasos

1. Obtengo un array con todas las tiendas de la tabla stores.
2. Obtengo un array con los ids de las orders.
3. Por cada tienda:
   3.1. Obtiene todas las ordenes de la tienda
   3.2. Con esa info recorre el array de ids de orders y se fija si en bbdd ya esta registrada, si es asi loguea `El pedido #12345 ya ha sido informado.`
   3.3. Obtiene los datos de la orden usando su order_id.
   3.4. Con la informacion de la tienda y los datos de la orden.
   3.4.1. Si la orden tiene nro de factura ($order_vtex['packageAttachment']['packages'][0]['invoiceNumber']) loguea `La orden #12345 no tiene factura`.
   3.4.2. Crea la orden usando la api de iflow.
   3.4.3. Con los datos de la creacion de la orden de la api de iflow, informa el seguimiento (tracking) usando la api de vtex.
   3.4.4.
