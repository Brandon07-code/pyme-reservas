<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# sistema Web de Gestión de Servicios y Reservas (PYME)

Este repositorio contiene el código fuente del proyecto de grado para optar al título de Tecnólogo en Gestión de Sistemas de Información (COTECNOVA).

**Caso de Estudio:** Barbería.
**Enfoque:** Adaptable a Pequeñas y Medianas Empresas (PYMES) del sector servicios.

## estado actual del proyecto
**Fase 1 Completada:** 
- Configuración de entorno con Docker (Laravel Sail) y WSL2.
- Diseño de Arquitectura MVC (Esqueleto inicial).
- Implementación de Layout principal (Plantilla Maestra con Tailwind CSS vía CDN).
- Configuración de Rutas y Controladores para módulos principales (sin conexión a Base de Datos).

## stack  base
- **Framework:** Laravel (PHP)
- **Base de Datos:** MySQL
- **Entorno de Desarrollo:** Docker Compose (Laravel Sail)
- **Frontend (UI):** Blade Templates + Tailwind CSS (CDN)
- **Control de Versiones:** Git / GitHub

## modulos del Sistema (Arquitectura de Navegación)
El sistema cuenta actualmente con la estructura de vistas y controladores para:
1. Dashboard (Inicio)
2. Gestión de Usuarios
3. Gestión de Empleados
4. Gestión de Clientes
5. Catálogo de Servicios
6. Catálogo de Productos
7. Gestión de Reservas

## instrucciones para levantar el entorno local
Al estar basado en Laravel Sail, no es necesario tener PHP o MySQL instalados en la máquina local.

1. Clonar el repositorio.
2. Copiar el archivo de entorno: `cp .env.example .env` (Asegurar que `SESSION_DRIVER=file` por ahora).
3. Levantar los contenedores de Docker:
   ```bash
   ./vendor/bin/sail up -d
## Captura prueba del avance 1
![Dashboard](capturas/Avance1.png)
