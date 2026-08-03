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
| Hosting produccion | Render (Plan gratuito) |

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

A continuacion se describe paso a paso como poner en marcha el proyecto
en un equipo local para desarrollo o evaluacion.

### Paso 1. Clonar el repositorio

Abrir una terminal y ejecutar:

```bash
git clone https://github.com/Brandon07-code/pyme-reservas.git
cd pyme-reservas
```

### Paso 2. Crear el archivo de configuracion

El proyecto necesita un archivo `.env` con las credenciales de la base de datos
y demas configuraciones. Se debe copiar el archivo de ejemplo que viene incluido:

```bash
cp .env.example .env
```

Luego, abrir el archivo `.env` y ajustar las variables de conexion a la base de
datos local (DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD) segun
la configuracion del equipo.

### Paso 3. Instalar dependencias del proyecto

El proyecto utiliza Composer para las dependencias de PHP (Laravel, DomPDF, JWT, etc.)
y NPM para las dependencias de JavaScript (Tailwind CSS, Vite, Alpine.js).

```bash
composer install
npm install
```

### Paso 4. Generar la llave de encriptacion

Laravel requiere una clave unica para el cifrado de sesiones y datos sensibles:

```bash
php artisan key:generate
```

### Paso 5. Crear las tablas y cargar datos de prueba

Este comando crea todas las tablas en la base de datos y las llena con datos
de ejemplo (barberos, clientes, servicios, productos, reservas y pedidos):

```bash
php artisan migrate:fresh --seed
```

Para habilitar la subida de imagenes de perfil, se debe crear el enlace
simbolico al almacenamiento publico:

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

El sistema estara disponible en http://localhost:8000.

Para desarrollo con recarga automatica (hot reload), se puede usar en su lugar:

```bash
npm run dev
```

---

## Despliegue en Produccion (Render)

El sistema esta publicado en internet usando Render, una plataforma de hosting
en la nube. Se utiliza el plan gratuito (Free Instance), lo cual implica ciertas
consideraciones que se explican a continuacion.

### Como funciona el despliegue

El repositorio de GitHub esta vinculado directamente a Render. Cada vez que se
hace un push a la rama `main`, Render detecta el cambio automaticamente,
descarga el codigo, instala las dependencias, compila los estilos y publica
la nueva version sin intervencion manual.

La instalacion de dependencias y la compilacion se ejecutan mediante el script
`composer setup` definido en el archivo `composer.json` del proyecto.

### Variables de entorno en Render

Para que el sistema funcione correctamente en produccion, se deben configurar
las siguientes variables de entorno desde el panel de Render:

| Variable | Descripcion |
|---|---|
| APP_ENV | production |
| APP_DEBUG | false |
| APP_KEY | Se genera con `php artisan key:generate` |
| DATABASE_URL | Proporcionada automaticamente por Render al crear la base PostgreSQL |
| PUSHER_APP_ID | Credencial de la aplicacion en Pusher para notificaciones |
| PUSHER_APP_KEY | Clave publica de Pusher |
| PUSHER_APP_SECRET | Clave privada de Pusher |

### Comportamiento del plan gratuito (Free Instance)

El plan gratuito de Render tiene una caracteristica importante: si el servidor
no recibe ninguna visita durante 15 minutos seguidos, Render lo apaga
automaticamente para ahorrar recursos. Esto se conoce como "sleep" o
"suspension".

Cuando alguien visita la pagina despues de que el servidor se ha dormido,
Render lo despierta, pero este proceso puede tardar entre 30 y 60 segundos.
Es completamente normal y no significa que el sistema este danado.

### Como se mantiene el servidor despierto

Para evitar que el servidor se duerma constantemente, se utiliza un servicio
gratuito llamado UptimeRobot (https://uptimerobot.com).

UptimeRobot envia una visita automatica a la pagina principal del sistema cada
5 minutos, las 24 horas del dia. Como el servidor siempre recibe trafico antes
de que pasen los 15 minutos, nunca llega a dormirse.

Configuracion en UptimeRobot:
1. Crear una cuenta gratuita en https://uptimerobot.com.
2. Agregar un nuevo monitor de tipo HTTP(s).
3. Colocar la URL: https://pyme-reservas.onrender.com/
4. Establecer el intervalo en 5 minutos.
5. Guardar.

### Tarea programada (Cron Job)

El sistema necesita revisar periodicamente las citas que ya pasaron de hora
para actualizar su estado automaticamente (las pendientes pasan a "No Asistio"
y las confirmadas pasan a "Completada"). Esta tarea se ejecuta cada 15 minutos
de forma automatica.

Como Render en su plan gratuito no permite ejecutar tareas programadas
directamente en el servidor, se utiliza un servicio externo gratuito llamado
cron-job.org (https://cron-job.org).

Este servicio llama a un archivo PHP independiente del sistema (`cron.php`)
que se conecta directamente a la base de datos y actualiza los estados sin
pasar por el framework Laravel. Esto garantiza que la respuesta sea liviana
y rapida, incluso cuando el servidor acaba de despertar.

Configuracion en cron-job.org:
1. Crear una cuenta gratuita en https://cron-job.org.
2. Agregar un nuevo cronjob.
3. Colocar la URL: https://pyme-reservas.onrender.com/cron.php?token=(token de seguridad)
4. Establecer la ejecucion cada 15 minutos.
5. Guardar y activar.

---

## Credenciales de Prueba

Para efectos de evaluacion y demostracion del aplicativo por parte de los jurados,
se proporcionan las siguientes cuentas de prueba.

**Nota:** El codigo de verificacion (OTP) al iniciar sesion es fijo para todas las cuentas: `123456`

| Rol | Correo | Contrasena |
|---|---|---|
| Administrador | admin@pymereservas.com | password |
| Empleado | jefferson@pymereservas.com | password |
| Empleado | andres@pymereservas.com | password |
| Cliente | juandavid.osorio@correo.com | password |

---

## Licencia

Proyecto academico desarrollado exclusivamente para fines educativos.
Todos los derechos reservados -- COTECNOVA, 2026.
