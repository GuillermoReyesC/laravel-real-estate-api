<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Personas</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 800px; margin: auto; }
        form { margin-bottom: 20px; }
        input, button { margin-bottom: 10px; padding: 8px; width: 100%; box-sizing: border-box; }
        .persona-item { border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; }
        .persona-item button { width: auto; margin-right: 5px; }
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
        <!-- Contenedor para el botón Volver -->
        <h1>Gestión de Personas</h1>

        <!-- Mensaje de estado -->
        <div id="message" class="alert" role="alert" style="display: none;"></div>

        <!-- Formulario para agregar o actualizar persona -->
        <form id="personaForm">
            <input type="hidden" id="personaId" />
            <input type="text" id="nombre" placeholder="Nombre"/>
            <input type="email" id="email" placeholder="Email"/>
            <input type="text" id="telefono" maxlength="8" placeholder="Teléfono"/>
            <button class="btn btn-primary" type="submit">Guardar</button>
        </form>

        <!-- Lista de personas -->
        <div id="personaList">
            <h2>Lista de Personas</h2>
            <!-- Aquí se mostrarán las personas -->
        </div>
    </div>

    <script>
        const apiUrl = 'http://localhost:8000/api/personas';

        // Mostrar mensaje de estado
        function showMessage(type, message) {
            const messageDiv = document.getElementById('message');
            messageDiv.className = `alert alert-${type}`;
            messageDiv.textContent = message;
            messageDiv.style.display = 'block';

            // Ocultar el mensaje después de 5 segundos
            setTimeout(() => {
                messageDiv.style.display = 'none';
            }, 5000);
        }

        async function fetchPersonas() {
            try {
                const response = await fetch(apiUrl);
                const personas = await response.json();
                const personaList = document.getElementById('personaList');
                personaList.innerHTML = '<h2>Lista de Personas</h2>';

                if (personas.length === 0) {
                    personaList.innerHTML += `
                        <div class="alert alert-danger" role="alert">
                            No se encontraron registros de personas en la base de datos.
                        </div>
                    `;
                    return;
                }

                personas.forEach(persona => {
                    const personaDiv = document.createElement('div');
                    personaDiv.className = 'persona-item';
                    personaDiv.innerHTML = `
                        <p><strong>Nombre:</strong> ${persona.nombre}</p>
                        <p><strong>Email:</strong> ${persona.email}</p>
                        <p><strong>Teléfono:</strong> ${persona.telefono}</p>
                        <button class='btn btn-secondary' onclick="editPersona(${persona.id})">Editar</button>
                        <button class='btn btn-danger' onclick="deletePersona(${persona.id})">Eliminar</button>
                    `;
                    personaList.appendChild(personaDiv);
                });
            } catch (error) {
                console.error('Error fetching personas:', error);
                showMessage('danger', 'Error al cargar la lista de personas.');
            }
        }

        // Función para guardar o actualizar una persona
        async function savePersona(event) {
            event.preventDefault();

            // Obtener valores de los campos
            const id = document.getElementById('personaId').value;
            const nombre = document.getElementById('nombre').value;
            const email = document.getElementById('email').value;
            const telefono = document.getElementById('telefono').value;

            // Validaciones
            const telefonoPattern = /^[0-9]{8}$/; // 8 dígitos
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Validar email con regex

            if (!nombre.trim()) {
                alert('El nombre no puede estar vacío.');
                return;
            }

            if (!telefonoPattern.test(telefono)) {
                alert('El teléfono debe contener solo números y tener exactamente 8 dígitos.');
                return;
            }

            if (!emailPattern.test(email)) {
                alert('El email no es válido.');
                return;
            }

            const method = id ? 'PUT' : 'POST';  // Depende del id, usamos create o update (PUT)
            const url = id ? `${apiUrl}/${id}` : apiUrl;

            try {
                const response = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ nombre, email, telefono })
                });

                if (response.ok) {
                    document.getElementById('personaForm').reset();
                    document.getElementById('personaId').value = '';
                    fetchPersonas();
                    showMessage('success', 'Persona guardada con éxito.');
                } else {
                    const errorData = await response.json();
                    console.error('Error al guardar persona:', errorData);
                    alert(`Error: ${errorData.message || 'Error desconocido'}`);
                }
            } catch (error) {
                console.error('Error al guardar persona:', error);
                showMessage('danger', 'Error al guardar persona.');
            }
        }

        // Función para editar una persona
        function editPersona(id) {
            fetch(`${apiUrl}/${id}`)
                .then(response => response.json())
                .then(persona => {
                    document.getElementById('personaId').value = persona.id;
                    document.getElementById('nombre').value = persona.nombre;
                    document.getElementById('email').value = persona.email;
                    document.getElementById('telefono').value = persona.telefono;
                })
                .catch(error => console.error('Error al traer persona:', error));
        }

        // Función para eliminar una persona
        async function deletePersona(id) {
            try {
                const response = await fetch(`${apiUrl}/${id}`, {
                    method: 'DELETE'
                });

                if (response.ok) {
                    fetchPersonas();
                    showMessage('success', 'Persona eliminada con éxito.');
                } else {
                    console.error('Error eliminando persona:', await response.text());
                    showMessage('danger', 'Error al eliminar persona.');
                }
            } catch (error) {
                console.error('Error eliminando persona:', error);
                showMessage('danger', 'Error al eliminar persona.');
            }
        }

        // Configurar el evento del formulario
        document.getElementById('personaForm').addEventListener('submit', savePersona);

        // Cargar la lista de personas al iniciar
        fetchPersonas();

        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>
