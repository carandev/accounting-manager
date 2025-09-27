# Accounting Manager

<p align="center">
<img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## 📋 Descripción

Accounting Manager es un sistema de gestión financiera personal desarrollado con Laravel y Filament. Permite a los usuarios gestionar sus cuentas, transacciones y categorías de gastos de manera eficiente y organizada.

## ✨ Características Principales

- 🏦 Gestión de cuentas bancarias
- 💰 Registro de transacciones (ingresos y gastos)
- 📊 Dashboard con estadísticas y gráficos
- 🏷️ Categorización de transacciones
- 📅 Filtrado por fechas de transacción
- 👤 Sistema de autenticación y autorización
- 📱 Interfaz responsive y moderna

## 🛠️ Tecnologías Utilizadas

- **Backend:**
  - PHP 8.1+
  - Laravel 10.x
  - MySQL/MariaDB
  - Filament Admin Panel

- **Frontend:**
  - Tailwind CSS
  - Alpine.js
  - Livewire

## 🚀 Instalación Local

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/carandev/accounting-manager.git
   cd accounting-manager
   ```

2. **Instalar dependencias**
   ```bash
   composer install
   npm install
   ```

3. **Configurar entorno**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Editar el archivo `.env` con tus credenciales de base de datos:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=accounting_manager
   DB_USERNAME=tu_usuario
   DB_PASSWORD=tu_contraseña
   ```

4. **Ejecutar migraciones y seeders**
   ```bash
   php artisan migrate --seed
   ```

5. **Compilar assets**
   ```bash
   npm run build
   ```

6. **Iniciar el servidor**
   ```bash
   php artisan serve
   ```

7. **Acceder al sistema**
   - URL: `http://localhost:8000`
   - Usuario por defecto: `admin@example.com`
   - Contraseña: `password`

## 📈 Roadmap y Mejoras Pendientes

### Prioridad Alta
- [ ] Implementar exportación de reportes en PDF/Excel
- [ ] Agregar presupuestos mensuales por categoría
- [ ] Sistema de etiquetas para transacciones
- [ ] Integración con APIs de bancos para sincronización automática

### Prioridad Media
- [ ] Gráficos de tendencias y comparativas
- [ ] Recordatorios de pagos recurrentes
- [ ] Sistema de metas financieras
- [ ] Dashboard personalizable

### Prioridad Baja
- [ ] App móvil (Kotlin Multiplatform)
- [ ] Integración con servicios de facturación electrónica
- [ ] Sistema de alertas por email/SMS
- [ ] API pública para integraciones

## 🤝 Contribuir

Las contribuciones son bienvenidas. Por favor, sigue estos pasos:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'feat: Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 👥 Autores

- [Carlos Gomez](https://github.com/carandev)

---

<p align="center">
❤️ Hecho con Laravel y mucho ❤️
</p>
