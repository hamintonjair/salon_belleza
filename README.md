# Sistema de Gestión para Salón de Belleza

Este proyecto es una aplicación web desarrollada con PHP 8.1 y CodeIgniter 4 para gestionar un salón de belleza. Permite administrar usuarios, clientes, empleados, servicios, productos, turnos, ventas, préstamos, egresos, movimientos de caja y reportes.

## Características principales

- Autenticación y permisos de usuario.
- CRUD de clientes y empleados.
- Gestión de servicios y productos (inventario).
- Reservas y gestión de turnos (agendar, atender, anular, finalizar, atendiendo).
- Gestión de ventas de servicios y productos.
- Exportación de ingresos a Excel (.xlsx) filtrables por mes y año.
- Generación de comprobantes en PDF (Dompdf).
- Pago de nóminas de empleados con cálculo automático de préstamos descontados.
- Gestión de préstamos a empleados con historial y filtro de últimos 15 días.
- Registro y reporte de egresos (incluyendo préstamos, pagos de nómina y otros gastos).
- Movimientos de caja y control de saldo disponible.
- Reportes de ganancias y actividades.
- Configuración de datos de la empresa.
- Interfaz moderna y responsive.

## Requisitos

- PHP >= 8.1 con extensiones: intl, mbstring, json, mysqlnd, curl.
- Composer.

## Instalación

1. Clonar el repositorio:
   ```bash
   git clone https://github.com/hamintonjair/salon_belleza.git
   cd salon_belleza
   ```
2. Copiar `.env.example` a `.env` y configurar:
   - `baseURL`
   - datos de la base de datos
   - credenciales de Google OAuth:
     ```dotenv
     GOOGLE_CLIENT_ID=tu_client_id
     GOOGLE_CLIENT_SECRET=tu_client_secret
     GOOGLE_REDIRECT_URI=tu_redirect_uri
     ```
   - Obtén tus credenciales en la [Google Cloud Console](https://console.cloud.google.com/)
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
5. Iniciar servidor:
   ```bash
   php spark serve
   ```
6. Acceder en el navegador a `http://localhost:8080`.

## Credenciales de prueba

- Usuario: admin@gmail.com
- Contraseña: admin123

## Licencia

MIT
