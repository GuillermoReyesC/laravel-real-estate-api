<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Propiedades</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 800px; margin: auto; }
        form { margin-bottom: 20px; }
        input, button { margin-bottom: 10px; padding: 8px; width: 100%; box-sizing: border-box; }
        .propiedad-item { border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; }
        .propiedad-item button { width: auto; margin-right: 5px; }
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
        <h1>Gestión de Propiedades</h1>
        <!-- Mensaje de estado -->
        <div id="message" class="alert" role="alert" style="display: none;"></div>

        <!-- Formulario para agregar o actualizar propiedad -->
        <form id="propiedadForm">
            <input type="hidden" id="propiedadId" />
            <input type="text" id="direccion" placeholder="Dirección" required />
            <input type="text" id="ciudad" placeholder="Ciudad" required />
            <input type="number" id="precio" placeholder="Precio" min="0" step="0.01" required />
            <textarea class="w-100" id="descripcion" placeholder="Descripción" rows="3"></textarea>
            <button class="btn btn-primary" type="submit">Guardar</button>
        </form>

        <!-- Lista de propiedades -->
        <div id="propiedadList">
            <h2>Lista de Propiedades</h2>
        </div>
    </div>

    <script>
        const apiUrl = 'http://localhost:8000/api/propiedades';

        // Función para mostrar mensajes
        function showMessage(type, message) {
            const messageDiv = document.getElementById('message');
            messageDiv.className = `alert alert-${type}`;
            messageDiv.textContent = message;
            messageDiv.style.display = 'block';

            setTimeout(() => {
                messageDiv.style.display = 'none';
            }, 5000);
        }

        // Obtener y mostrar las propiedades
        async function fetchPropiedades() {
            try {
                const response = await fetch(apiUrl);
                const propiedades = await response.json();
                const propiedadList = document.getElementById('propiedadList');
                propiedadList.innerHTML = '<h2>Lista de Propiedades</h2>';

                if (propiedades.length === 0) {
                    propiedadList.innerHTML += `
                        <div class="alert alert-danger" role="alert">
                            No se encontraron registros de propiedades en la base de datos.
                        </div>
                    `;
                    return;
                }
                console.log(response,propiedades)

                propiedades.forEach(propiedad => {
                    const propiedadDiv = document.createElement('div');
                    propiedadDiv.className = 'propiedad-item';
                    propiedadDiv.innerHTML = `
                        <p><strong>Dirección:</strong> ${propiedad.direccion}</p>
                        <p><strong>Ciudad:</strong> ${propiedad.ciudad}</p>
                        <p><strong>Descripción:</strong> ${propiedad.descripcion}</p>
                        <p><strong>Precio:</strong> ${formatPrice(propiedad.precio)}</p>
                        <button class='btn btn-secondary' onclick="editPropiedad(${propiedad.id})">Editar</button>
                        <button class='btn btn-danger' onclick="deletePropiedad(${propiedad.id})">Eliminar</button>
                    `;
                    propiedadList.appendChild(propiedadDiv);
                });
            } catch (error) {
                console.error('Error fetching propiedades:', error);
                showMessage('danger', 'Error al cargar la lista de propiedades.');
            }
        }

        // Guardar o actualizar propiedad
        async function savePropiedad(event) {
            event.preventDefault();

            const id = parseInt(document.getElementById('propiedadId').value, 10); // Convertir a entero
            const direccion = document.getElementById('direccion').value;
            const ciudad = document.getElementById('ciudad').value;
            const descripcion = document.getElementById('descripcion').value;
            const precio = parseFloat(document.getElementById('precio').value).toFixed(2); // Convertir a decimal

            if (isNaN(precio) || precio <= 0) {
                alert('El precio debe ser un número positivo.');
                return;
            }

            // Mostrar los datos en la consola
            console.log('Datos a enviar:', {
                id,
                direccion,
                ciudad,
                descripcion,
                precio
            });
            console.log('Tipos de dato:', {
                id: typeof id,
                direccion: typeof direccion,
                ciudad: typeof ciudad,
                descripcion: typeof descripcion,
                precio: typeof precio
            });

            const method = id ? 'PUT' : 'POST';
            const url = id ? `${apiUrl}/${id}` : apiUrl;

            try {
                const response = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        direccion,
                        ciudad,
                        descripcion,
                        precio: parseFloat(precio) // Asegúrate de enviar como decimal
                    })
                });

                if (response.ok) {
                    document.getElementById('propiedadForm').reset();
                    document.getElementById('propiedadId').value = '';
                    fetchPropiedades();
                    showMessage('success', 'Propiedad guardada con éxito.');
                } else {
                    const errorData = await response.json();
                    showMessage('danger', `Error: ${errorData.error || 'Error desconocido'}`);
                }
            } catch (error) {
                console.error('Error al guardar propiedad:', error);
                showMessage('danger', 'Error al guardar propiedad.');
            }
        }

        // Editar una propiedad
        function editPropiedad(id) {
            fetch(`${apiUrl}/${id}`)
                .then(response => response.json())
                .then(propiedad => {
                    document.getElementById('propiedadId').value = propiedad.id;
                    document.getElementById('direccion').value = propiedad.direccion;
                    document.getElementById('ciudad').value = propiedad.ciudad;
                    document.getElementById('descripcion').value = propiedad.descripcion;
                    document.getElementById('precio').value = propiedad.precio;
                })
                .catch(error => {
                    console.error('Error al traer propiedad:', error);
                    showMessage('danger', 'Error al traer propiedad.');
                });
        }

        // Eliminar una propiedad
        async function deletePropiedad(id) {
            try {
                const response = await fetch(`${apiUrl}/${id}`, {
                    method: 'DELETE'
                });

                // Mostrar los datos en la consola
            console.log('Datos a enviar:', {
                id
                
            });
            console.log('Tipos de dato:', {
                id: typeof id,
                
            });
                if (response.ok) {
                    fetchPropiedades();
                    showMessage('success', 'Propiedad eliminada con éxito.');
                } else {
                    const errorData = await response.json();
                    showMessage('danger', `Error: ${errorData.error || 'Error desconocido'}`);
                }
            } catch (error) {
                console.error('Error al eliminar propiedad:', error);
                showMessage('danger', 'Error al eliminar propiedad.');
            }
        }

        document.getElementById('propiedadForm').addEventListener('submit', savePropiedad);
        fetchPropiedades();

        function goBack() {
            window.history.back();
        }

        function formatPrice(price) {
            return new Intl.NumberFormat('es-CL', {
                style: 'currency',
                currency: 'CLP',
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            }).format(price);
        }
    </script>
</body>
</html>
