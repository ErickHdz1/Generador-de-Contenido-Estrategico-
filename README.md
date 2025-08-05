Generador de Contenido Estratégico
!(https://placehold.co/800x400/E0E7FF/4F46E5?text=Generador+de+Contenido+Estratégico)

Descripción del Proyecto
El "Generador de Contenido Estratégico" es una aplicación web diseñada para ayudar a empresas y profesionales a crear rápidamente borradores de contenido estratégico (como campañas de marketing, informes, resúmenes, artículos de blog y correos electrónicos) utilizando inteligencia artificial generativa. La aplicación ofrece una interfaz de usuario intuitiva, opciones de personalización de tono y una gestión básica de borradores (crear, leer, actualizar, eliminar).

Este proyecto demuestra la integración de un frontend React con un backend PHP/MySQL, utilizando la API de Gemini para la generación de texto.

Características
Generación de Contenido con IA: Genera borradores de texto automáticamente utilizando la API de Gemini de Google.

Personalización de Tono: Selecciona el tipo de contenido (campaña de marketing, informe, etc.) y el tono (formal, casual, creativo, etc.) para influir en la generación de la IA.

Gestión de Borradores (CRUD):

Crear: Genera y guarda nuevos borradores en la base de datos.

Leer: Visualiza una lista de todos los borradores guardados, con un resumen del contenido, contador de palabras y fecha de creación.

Actualizar: Edita borradores existentes cargando su contenido en el formulario.

Eliminar: Borra borradores de la base de datos.

Interfaz de Usuario Intuitiva: Diseño moderno y responsivo construido con React y Tailwind CSS para una experiencia de usuario fluida.

Funcionalidades de Copia: Copia el contenido completo de un borrador al portapapeles con un solo clic.

Tecnologías Utilizadas
Frontend:

React (v18)

Tailwind CSS (v3.x)

HTML, CSS, JavaScript (ES6+)

Babel Standalone (para compilar JSX en el navegador durante el desarrollo)

Backend:

PHP (v7.4+ recomendado)

MySQL

Base de Datos:

phpMyAdmin (para gestión de la base de datos)

IA Generativa:

Google Gemini API (modelo gemini-2.5-flash-preview-05-20)

Servidor Local:

XAMPP (Apache y MySQL)

Instalación y Configuración Local
Sigue estos pasos para configurar y ejecutar el proyecto en tu máquina local.

Prerrequisitos
XAMPP: Asegúrate de tener XAMPP instalado y funcionando (con Apache y MySQL iniciados).

Git: Para clonar el repositorio.

Clave de API de Gemini: Necesitas una clave de API válida de Google AI Studio. Puedes obtenerla aquí.

1. Clonar el Repositorio
Abre tu terminal (Git Bash, CMD o PowerShell) y clona el repositorio:

git clone https://github.com/Erickhdzi/Generador-de-Contenido-Estrategico-.git
cd Generador-de-Contenido-Estrategico-

2. Configuración del Backend (PHP y MySQL)
Mover Archivos PHP:

Copia la carpeta api (que contiene borradores/crear_test.php, borradores/leer.php, borradores/eliminar.php, borradores/actualizar.php) y el archivo db_config.php a la raíz de tu servidor web Apache.

La estructura debería ser algo así:

C:\xampp\htdocs\generador-contenidos-estrategico\
├── index.html
├── db_config.php
└── api/
    └── borradores/
        ├── crear_test.php
        ├── leer.php
        ├── eliminar.php
        └── actualizar.php

Configurar la Base de Datos:

Abre phpMyAdmin en tu navegador (usualmente http://localhost/phpmyadmin/).

Crea una nueva base de datos si no existe, llamada contenido_estrategico. En el panel izquierdo, haz clic en "Nueva" o "Bases de datos", escribe contenido_estrategico y haz clic en "Crear".

Selecciona la base de datos contenido_estrategico en el panel izquierdo.

Haz clic en la pestaña "SQL" y ejecuta el siguiente comando para crear la tabla borradores:

CREATE TABLE borradores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    contenido TEXT,
    tipo_contenido VARCHAR(100) NOT NULL,
    tono VARCHAR(100) NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

Actualizar db_config.php:

Abre el archivo db_config.php en tu editor de código.

Asegúrate de que las credenciales de la base de datos coincidan con tu configuración de MySQL en XAMPP. Por defecto, suelen ser:

<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', ''); // Deja vacío si no tienes contraseña
define('DB_NAME', 'contenido_estrategico'); // Asegúrate que este nombre coincida
// ... (resto del código)
?>

Guarda los cambios.

3. Configuración del Frontend (HTML/React)
Actualizar index.html con tu API Key:

Abre el archivo index.html en tu editor de código.

Busca la línea donde se define apiKey:

const apiKey = "TU_CLAVE_DE_API_AQUI"; // <--- REEMPLAZA ESTO CON TU CLAVE REAL

Reemplaza "TU_CLAVE_DE_API_AQUI" con tu clave de API de Gemini real y completa. Asegúrate de que la clave esté dentro de las comillas dobles.

Guarda los cambios en index.html.

4. Ejecutar la Aplicación
Asegúrate de que Apache y MySQL estén corriendo en tu Panel de Control de XAMPP.

Abre tu navegador web.

Navega a la URL de tu aplicación:

http://localhost/generador-contenidos-estrategico/index.html

¡La aplicación debería cargarse y funcionar correctamente!

Uso de la Aplicación
Crear un Borrador:

Ingresa un Título del Borrador.

Selecciona el Tipo de Contenido y el Tono deseados.

Escribe la Idea o tema principal que quieres que la IA desarrolle.

Haz clic en el botón "Generar y Guardar Borrador". La IA generará el contenido y lo guardará.

Ver Borradores Guardados:

Los borradores aparecerán en la sección "Borradores Guardados".

Cada tarjeta muestra el título, un fragmento del contenido, la fecha de creación, el modelo de IA utilizado y el número de palabras.

Haz clic en "Ver más" para expandir el contenido completo.

Editar un Borrador:

Haz clic en el icono de lápiz (Editar) en la tarjeta del borrador que deseas modificar.

Los datos del borrador se cargarán en el formulario superior.

Realiza los cambios deseados y haz clic en "Actualizar Borrador".

Eliminar un Borrador:

Haz clic en el icono de bote de basura (Eliminar) en la tarjeta del borrador que deseas borrar.

Copiar Contenido:

Haz clic en el icono de copiar en la tarjeta del borrador para copiar su contenido completo al portapapeles.

Futuras Mejoras (Opcional)
Autenticación de Usuarios: Implementar un sistema de login/registro para que cada usuario tenga sus propios borradores privados.

Exportación de Borradores: Añadir opciones para exportar el contenido a diferentes formatos (PDF, DOCX, TXT).

Más Opciones de IA: Integrar parámetros adicionales para la generación de IA, como longitud, palabras clave específicas, etc.

Historial de Versiones: Permitir guardar múltiples versiones de un borrador editado.

Licencia
Este proyecto está bajo la licencia MIT. Consulta el archivo LICENSE para más detalles.

Contacto
Si tienes preguntas o sugerencias, no dudes en contactarme a través de mi perfil de GitHub: Erickhdzi