# PYME Reservas -- Sistema Web de Gestion de Servicios y Reservas
## Caso de estudio: Barberia y Perfumeria JyM, Cartago (Valle del Cauca)

Proyecto de grado desarrollado para optar al titulo de Tecnologo en Gestion
de Sistemas de Informacion -- COTECNOVA.

**Autor:** Brandon Cortes  
**Asesor:** Jhon James Cano Sanchez  
**Metodologia:** RAD (Rapid Application Development)

---

## Descripcion

Sistema web para la administracion de citas, servicios, productos y personal
de un establecimiento de barberia. Resuelve el problema de los cruces de citas
(doble asignacion en el mismo horario), centraliza la informacion de clientes
y empleados, y ofrece un portal propio para que los clientes reserven sin
necesidad de contacto directo.

El diseno esta orientado a negocios pequenos del sector servicios que trabajen
por reserva, aunque el caso de estudio concreto es la Barberia y Perfumeria JyM.

---

## Enlace en Produccion

La aplicacion se encuentra desplegada y en funcionamiento en:

https://pyme-reservas.onrender.com/

---

## Funcionalidades Implementadas

**Reservas (Agendamiento Inteligente)**
- Validacion en tiempo real de disponibilidad por barbero mediante Fetch API (evitando cruces o doble asignacion).
- Calculo automatico de la duracion de la reserva segun los servicios seleccionados.
- Creacion de citas para servicios individuales o multiples.
- Historial de citas y seguimiento de estados (Pendiente, Confirmada, Completada, Cancelada, No Asistio).
- Automatizacion de estados vencidos mediante tarea programada (Cron Job).

**Comercio Electronico e Inventario**
- Catalogo de productos (perfumeria) visible en el portal publico.
- Carrito de compras funcional con generacion de pedidos.
- Descuento y restauracion de inventario automatizado en tiempo real basado en el estado del pedido.

**Gestion de Usuarios y Roles (RBAC)**
- Tres niveles de acceso: Administrador (Acceso Total), Empleado (Agenda Personal) y Cliente (Historial Privado).
- Validacion de dominio corporativo (@pymereservas.com) para cuentas de Administrador y Empleado.
- Panel de perfil para la gestion de datos personales (telefono, direccion) y subida de fotos de perfil.

**Autenticacion en Dos Pasos (2FA)**
- Verificacion de identidad mediante codigo OTP (One-Time Password) de 6 digitos al iniciar sesion.

**Notificaciones en Tiempo Real**
- Sistema de notificaciones push mediante WebSockets (Pusher) para eventos de reservas, pedidos y cambios de estado.
- Campana de notificaciones con contador de no leidas y panel desplegable.

**Gestion de Turnos y Horarios**
- Configuracion individual de dias laborables y horas de trabajo por empleado.
- Proteccion de agenda: el sistema impide desactivar un dia si el empleado tiene citas futuras programadas.

**Dashboard y Reportes**
- Panel de indicadores (KPIs) diferenciado por rol (Administrador y Empleado).
- Graficas de evolucion de ingresos mensuales y distribucion de estados de citas.
- Exportacion de datos a PDF en todos los modulos principales.

---

## Stack Tecnologico

| Componente | Tecnologia |
|---|---|
| Back-end | Laravel 13.x (PHP 8.3+) |
| Base de datos (Local) | MySQL 8.0 |
| Base de datos (Produccion) | PostgreSQL 15 |
| Front-end | Laravel Blade, Tailwind CSS 3.x, Alpine.js |
| Empaquetador de assets | Vite 8.x |
| Autenticacion | Laravel Breeze + OTP (2FA) |
| Tiempo real | Pusher (WebSockets) |
| Reportes PDF | Laravel DomPDF |
| API REST | JWT Auth (tymon/jwt-auth) |
| Documentacion API | Scramble |
| Infraestructura local | Docker Compose (Laravel Sail) sobre WSL2 Ubuntu |
| Hosting produccion | Render (PaaS) |

---

## Estructura del Proyecto

```
pyme-reservas/
  app/
    Console/Commands/      -- Comandos Artisan personalizados (Cron)
    Http/Controllers/      -- Controladores por modulo
    Http/Middleware/        -- Middleware de verificacion de roles
    Mail/                  -- Plantillas de correo (OTP, notificaciones)
    Models/                -- Modelos Eloquent (User, Reservation, Product, etc.)
    Notifications/         -- Notificaciones push (Pusher)
  database/
    migrations/            -- Migraciones de la base de datos
    seeders/               -- Datos de prueba parametricos
  resources/views/         -- Vistas Blade organizadas por modulo
  routes/
    web.php                -- Rutas web con middleware de roles
    api.php                -- Rutas API REST y endpoint del Cron Job
  public/
    cron.php               -- Endpoint independiente para tarea programada
```

---

## Instrucciones de Instalacion Local

1. **Clonar repositorio y preparar variables de entorno:**
   ```bash
   git clone https://github.com/Brandon07-code/pyme-reservas.git
   cd pyme-reservas
   cp .env.example .env
   ```

2. **Instalar dependencias:**
   ```bash
   composer install
   npm install
   ```

3. **Generar llave y ejecutar migraciones con datos de prueba:**
   ```bash
   php artisan key:generate
   php artisan migrate:fresh --seed
   php artisan storage:link
   ```

4. **Compilar recursos graficos y levantar el servidor:**
   ```bash
   npm run build
   php artisan serve
   ```

   Para desarrollo con recarga automatica:
   ```bash
   npm run dev
   ```

---

## Despliegue en Produccion (Render)

**Build Command:**
```bash
composer install --prefer-dist --no-dev --optimize-autoloader --no-interaction && npm install && npm run build && php artisan migrate --force
```

**Start Command:**
```bash
php -S 0.0.0.0:$PORT -t public
```

**Variables de entorno requeridas en Render:**
- APP_ENV=production
- APP_DEBUG=false
- APP_KEY=(generada con php artisan key:generate)
- DATABASE_URL=(proporcionada por Render PostgreSQL)
- PUSHER_APP_ID, PUSHER_APP_KEY, PUSHER_APP_SECRET

---

## Credenciales de Prueba

Todos los usuarios creados por los seeders tienen la contrasena: `password`

| Rol | Correo | Notas |
|---|---|---|
| Administrador | admin@pymereservas.com | Acceso total a todos los modulos |
| Empleado | jefferson@pymereservas.com | Agenda personal y perfil |
| Empleado | andres@pymereservas.com | Agenda personal y perfil |
| Cliente | juandavid.osorio@correo.com | Portal de autoservicio con historial |

---

## Licencia

Proyecto academico desarrollado exclusivamente para fines educativos.
Todos los derechos reservados -- COTECNOVA, 2026.
