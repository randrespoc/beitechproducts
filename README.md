Éste proyecto es una prueba técnica

## Intalación

Para desplegar éste proyecto, por favor siga las siguientes intrucciones:

- Asegurese de tener instalado y configurado un servidor con la última versión de PHP y composer.
- Clone el proyecto en su servidor.
- Vaya al directorio raíz del proyecto.
- Ejecute el comando: composer update
- Ejecute el comando: php artisan key:generate
- Si el archivo .env no existe, creelo con el contenido que se encuentra en .env.example  .
- Edite el archivo .env con los datos adecuados de conexión a la base de datos.
- Para arrancar el servicio, ejecute el comando: php artisan serve
- Si todo funciona correctamente, podrá acceder al sitio en: http://127.0.0.1:8000/

## Servicio Web

En http://127.0.0.1:8000/ podrá encontrar una página web donde podrá consultar las ordenes disponibles en la base de datos.

## API

Podrá acceder a los servicios de la API en:

http://127.0.0.1:8000/api/

Los servicios disponibles son:

- /api/customer/ : (GET) Obtendrá el listado de clientes disponibles
- /api/customer/{id} : (GET) Obtiene los datos de un cliente dado por el id del mismo.
- /api/order/?customer_id={id}&date_ini={date_ini}&date_end={date_fin} : (GET) Obtiene el listado de órdenes disponibles para un cliente dado por su id, en un rango de fechas desde date_ini hasta date_fin.
- /api/order/?customer_id={id}&date_ini={date_ini}&date_end={date_fin} : (POST) Obtiene el listado de órdenes disponibles para un cliente dado por su id, en un rango de fechas desde date_ini hasta date_fin.
- /api/order/ : (POST) Ingresa una nueva orden al sistema con todas las validaciones necesarias.
    Éste servicio recibe 3 parámetros:
    - customer_id : (Integer) Es el id del cliente
    - delivery_address: (String) Es la dirección de envío al cliente.
    - products : (JSON) Estructura que contiene los datos de los productos a registrar, debe tener la siguiente estructura:
        [{"id":15,"quantity":20},{"id":10,"quantity":1}]
        Se trata de un arreglo con los datos de cada producto. Cada producto tiene la siguiente estructura:
        - id : (Integer) Es un id de producto válido almacenado en la base de datos.
        - quantity :  (Integer) Es la cantidad de productos a registrar.