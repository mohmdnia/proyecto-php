# Proyecto de Gestión de Empleados (PHP)

Este es un proyecto PHP para gestionar empleados, que permite realizar operaciones como la búsqueda de usuarios y la visualización de sus datos personales. El sistema utiliza bases de datos MySQL y está diseñado para ser ejecutado en un servidor local usando XAMPP en Windows.

## Requisitos

- **Windows 10 o superior**.
- **XAMPP** (incluye Apache y MySQL).
- **Navegador web** (Chrome, Firefox, etc.).

## Instalación

### Paso 1: Descargar e Instalar XAMPP

1. **Descargar XAMPP**:
   - Dirígete al sitio oficial de XAMPP: [https://www.apachefriends.org/index.html](https://www.apachefriends.org/index.html).
   - Descarga la versión de XAMPP que se ajuste a tu sistema operativo (en este caso, Windows).
   
2. **Instalar XAMPP**:
   - Ejecuta el instalador descargado.
   - Sigue los pasos del asistente de instalación y asegúrate de instalar **Apache** y **MySQL** (son los que utilizaremos para ejecutar el proyecto).
   
3. **Iniciar XAMPP**:
   - Abre el panel de control de XAMPP (XAMPP Control Panel).
   - Inicia los servicios de **Apache** y **MySQL** haciendo clic en **Start** junto a cada uno.
   - Esto arrancará el servidor web Apache (para servir las páginas PHP) y MySQL (para la base de datos).

### Paso 2: Descargar el Proyecto

1. **Obtener los Archivos del Proyecto**:
   - Clona o descarga este repositorio en tu computadora.
   - Asegúrate de que los archivos del proyecto estén dentro de la carpeta **`htdocs`** de XAMPP. Esta carpeta se encuentra en el directorio donde instalaste XAMPP. Por lo general, está en `C:\xampp\htdocs\`.

   Por ejemplo, si tu proyecto se llama `proyecto-empleados`, debes colocarlo en:



### Paso 3: Configurar la Base de Datos

1. **Acceder a phpMyAdmin**:
- Abre tu navegador web y accede a [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
- Esto abrirá la interfaz de administración de bases de datos **phpMyAdmin**.

2. **Crear la Base de Datos**:
- En **phpMyAdmin**, haz clic en **Nuevo** en el menú lateral para crear una nueva base de datos.
- Nombra la base de datos como **`empleados`** (o cualquier otro nombre que prefieras, pero asegúrate de cambiarlo en el archivo de conexión `database.php`).

3. **Importar las Tablas**:
- A continuación, deberás importar las tablas del proyecto. Si el proyecto incluye un archivo SQL con la estructura de las tablas, selecciona la base de datos **empleados** y haz clic en **Importar**.
- Selecciona el archivo `.sql` proporcionado con el proyecto para crear las tablas necesarias.

