# PYME Reservas — Sistema Web de Gestión de Servicios y Reservas
## Caso de estudio: Barbería y Perfumería JyM, Cartago (Valle del Cauca)

Proyecto de grado desarrollado para optar al título de Tecnólogo en Gestión
de Sistemas de Información — COTECNOVA.

**Autor:** Brandon  
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

## Funcionalidades implementadas

**Reservas (Agendamiento Inteligente)**
- Validación en tiempo real de disponibilidad por barbero mediante Fetch API (evitando cruces o doble asignación).
- Creación de citas para servicios individuales o múltiples.
- Historial de citas y seguimiento de estados (Pendiente, Confirmada, Completada, Cancelada).

**Comercio Electrónico e Inventario**
- Catálogo de productos (perfumería) visible en el portal público.
- Carrito de compras funcional con generación de pedidos.
- Descuento y restauración de inventario automatizado en tiempo real basado en el estado del pedido.

**Gestión de Usuarios y Roles (RBAC)**
- Tres niveles de acceso: Administrador (Acceso Total), Empleado (Agenda Personal) y Cliente (Historial Privado).
- Panel de perfil para la gestión de datos personales (teléfono, dirección) y subida de fotos de perfil (avatares).

---

## Stack Tecnológico

- **Lógica de Negocio y Back-end:** Laravel 11.x (PHP 8.2)
- **Motor de Base de Datos:** MySQL
- **Diseño Front-end:** Laravel Blade, Tailwind CSS y Vanilla JavaScript.
- **Autenticación:** Laravel Breeze.
- **Infraestructura Local:** Docker Compose (Laravel Sail) ejecutado sobre WSL2 Ubuntu.

---

## Instrucciones de Instalación Local

1. **Clonar repositorio y preparar variables de entorno:**
   ```bash
   git clone <url-del-repositorio>
   cd pyme-reservas
   cp .env.example .env
   ```

2. **Levantar contenedores con Laravel Sail:**
   ```bash
   ./vendor/bin/sail up -d
   ```

3. **Ejecutar migraciones y datos de prueba (Seeders):**
   ```bash
   ./vendor/bin/sail artisan key:generate
   ./vendor/bin/sail artisan migrate:fresh --seed
   ./vendor/bin/sail artisan storage:link
   ```

4. **Compilar recursos gráficos (Tailwind CSS):**
   ```bash
   ./vendor/bin/sail npm install
   ./vendor/bin/sail npm run dev
   ```
