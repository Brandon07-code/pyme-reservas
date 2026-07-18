# Sistema Web de Gestión de Citas e Inventario (PYME) - "Barbería JyM"

Este repositorio contiene el código fuente del proyecto de grado desarrollado para optar al título de **Tecnólogo en Gestión de Sistemas de Información** (COTECNOVA).

**Autor:** Brandon
**Caso de Estudio:** Barbería JyM
**Enfoque:** Adaptable a Pequeñas y Medianas Empresas (PYMES) del sector servicios.

---

## 🎯 Objetivo del Proyecto
Desarrollar e implementar un sistema web centralizado que resuelva los problemas de agendamiento cruzado (citas solapadas) y garantice el control del inventario en tiempo real, mejorando la experiencia tanto del cliente final como de los administradores y empleados.

## 🚀 Estado Actual: VERSIÓN FINAL (Producción Local)
El proyecto ha sido completado al 100% en su fase de desarrollo y cuenta con las siguientes características implementadas:

### Módulo de Agendamiento Inteligente ("Motor Tetris")
- Algoritmo en tiempo real (AJAX/Fetch) que valida y bloquea horas ocupadas para evitar cruces.
- Separación de agendas por Barbero (Empleado).
- Panel interactivo para confirmación y marcado de asistencia.

### E-Commerce y Control de Inventario
- Portal público de "Vitrina" (Servicios y Perfumes).
- Carrito de compras funcional con descuento automático de inventario al ordenar.
- Dashboard de pedidos (Pendientes, Por Entregar, Completados) para el control en caja.

### Sistema de Usuarios y Seguridad (RBAC)
- **Roles Definidos:** Administrador (Acceso Total), Empleado (Solo su agenda), Cliente (Solo sus reservas/pedidos).
- **Gestión de Perfiles:** Subida de Avatares (Fotos de perfil), actualización de teléfono y dirección.

## 🛠️ Stack Tecnológico
- **Back-end:** Laravel 11.x (PHP 8.2)
- **Base de Datos:** MySQL (Relacional)
- **Front-end:** Laravel Blade, Tailwind CSS (Nativo/Vite), Vanilla JavaScript.
- **Autenticación:** Laravel Breeze.
- **Entorno de Desarrollo:** Docker (Laravel Sail) corriendo sobre WSL2 (Ubuntu).

## 📦 Instrucciones para Levantar el Entorno Local

Al estar basado en **Laravel Sail**, no es necesario tener PHP, Composer o MySQL instalados directamente en Windows. Todo corre de forma encapsulada y segura dentro de Docker.

1. **Clonar el repositorio:**
   ```bash
   git clone <url-del-repo>
   cd pyme-reservas
   ```

2. **Configurar el entorno:**
   Copia el archivo de configuración base:
   ```bash
   cp .env.example .env
   ```

3. **Instalar dependencias y levantar el servidor (Vía Sail):**
   ```bash
   # Instalar dependencias de PHP usando un contenedor temporal
   docker run --rm \
       -u "$(id -u):$(id -g)" \
       -v "$(pwd):/var/www/html" \
       -w /var/www/html \
       laravelsail/php82-composer:latest \
       composer install --ignore-platform-reqs

   # Levantar los contenedores en segundo plano
   ./vendor/bin/sail up -d
   ```

4. **Generar claves y base de datos:**
   ```bash
   ./vendor/bin/sail artisan key:generate
   ./vendor/bin/sail artisan migrate:fresh --seed
   ./vendor/bin/sail artisan storage:link
   ```

5. **Compilar estilos y scripts (Tailwind):**
   ```bash
   ./vendor/bin/sail npm install
   ./vendor/bin/sail npm run dev
   ```

6. **¡Listo!** Abre tu navegador en:
   `http://localhost`

---
*Desarrollado con ❤️ para transformar la administración de las PYMES.*
