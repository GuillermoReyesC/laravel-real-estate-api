# Proyecto: API Real Estate

## Descripción
Este proyecto es una API RESTful para gestionar propiedades inmobiliarias. Permite realizar operaciones CRUD (Crear, Leer, Actualizar y Eliminar) sobre propiedades y clientes, así como gestionar las relaciones entre estas entidades. El proyecto está construido con **Laravel**, y la mantenemos una seguridad básica a través de una API Key.

## Características
- Gestión de propiedades inmobiliarias
- Gestión de personas
- Gestión de visitas
- Relaciones entre entidades (propiedades, agentes, clientes)
- Seguridad con API Key
- Endpoints CRUD

## Requisitos Previos
- **PHP >= 8.x**
- **Composer**
- **Laravel >= 9.x**
- **MySQL o cualquier otro sistema de bases de datos compatible con Laravel**

## Configuración del Entorno de Desarrollo

1. Clona el repositorio en tu máquina local:
   ´´
        git clone https://github.com/GuillermoReyesC/laravel-real-estate-api
        o bien crea un nuevo proyecto
        composer create-project --prefer-dist laravel/laravel nombre_proyecto´´´
3.  cd nombre_proyecto
4.  composer install
5.  configura tu archivo env
6.  creamos migraciones y models para las entidades:
        php artisan make:model Propiedad -m
        php artisan make:model Persona -m
        php artisan make:model SolicitudVisita -m
7.  debemos definir correctamente los datos de las migraciones para las tablas a crear.
8.  ejecutar migraciones con:
        php artisan migrate
9.  definimos los modelos y relaciones en el model

10. creamos controladores de tipo resource para mayor accesibilidad

   php artisan make:controller PropiedadController --resource
   php artisan make:controller PersonaController --resource
   php artisan make:controller SolicitudVisitaController --resource

   Definimos las rutas en routes/api.php
   

   asegurarnos de  agregar lo necesario a los modelos, migraciones y las vistas .blade

   Asegúrate de definir correctamente los atributos y relaciones en los modelos.
    Ajusta las migraciones si es necesario para reflejar correctamente las entidades y relaciones.
    Si usas vistas blade, crea o modifica las vistas en resources/views.
