<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Solicitudes de Visita</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 800px; margin: auto; }
        form { margin-bottom: 20px; }
        input, select, textarea, button { margin-bottom: 10px; padding: 8px; width: 100%; box-sizing: border-box; }
        .solicitud-item { border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; }
        .solicitud-item button { width: auto; margin-right: 5px; }
        .btn-container {
            display: flex;
            justify-content: flex-start;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="btn-container">
            <button class="btn btn-secondary btn-back w-25" onclick="goBack()">Volver</button>
        </div>
        <h1>Solicitudes de Visita</h1>

        <!-- Mensaje de estado -->
        <div id="message" class="alert" role="alert" style="display: none;"></div>

        <!-- Formulario para agregar o actualizar solicitud -->
        <form id="solicitudForm">
            <input type="hidden" id="solicitudId" />

            <!-- Selección de la Persona (Campo relacional) -->
            <label for="personaId">Seleccionar Persona</label>
            <select id="personaId">
                <option value="">Seleccione una persona</option>
                <!-- Aquí se poblará dinámicamente -->
            </select>

            <!-- Selección de la Propiedad (Campo relacional) -->
            <label for="propiedadId">Seleccionar Propiedad</label>
            <select id="propiedadId">
                <option value="">Seleccione una propiedad</option>
                <!-- Aquí se poblará dinámicamente -->
            </select>

            <!-- Fecha de visita -->
            <label for="fechaVisita">Fecha de la Visita</label>
            <input type="date" id="fechaVisita" />

            <!-- Comentarios -->
            <label for="comentarios">Comentarios adicionales</label>
            <textarea id="comentarios" placeholder="Escriba comentarios adicionales"></textarea>

            <button class="btn btn-primary" type="submit">Guardar</button>
        </form>

        <!-- Lista de solicitudes -->
        <div id="solicitudList">
            <h2>Lista de Solicitudes</h2>
            <!-- Aquí se mostrarán las solicitudes -->
        </div>
    </div>

    <script>
        
        const apiUrl = 'http://localhost:8000/api/solicitudes';
        const personasUrl = 'http://localhost:8000/api/personas';
        const propiedadesUrl = 'http://localhost:8000/api/propiedades';

        // Mostrar mensaje de estado
        function showMessage(type, message) {
            const messageDiv = document.getElementById('message');
            messageDiv.className = `alert alert-${type}`;
            messageDiv.textContent = message;
            messageDiv.style.display = 'block';

            setTimeout(() => {
                messageDiv.style.display = 'none';
            }, 5000);
        }

        // Poblar las personas en el select
        async function fetchPersonas() {
            try {
                const response = await fetch(personasUrl);
                const personas = await response.json();
                console.log('Personas:', personas)
                const personaSelect = document.getElementById('personaId');
                personas.forEach(persona => {
                    const option = document.createElement('option');
                    option.value = persona.id;
                    option.textContent = `${persona.nombre}`;
                    personaSelect.appendChild(option);
                });
            } catch (error) {
                console.error('Error fetching personas:', error);
            }
        }

        // Poblar las propiedades en el select
        async function fetchPropiedades() {
            try {
                const response = await fetch(propiedadesUrl);
                const propiedades = await response.json();
                console.log('Propiedades:', propiedades); // Log para depuración
                const propiedadSelect = document.getElementById('propiedadId');
                propiedades.forEach(propiedad => {
                    const option = document.createElement('option');
                    option.value = propiedad.id;
                    option.textContent = `${propiedad.direccion}`;
                    propiedadSelect.appendChild(option);
                });
            } catch (error) {
                console.error('Error fetching propiedades:', error);
            }
        }

        // Obtener solicitudes de visita
        async function fetchSolicitudes() {
            try {
                const response = await fetch(apiUrl);
                const solicitudes = await response.json();
                console.log('Solicitudes:', solicitudes); // Log para depuración
                const solicitudList = document.getElementById('solicitudList');
                solicitudList.innerHTML = '<h2>Lista de Solicitudes</h2>';
                if (solicitudes.length === 0) {
                    solicitudList.innerHTML += `
                        <div class="alert alert-danger" role="alert">
                            No se encontraron registros de solicitudes en la base de datos.
                        </div>
                    `;
                    return;
                }

                solicitudes.forEach(solicitud => {
                    const solicitudDiv = document.createElement('div');
                    solicitudDiv.className = 'solicitud-item';
                    
                    solicitudDiv.innerHTML = `
                        <p><strong>Persona:</strong> ${solicitud.persona}</p>
                        <p><strong>Propiedad:</strong> ${solicitud.propiedad}</p>
                        <p><strong>Fecha de Visita:</strong> ${solicitud.fecha_visita}</p>
                        <p><strong>Comentarios:</strong> ${solicitud.comentarios}</p>
                        <button class='btn btn-secondary' onclick="editSolicitud(${solicitud.id})">Editar</button>
                        <button class='btn btn-danger' onclick="deleteSolicitud(${solicitud.id})">Eliminar</button>
                    `;
                    solicitudList.appendChild(solicitudDiv);
                });
            } catch (error) {
                console.error('Error fetching solicitudes:', error);
                showMessage('danger', 'Error al cargar la lista de solicitudes.');
            }
        }

        // Función para crear una nueva solicitud
        async function crearSolicitud(personaId, propiedadId, fechaVisita, comentarios) {
            try {
                const response = await fetch(apiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        personaId: personaId,
                        propiedadId: propiedadId,
                        fechaVisita: fechaVisita,
                        comentarios: comentarios || null,
                    })
                });

                const responseData = await response.json();
                console.log('Respuesta del servidor al crear:', responseData);

                if (response.ok) {
                    // Resetear el formulario solo si fue un éxito
                    document.getElementById('solicitudForm').reset();
                    fetchSolicitudes(); // Actualizar la lista de solicitudes
                    showMessage('success', 'Solicitud creada con éxito.');
                } else {
                    showMessage('danger', `Error: ${responseData.message || 'Error desconocido'}`);
                }
            } catch (error) {
                console.error('Error al crear solicitud:', error);
                showMessage('danger', 'Error al crear solicitud.');
            }
        }

        // Función para actualizar una solicitud existente
        async function actualizarSolicitud(id, personaId, propiedadId, fechaVisita, comentarios) {
    try {
        const response = await fetch(`${apiUrl}/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                persona_id: personaId,  // Cambia según la API espere
                propiedad_id: propiedadId,  // Cambia según la API espere
                fecha_visita: fechaVisita,
                comentarios: comentarios || null,
            })
        });

        const responseData = await response.json();
        console.log('Respuesta del servidor al actualizar:', responseData);

        if (response.ok) {
            document.getElementById('solicitudForm').reset();
            document.getElementById('solicitudId').value = ''; // Limpiar el ID del formulario
            fetchSolicitudes(); // Actualizar la lista de solicitudes
            showMessage('success', 'Solicitud actualizada con éxito.');
        } else {
            showMessage('danger', `Error: ${responseData.message || 'Error desconocido'}`);
        }
    } catch (error) {
        console.error('Error al actualizar solicitud:', error);
        showMessage('danger', 'Error al actualizar solicitud.');
    }
}

        // Evento de envío de formulario para manejar creación y actualización
        async function saveSolicitud(event) {
            event.preventDefault();

            const idValue = document.getElementById('solicitudId').value;
            const id = idValue ? parseInt(idValue, 10) : null; // ID de la solicitud, si existe
            const personaId = parseInt(document.getElementById('personaId').value, 10);
            const propiedadId = parseInt(document.getElementById('propiedadId').value, 10);
            const fechaVisita = document.getElementById('fechaVisita').value;
            const comentarios = document.getElementById('comentarios').value;

            // Validar los campos obligatorios
            if (!personaId || !propiedadId || !fechaVisita) {
                alert('Todos los campos son obligatorios.');
                return;
            }

            // Determinar si es creación o actualización
            if (id) {
                actualizarSolicitud(id, personaId, propiedadId, fechaVisita, comentarios);
            } else {
                crearSolicitud(personaId, propiedadId, fechaVisita, comentarios);
            }
        }

        // Editar solicitud
// Editar solicitud
function editSolicitud(id) {
    fetch(`${apiUrl}/${id}`)
        .then(response => response.json())
        .then(solicitud => {
            document.getElementById('solicitudId').value = solicitud.id;
            document.getElementById('fechaVisita').value = solicitud.fecha_visita.split(' ')[0];
            document.getElementById('comentarios').value = solicitud.comentarios;

            // Seleccionar la opción correcta en el select de personas por nombre
            const personaSelect = document.getElementById('personaId');
            for (const option of personaSelect.options) {
                if (option.text === solicitud.persona) {
                    option.selected = true;
                    break;
                }
            }

            // Seleccionar la opción correcta en el select de propiedades por dirección
            const propiedadSelect = document.getElementById('propiedadId');
            for (const option of propiedadSelect.options) {
                if (option.text === solicitud.propiedad) {
                    option.selected = true;
                    break;
                }
            }
        })
        .catch(error => {
            console.error('Error al obtener solicitud para editar:', error);
            showMessage('danger', 'Error al cargar los datos para edición.');
        });
}

        // Eliminar solicitud
        async function deleteSolicitud(id) {
            if (!confirm('¿Estás seguro de que deseas eliminar esta solicitud?')) {
                return;
            }

            try {
                const response = await fetch(`${apiUrl}/${id}`, {
                    method: 'DELETE'
                });

                const responseData = await response.json();
                console.log('Respuesta del servidor al eliminar:', responseData); //depuración

                if (response.ok) {
                    fetchSolicitudes();
                    showMessage('success', 'Solicitud eliminada con éxito.');
                } else {
                    showMessage('danger', `Error: ${responseData.message || 'Error desconocido'}`);
                }
            } catch (error) {
                console.error('Error al eliminar solicitud:', error);
                showMessage('danger', 'Error al eliminar solicitud.');
            }
        }

        // Volver a la página anterior
        function goBack() {
            window.history.back();
        }

        // Inicialización
        document.getElementById('solicitudForm').addEventListener('submit', saveSolicitud);
        fetchPersonas();
        fetchPropiedades();
        fetchSolicitudes();
    </script>
</body>
</html>
