# Proyecto: API Real Estate 🏛️🏛️🏛️

## Descripción
Este proyecto es una API RESTful para gestionar propiedades inmobiliarias. Permite realizar operaciones CRUD (Crear, Leer, Actualizar y Eliminar) sobre propiedades y clientes, así como gestionar las solicitudes de visitas y relacionarla entre estas entidades. El proyecto está construido con **Laravel**, tiene test unitarios a los endpoints con PHPUnit y posee seguridad basada en apikey (para los endpoints)

## Características
- Gestión de propiedades inmobiliarias✅
- Gestión de personas✅
- Gestión de visitas✅
- Relaciones entre entidades (propiedades, agentes, clientes)✅
- Seguridad con API Key✅
- Endpoints CRUD✅
- Pruebas unitarias✅

## Requisitos Previos
- **PHP >= 8.x**
- **Composer**
- **Laravel >= 9.x**
- **MySQL o cualquier otro sistema de bases de datos compatible con Laravel**

## Configuración del Entorno de Desarrollo

1. Clona el repositorio en tu máquina local:🖥️
    -
    ```
    git clone https://github.com/GuillermoReyesC/laravel-real-estate-api
    o bien crea un nuevo proyecto
    composer create-project --prefer-dist laravel/laravel nombre_proyecto 
    ```

3.  entra al directorio del proyecto⌨️
    -
    ```
    cd nombre_proyecto
    ```
4.  Instala las dependencias de composer⌨️
    -
    ```
    -composer install
    ```
5.  configura tu archivo env⌨️
    -
    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=real_state_db
    DB_USERNAME=root
    DB_PASSWORD=


    ```
6.  Creamos migraciones y models para las entidades⌨️
    -
    ```
    php artisan make:model Propiedad -m
    php artisan make:model Persona -m
    php artisan make:model SolicitudVisita -m
    ```
7.  Definir correctamente los datos de las migraciones para las tablas a crear⌨️
    -
8.  ejecutar migraciones con:⌨️
    -
    ```
    php artisan migrate
    ```
9.  definimos los modelos y relaciones en el model⌨️
    -
10. creamos controladores de tipo resource para mayor accesibilidad⌨️
    -
    ```
    php artisan make:controller PropiedadController --resource
    php artisan make:controller PersonaController --resource
    php artisan make:controller SolicitudVisitaController --resource
    ```
11. Definimos las rutas en routes/api.php⌨️
    -
   
12. A Codear!👨‍💻👩‍💻
    -
    asegurarnos de agregar lo necesario a los modelos, migraciones y las vistas .blade

    Asegúrate de definir correctamente los atributos y relaciones en los modelos.
    Ajusta las migraciones si es necesario para reflejar correctamente las entidades y relaciones.
    Si usas vistas blade, crea o modifica las vistas en resources/views.

    recuerda agregar el codigo necesario para que funcione tu controller con el blade.
    en este caso creé 3 ramas por cada entidad y al finar les hice merge a Master

13. Implementar ApiKey:🔒
    -

    -crea el middleware para implementar
    ```
       php artisan make:middleware ValidateApiKey
    ```
    -Implementar la Lógica del Middleware:

    Abre el archivo creado (ValidateApiKey.php) y modifica su contenido para validar la API Key.

    -debes registrar el apikey en kernel.php
    ```
        protected $routeMiddleware = [
        // Otros middlewares...
        'validate.api.key' => \App\Http\Middleware\ValidateApiKey::class,
        ];
    ```
    -Aplicar el middleware a las rutas necesarias.
    
    -agregar tu apikey al archivo .env
    
        
        API_KEY=###############################

    -agrega el apikey a tus solicitudes fetch para poder autenticarte y usar los endpoints.

 
14. Pruebas unitarias  con PHPUnit 🧪🧪
    -

    -primero debes crear las pruebas necesarias:
    
    ```
       php artisan make:test Api/PersonaTest
       php artisan make:test Api/PropiedadTest
       php artisan make:test Api/SolicitudVisitaTest
    ```

    -abre tu archivo creado en la carpeta 'tests/Feature/Api/nombreTest.php'

    -escribe tus pruebas necesarias en los archivos de prueba

    -recuerda instanciar correctamente los modelos en donde sea necesario.
    
    -crear las factories que usará cada entidad en los test.
     ```
       php artisan make:factory PersonaFactory --model=Persona
       php artisan make:factory PropiedadFactory --model=Propiedad
       php artisan make:factory SolicitudVisitaFactory --model=SolicitudVisita
    ```
    -Recuerda tambien definir los atributos predeterminados para cada modelo:
    por ejemplo, en persona seria algo asi el ejemplo

    ```php
      <?php

        namespace Database\Factories;

        use App\Models\Persona;
        use Illuminate\Database\Eloquent\Factories\Factory;

        class PersonaFactory extends Factory
        {
            protected $model = Persona::class;

            public function definition()
            {
                return [
                    'nombre' => $this->faker->name,
                    'email' => $this->faker->unique()->safeEmail,
                    'telefono' => $this->faker->phoneNumber,
                ];
            }
        }
    ```
    -verifica que los modelos esten usando las fabricas correspondientes a su test.
    cada modelo debe tener el use de HasFactory:

    ``` php
       use Illuminate\Database\Eloquent\Factories\HasFactory;
    ```

    -ejecutar pruebas unitarias:

    -ejecuta en la consola:
    
    ```
    ./vendor/bin/phpunit
    ```
    -deberian ejecutarse las pruebas a los endpoints.

15. Bonus: Configurar Swagger 🚀🚀
    -    
    -Instalar dependencia <b>l5-swagger</b>

    ```
    composer require "darkaonline/l5-swagger"
    ```

    -publicar archivos de configuracion, para poder personalizar swagger

    ```
    php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
    ```

    -configurar el archivo <b>.env</b>:
     ```
     L5_SWAGGER_GENERATE_ALWAYS=true
     L5_SWAGGER_CONST_HOST=http://localhost:8000
     ```
    -se deben configurar las rutas y controladores con anotaciones en el codigo, este es un ejemplo de como se deberia ver:

    ```php
    /**
    * @OA\Get(
    *     path="/api/propiedades",
    *     summary="Obtener todas las propiedades",
    *     tags={"Propiedades"},
    *     @OA\Response(
    *         response=200,
    *         description="Lista de propiedades",
    *         @OA\JsonContent(
    *             type="array",
    *             @OA\Items(ref="#/components/schemas/Propiedad")
    *         )
    *     )
    * )
    */
    public function index()
    {
        // Tu lógica aquí
    }
    ```

    -para generar la documentacion de swagger se ejecuta lo siguente, cada vez que se actualice la info.
    ```
    php artisan l5-swagger:generate
    ```

    Espero que esto sea de utilidad. Gracias por llegar hasta aquí 🙂