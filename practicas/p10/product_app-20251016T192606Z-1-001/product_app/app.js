// Función para inicializar la aplicación
function init() {
    cargarProductos();
}

// Función para cargar todos los productos
function cargarProductos() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'backend/read.php', true);
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (!response.error) {
                mostrarProductos(response);
            }
        }
    };
    
    xhr.send();
}

// Función para buscar por ID
function buscarID(event) {
    event.preventDefault();
    const id = document.getElementById('search').value;
    
    if (!id) {
        alert('Por favor, ingresa un ID para buscar');
        return;
    }
    
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `backend/read.php?id=${id}`, true);
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            
            if (response.error) {
                alert(response.error);
                document.getElementById('productos').innerHTML = '';
            } else {
                alert('Producto encontrado');
                mostrarProductos([response]);
            }
        } else {
            alert('Error en la conexión');
        }
    };
    
    xhr.send();
}

// Función para mostrar productos en la tabla
function mostrarProductos(productos) {
    const tbody = document.getElementById('productos');
    tbody.innerHTML = '';
    
    if (productos.length === 0) {
        tbody.innerHTML = '<tr><td colspan="3" style="text-align: center;">No se encontraron productos</td></tr>';
        return;
    }
    
    productos.forEach(producto => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${producto.id}</td>
            <td>${producto.nombre}</td>
            <td>${producto.descripcion}</td>
        `;
        tbody.appendChild(tr);
    });
}

// Función para agregar producto (Ejercicio 3)
function agregarProducto(event) {
    event.preventDefault();
    
    const nombre = document.getElementById('name').value;
    const descripcion = document.getElementById('description').value;
    
    // Validar que los campos no estén vacíos
    if (!nombre || !descripcion) {
        alert('Todos los campos son obligatorios');
        return;
    }
    
    // Validar que la descripción sea un JSON válido
    let productoData;
    try {
        productoData = JSON.parse(descripcion);
    } catch (e) {
        alert('Error: La descripción debe ser un JSON válido');
        return;
    }
    
    // Validaciones de los campos del JSON (práctica anterior)
    if (!productoData.marca || !productoData.modelo || !productoData.precio || !productoData.cantidad) {
        alert('Error: El JSON debe contener marca, modelo, precio y cantidad');
        return;
    }
    
    if (isNaN(productoData.precio) || parseFloat(productoData.precio) <= 0) {
        alert('Error: El precio debe ser un número mayor a 0');
        return;
    }
    
    if (isNaN(productoData.cantidad) || parseInt(productoData.cantidad) <= 0) {
        alert('Error: La cantidad debe ser un número mayor a 0');
        return;
    }
    
    // Crear objeto con todos los datos del producto
    const producto = {
        nombre: nombre,
        descripcion: productoData.descripcion || descripcion,
        marca: productoData.marca,
        modelo: productoData.modelo,
        precio: parseFloat(productoData.precio),
        cantidad: parseInt(productoData.cantidad)
    };
    
    // Enviar datos al servidor
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'backend/create.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            
            if (response.success) {
                alert(response.message);
                // Limpiar formulario
                document.getElementById('name').value = '';
                document.getElementById('description').value = '';
                // Recargar productos
                cargarProductos();
            } else {
                alert('Error: ' + response.message);
            }
        } else {
            alert('Error en la conexión con el servidor');
        }
    };
    
    xhr.onerror = function() {
        alert('Error en la conexión');
    };
    
    // Enviar datos como JSON
    xhr.send(JSON.stringify(producto));
}