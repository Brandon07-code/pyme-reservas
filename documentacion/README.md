# PYME Reservas — Sistema Web de Gestión de Servicios y Reservas
## Caso de estudio: Barbería y Perfumería JyM, Cartago (Valle del Cauca)

Proyecto de grado desarrollado para optar al título de Tecnólogo en Gestión
de Sistemas de Información — COTECNOVA.

**Autor:** Brandon Cortes  
**Asesor:** Jhon James Cano Sánchez  
**Metodología:** RAD (Rapid Application Development)

---

## Descripción

Sistema web para la administración de citas, servicios, productos y personal
de un establecimiento de barbería. Resuelve el problema de los cruces de citas
(doble asignación en el mismo horario), centraliza la información de clientes
y empleados, y ofrece un portal propio para que los clientes reserven sin
necesidad de contacto directo.

El diseño está orientado a negocios pequeños del sector servicios que trabajen
por reserva, aunque el caso de estudio concreto es la Barbería y Perfumería JyM.

---

## Enlace en Producción

La aplicación se encuentra desplegada y en funcionamiento en:

https://pyme-reservas.onrender.com/

---

## Funcionalidades Implementadas

**Reservas (Agendamiento Inteligente)**
- Validación en tiempo real de disponibilidad por barbero mediante Fetch API (evitando cruces o doble asignación).
- Cálculo automático de la duración de la reserva según los servicios seleccionados.
- Creación de citas para servicios individuales o múltiples.
- Historial de citas y seguimiento de estados (Pendiente, Confirmada, Completada, Cancelada, No Asistió).
- Automatización de estados vencidos mediante tarea programada (Cron Job).

**Comercio Electrónico e Inventario**
- Catálogo de productos (perfumería) visible en el portal público.
- Carrito de compras funcional con generación de pedidos.
- Descuento y restauración de inventario automatizado en tiempo real basado en el estado del pedido.

**Gestión de Usuarios y Roles (RBAC)**
- Tres niveles de acceso: Administrador (Acceso Total), Empleado (Agenda Personal) y Cliente (Historial Privado).
- Validación de dominio corporativo (@pymereservas.com) para cuentas de Administrador y Empleado.
- Panel de perfil para la gestión de datos personales (teléfono, dirección) y subida de fotos de perfil.

**Autenticación en Dos Pasos (2FA)**
- Verificación de identidad mediante código OTP (One-Time Password) de 6 dígitos al iniciar sesión.

**Notificaciones en Tiempo Real**
- Sistema de notificaciones push mediante WebSockets (Pusher) para eventos de reservas, pedidos y cambios de estado.
- Campana de notificaciones con contador de no leídas y panel desplegable.

**Gestión de Turnos y Horarios**
- Configuración individual de días laborables y horas de trabajo por empleado.
- Protección de agenda: el sistema impide desactivar un día si el empleado tiene citas futuras programadas.

**Dashboard y Reportes**
- Panel de indicadores (KPIs) diferenciado por rol (Administrador y Empleado).
- Gráficas de evolución de ingresos mensuales y distribución de estados de citas.
- Exportación de datos a PDF en todos los módulos principales.

---

## Stack Tecnológico

| Componente | Tecnología |
|---|---|
| Back-end | Laravel 11.x (PHP 8.2+) |
| Base de datos (Local) | MySQL 8.0 |
| Base de datos (Producción) | PostgreSQL 15 |
| Front-end | Laravel Blade, Tailwind CSS 3.x, Alpine.js |
| Empaquetador de assets | Vite 5.x |
| Autenticación | Laravel Breeze + OTP (2FA) |
| Tiempo real | Pusher (WebSockets) |
| Reportes PDF | Laravel DomPDF |
| API REST | JWT Auth (tymon/jwt-auth) |
| Documentación API | Scramble |
| Infraestructura local | Docker Compose (Laravel Sail) sobre WSL2 Ubuntu |
| Hosting producción | Render (Plan gratuito) |

---

## Estructura del Proyecto

```
pyme-reservas/
  app/
    Console/Commands/      -- Comandos Artisan personalizados (Cron)
    Http/Controllers/      -- Controladores por módulo
    Http/Middleware/       -- Middleware de verificación de roles
    Mail/                  -- Plantillas de correo (OTP, notificaciones)
    Models/                -- Modelos Eloquent (User, Reservation, Product, etc.)
    Notifications/         -- Notificaciones push (Pusher)
  database/
    migrations/            -- Migraciones de la base de datos
    seeders/               -- Datos de prueba paramétricos
  resources/views/         -- Vistas Blade organizadas por módulo
  routes/
    web.php                -- Rutas web con middleware de roles
    api.php                -- Rutas API REST y endpoint del Cron Job
  public/
    cron.php               -- Endpoint independiente para tarea programada
```

---

## Instrucciones de Instalación Local

A continuación se describe paso a paso cómo poner en marcha el proyecto
en un equipo local para desarrollo o evaluación.

### Paso 1. Clonar el repositorio

Abrir una terminal y ejecutar:

```bash
git clone https://github.com/Brandon07-code/pyme-reservas.git
cd pyme-reservas
```

### Paso 2. Crear el archivo de configuración

El proyecto necesita un archivo `.env` con las credenciales de la base de datos
y demás configuraciones. Se debe copiar el archivo de ejemplo que viene incluido:

```bash
cp .env.example .env
```

Luego, abrir el archivo `.env` y ajustar las variables de conexión a la base de
datos local (DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD) según
la configuración del equipo.

### Paso 3. Instalar dependencias del proyecto

El proyecto utiliza Composer para las dependencias de PHP (Laravel, DomPDF, JWT, etc.)
y NPM para las dependencias de JavaScript (Tailwind CSS, Vite, Alpine.js).

```bash
composer install
npm install
```

### Paso 4. Generar la llave de encriptación

Laravel requiere una clave única para el cifrado de sesiones y datos sensibles:

```bash
php artisan key:generate
```

### Paso 5. Crear las tablas y cargar datos de prueba

Este comando crea todas las tablas en la base de datos y las llena con datos
de ejemplo (barberos, clientes, servicios, productos, reservas y pedidos):

```bash
php artisan migrate:fresh --seed
```

Para habilitar la subida de imágenes de perfil, se debe crear el enlace
simbólico al almacenamiento público:

```bash
php artisan storage:link
```

### Paso 6. Compilar los estilos y levantar el servidor

Primero se compilan los archivos de Tailwind CSS y JavaScript con Vite:

```bash
npm run build
```

Luego se levanta el servidor de desarrollo de Laravel:

```bash
php artisan serve
```

El sistema estará disponible en http://localhost:8000.

Para desarrollo con recarga automática (hot reload), se puede usar en su lugar:

```bash
npm run dev
```

---

## Despliegue en Producción (Render)

El sistema está publicado en internet usando Render, una plataforma de hosting
en la nube. Se utiliza el plan gratuito (Free Instance), lo cual implica ciertas
consideraciones que se explican a continuación.

### Cómo funciona el despliegue

El repositorio de GitHub está vinculado directamente a Render. Cada vez que se
hace un push a la rama `main`, Render detecta el cambio automáticamente,
descarga el código, instala las dependencias, compila los estilos y publica
la nueva versión sin intervención manual.

La instalación de dependencias y la compilación se ejecutan mediante el script
`composer setup` definido en el archivo `composer.json` del proyecto.

### Variables de entorno en Render

Para que el sistema funcione correctamente en producción, se deben configurar
las siguientes variables de entorno desde el panel de Render:

| Variable | Descripción |
|---|---|
| APP_ENV | production |
| APP_DEBUG | false |
| APP_KEY | Se genera con `php artisan key:generate` |
| DATABASE_URL | Proporcionada automáticamente por Render al crear la base PostgreSQL |
| PUSHER_APP_ID | Credencial de la aplicación en Pusher para notificaciones |
| PUSHER_APP_KEY | Clave pública de Pusher |
| PUSHER_APP_SECRET | Clave privada de Pusher |

### Comportamiento del plan gratuito (Free Instance)

El plan gratuito de Render tiene una característica importante: si el servidor
no recibe ninguna visita durante 15 minutos seguidos, Render lo apaga
automáticamente para ahorrar recursos. Esto se conoce como "sleep" o
"suspensión".

Cuando alguien visita la página después de que el servidor se ha dormido,
Render lo despierta, pero este proceso puede tardar entre 30 y 60 segundos.
Es completamente normal y no significa que el sistema esté dañado.

### Cómo se mantiene el servidor despierto

Para evitar que el servidor se duerma constantemente, se utiliza un servicio
gratuito llamado UptimeRobot (https://uptimerobot.com).

UptimeRobot envía una visita automática a la página principal del sistema cada
5 minutos, las 24 horas del día. Como el servidor siempre recibe tráfico antes
de que pasen los 15 minutos, nunca llega a dormirse.

Configuración en UptimeRobot:
1. Crear una cuenta gratuita en https://uptimerobot.com.
2. Agregar un nuevo monitor de tipo HTTP(s).
3. Colocar la URL: https://pyme-reservas.onrender.com/
4. Establecer el intervalo en 5 minutos.
5. Guardar.

### Tarea programada (Cron Job)

El sistema necesita revisar periódicamente las citas que ya pasaron de hora
para actualizar su estado automáticamente (las pendientes pasan a "No Asistió"
y las confirmadas pasan a "Completada"). Esta tarea se ejecuta cada 15 minutos
de forma automática.

Como Render en su plan gratuito no permite ejecutar tareas programadas
directamente en el servidor, se utiliza un servicio externo gratuito llamado
cron-job.org (https://cron-job.org).

Este servicio llama a un archivo PHP independiente del sistema (`cron.php`)
que se conecta directamente a la base de datos y actualiza los estados sin
pasar por el framework Laravel. Esto garantiza que la respuesta sea liviana
y rápida, incluso cuando el servidor acaba de despertar.

Configuración en cron-job.org:
1. Crear una cuenta gratuita en https://cron-job.org.
2. Agregar un nuevo cronjob.
3. Colocar la URL: `https://pyme-reservas.onrender.com/cron.php?token=jym-seguro-2026`
4. Establecer la ejecución cada 15 minutos.
5. Guardar y activar.

---

## Credenciales de Prueba

Para efectos de evaluación y demostración del aplicativo por parte de los jurados,
se proporcionan las siguientes cuentas de prueba.

**Nota:** El código de verificación (OTP) al iniciar sesión es fijo para todas las cuentas: `123456`

| Rol | Correo | Contraseña |
|---|---|---|
| Administrador | admin@pymereservas.com | password |
| Empleado | jefferson@pymereservas.com | password |
| Empleado | andres@pymereservas.com | password |
| Cliente | juandavid.osorio@correo.com | password |

---

## Licencia

Proyecto académico desarrollado exclusivamente para fines educativos.
Todos los derechos reservados — COTECNOVA, 2026.
