document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("generadorForm");
    const listaBorradores = document.getElementById("lista-borradores");

    // Función para mostrar un mensaje personalizado
    const showMessage = (message, type = 'success') => {
        const messageBox = document.createElement('div');
        messageBox.classList.add('message-box', type);
        messageBox.textContent = message;
        document.body.appendChild(messageBox);
        setTimeout(() => {
            messageBox.remove();
        }, 3000);
    };

    // Función para obtener y mostrar los borradores
    const cargarBorradores = async () => {
        try {
            const response = await fetch("http://localhost/generador-contenidos-estrategico/api/borradores/leer.php"); 
            
            if (!response.ok) {
                showMessage(`Error al cargar borradores: ${response.statusText}`, 'error');
                return;
            }

            const borradores = await response.json();

            listaBorradores.innerHTML = ''; // Limpiar la lista
            if (Array.isArray(borradores)) {
                borradores.forEach(borrador => {
                    const divBorrador = document.createElement('div');
                    divBorrador.classList.add('borrador');
                    divBorrador.innerHTML = `
                        <h3>${borrador.titulo}</h3>
                        <p><strong>Tipo:</strong> ${borrador.tipo_contenido}</p>
                        <p><strong>Tono:</strong> ${borrador.tono}</p>
                        <p>${borrador.contenido}</p>
                        <button onclick="eliminarBorrador(${borrador.id})">Eliminar</button>
                    `;
                    listaBorradores.appendChild(divBorrador);
                });
            } else {
                showMessage('No se encontraron borradores.', 'info');
            }
        } catch (error) {
            showMessage(`Error de conexión: ${error.message}`, 'error');
        }
    };

    // Manejar el envío del formulario
    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const nuevoBorrador = {
            titulo: document.getElementById("titulo").value,
            tema_principal: document.getElementById("tema_principal").value,
            tipo_contenido: document.getElementById("tipo_contenido").value,
            tono: document.getElementById("tono").value
        };

        try {
            const response = await fetch("http://localhost/generador-contenidos-estrategico/api/borradores/crear_test.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(nuevoBorrador),
            });
    
            if (!response.ok) {
                showMessage(`Error al crear borrador: ${response.statusText}`, 'error');
                return;
            }
    
            const resultado = await response.json();
            showMessage(resultado.message, 'success');
            form.reset();
            cargarBorradores(); // Recargar la lista después de crear uno nuevo
        } catch (error) {
            showMessage(`Error de conexión: ${error.message}`, 'error');
        }
    });

    // Función para eliminar un borrador
    window.eliminarBorrador = async (id) => {
        if (confirm("¿Estás seguro de que quieres eliminar este borrador?")) {
            try {
                const response = await fetch(`http://localhost/generador-contenidos-estrategico/api/borradores/eliminar.php?id=${id}`, {
                    method: "DELETE"
                });

                // Muestra la respuesta del servidor en la consola para depuración
                const resultado = await response.json();
                console.log(resultado); // <<< Línea agregada para depuración

                if (!response.ok) {
                    showMessage(`Error al eliminar borrador: ${resultado.message}`, 'error');
                    return;
                }

                showMessage(resultado.message, 'success');
                cargarBorradores(); // Esto recarga la lista y hace que el borrador desaparezca
            } catch (error) {
                showMessage(`Error de conexión: ${error.message}`, 'error');
            }
        }
    };

    cargarBorradores();
});
