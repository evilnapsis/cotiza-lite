# Cotiza Lite

Cotiza Lite es un sistema moderno de gestión de Cotizaciones, Presupuestos Comerciales, Clientes y Catálogo de Servicios desarrollado en **PHP** y **MySQL**.

---

## 🚀 Novedades y Actualizaciones (2026)

- **Arquitectura LegoBox v5 MVC con Enrutamiento Limpio**: Implementación de **FastRoute** para URLs amigables (ej: `/home`, `/cotizations`, `/cotization/new`, `/clients`, `/services`, `/reports`, `/settings`).
- **Motor de Plantillas Twig**: Vistas declarativas y desacopladas en `public/` con herencia de layouts (`main.html.twig`), inyección automática de variables globales y helpers de seguridad (`csrf_field()`).
- **Seguridad Integrada**: Protección contra ataques **CSRF** en todas las peticiones POST mediante validación de tokens únicos por sesión.
- **Capa de Servicios**: Desacoplamiento total de la lógica de negocio en clases de servicio dedicadas (`CotizationService`, `PersonService`, `ServiceService`, `ReportService`, `SettingService`, `UserService`, etc.).
- **Generación y Exportación de Documentos**:
  - **Exportación en PDF**: Generación dinámica de cotizaciones en formato PDF profesional con datos de la empresa, desglose de servicios y montos mediante **FPDF**.
  - **Exportación a Excel**: Reporte de cotizaciones exportable en hojas de cálculo `.xlsx` con estilos personalizados utilizando **PhpSpreadsheet**.
- **Gestión de Ajustes de la Empresa**:
  - Módulo **Ajustes** (`/settings`): Configuración dinámica de logotipo, razón social, RFC, dirección, teléfono y correo electrónico que se reflejan en las cotizaciones y comprobantes.
- **Interfaz de Usuario Moderna**: Plantilla integrada con **CoreUI**, componentes **Bootstrap 5**, **Bootstrap Icons** y alertas interactivas con **SweetAlert2**.

---

## 📦 Módulos Principales

- **Dashboard / Inicio**: Resumen estadístico y acceso directo a las operaciones frecuentes.
- **Cotizaciones**: Creación, edición, control de estados (*Borrador*, *Pendiente*, *Vendido*), adición dinámica de servicios/ítems, notas comerciales, cálculo de totales y descarga directa en **PDF**.
- **Clientes**: Registro y administración completa de cartera de clientes vinculados con ciudad y tipo de crédito.
- **Servicios**: Catálogo de productos y servicios con códigos, precios y clasificación por categoría.
- **Reportes**: Consulta de cotizaciones por rango de fechas con visualización en tabla y exportación a **Excel (.xlsx)**.
- **Catálogos Auxiliares**:
  - **Categorías**: Organización y clasificación de servicios.
  - **Ciudades**: Registro de ciudades para la ubicación de clientes.
  - **Tipos de Crédito**: Condiciones y términos de pago asociados a clientes.
  - **Formas de Pago**: Métodos de pago registrados (Efectivo, Transferencia, etc.).
- **Administración**: Gestión de usuarios del sistema con control de acceso y estados.
- **Ajustes de Empresa**: Personalización de los datos comerciales y membrete de la empresa.

---

## 🛠️ Requisitos del Sistema

- **Servidor Web**: Apache (con módulo `mod_rewrite` activado) o Nginx.
- **PHP**: versión 8.0 o superior (recomendado PHP 8.1 / 8.2) con extensiones `pdo_mysql`, `mysqli`, `gd` y `zip` (requerida por PhpSpreadsheet).
- **Base de Datos**: MySQL 5.7+ / MariaDB 10.3+.
- **Gestor de dependencias**: Composer.

---

## ⚙️ Instalación y Configuración

1. **Clonar o descargar el repositorio**:
   Coloca el proyecto en el directorio raíz de tu servidor web (ej. `htdocs/cotiza-lite` en XAMPP/LAMPP).

2. **Instalar dependencias de Composer**:
   ```bash
   composer install
   ```

3. **Base de Datos**:
   Crea la base de datos `cotizalite` en MySQL e importa la estructura y datos iniciales del archivo `schema.sql`:
   ```sql
   CREATE DATABASE cotizalite;
   USE cotizalite;
   SOURCE schema.sql;
   ```

4. **Configuración de Conexión**:
   Edita los parámetros de conexión a la base de datos en [`core/controller/Database.php`](file:///Applications/XAMPP/xamppfiles/htdocs/cotiza-lite/core/controller/Database.php):
   ```php
   $this->user = "root";
   $this->pass = "";
   $this->host = "localhost";
   $this->ddbb = "cotizalite";
   ```

5. **Acceso**:
   Abre tu navegador web e ingresa a `http://localhost/cotiza-lite/`.

6. **Credenciales por defecto**:
   - **Usuario / Email**: `admin`
   - **Contraseña**: `admin`

---

## 📄 Más Información y Créditos

Encuentra más información, instrucciones y proyectos en el siguiente enlace:
- **Sitio Web Oficial**: [https://evilnapsis.com/](https://evilnapsis.com/)

Desarrollado por [Evilnapsis](https://evilnapsis.com/).