# Sistema de Gestión para Salón de Belleza

## Descripción
Este proyecto es una aplicación web desarrollada en PHP 8.1 y CodeIgniter 4 para gestionar todas las operaciones de un salón de belleza.

## Características principales
- Autenticación y permisos de usuario (login local y Google OAuth).
- CRUD de clientes y empleados.
- Gestión de servicios y productos (inventario).
- Reservas y gestión de turnos (agendar, atender, finalizar, anular).
- Ventas de servicios y productos con generación de comprobantes PDF (Dompdf).
- Exportación de informes de ingresos a Excel (.xlsx) filtrables por mes y año (PhpSpreadsheet).
- Pago de nóminas y gestión de préstamos a empleados.
- Registro de egresos y movimientos de caja.
- Reportes de ganancias y actividades.
- Configuración de datos de la empresa.
- Interfaz moderna y responsive.

## Requisitos
- PHP >= 8.1 con extensiones: intl, mbstring, json, mysqlnd, curl.
- Composer.
- Servidor web (Apache o Nginx) con módulo PHP.
- Base de datos MySQL o MariaDB.

## Instalación
1. Clonar el repositorio:
   ```bash
   git clone https://github.com/hamintonjair/salon_belleza.git
   cd salon_belleza
   ```

2. Copiar `.env.example` a `.env` y configurar las variables:
   - `baseURL`
   - datos de la base de datos
   - credenciales de Google OAuth:
   ```dotenv
   # Entorno de la aplicación
   CI_ENVIRONMENT = development
   app.baseURL = http://localhost:8080/

   # Conexión a la base de datos
   database.default.hostname = localhost
   database.default.database = salon_belleza
   database.default.username = tu_usuario
   database.default.password = tu_contraseña
   database.default.DBDriver = MySQLi

   # Google OAuth
   GOOGLE_CLIENT_ID=tu_client_id
   GOOGLE_CLIENT_SECRET=tu_client_secret
   GOOGLE_REDIRECT_URI=tu_redirect_uri
   GOOGLE_REDIRECT_URI=http://localhost:8080/login/googleCallback
   - Obtén tus credenciales en la [Google Cloud Console](https://console.cloud.google.com/)   ```
3. Instalar dependencias:
   ```bash
   composer install
   composer require phpoffice/phpspreadsheet
   composer require google/apiclient
   ```
4. Ejecutar migraciones y seeders:
   ```bash
   php spark migrate
   php spark db:seed DatabaseSeeder
   ```
5. Iniciar servidor de desarrollo:
   ```bash
   php spark serve --host 0.0.0.0 --port 8080
   ```
6. Acceder en el navegador a `http://localhost:8080`.

## Uso
- Registrar una cuenta o iniciar sesión como usuario existente.
- Navegar por el panel para gestionar clientes, empleados, servicios, productos, turnos y finanzas.
- Generar comprobantes y exportar reportes según sea necesario.

## Credenciales de prueba

- Usuario: admin@gmail.com
- Contraseña: admin123
## Pruebas
Para ejecutar los tests unitarios:
```bash
composer test
```

## Contribuciones
Las contribuciones son bienvenidas:
1. Haz un fork del proyecto.
2. Crea una rama de feature o bugfix: `git checkout -b feature/nombre-descriptivo`.
3. Realiza tus cambios y haz commit: `git commit -m "Descripción del cambio"`.
4. Envía un Pull Request describiendo tus cambios.

## Licencia
Este proyecto está bajo la licencia MIT.
