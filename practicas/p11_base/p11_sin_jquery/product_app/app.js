// JSON BASE A MOSTRAR EN FORMULARIO
const baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
};

function init() {
    var JsonString = JSON.stringify(baseJSON,null,2);
    document.getElementById("description").value = JsonString;
    listarProductos();
}

// FUNCIÓN PARA CARGAR TODOS LOS PRODUCTOS
function listarProductos() {
    var client = getXMLHttpRequest();
    client.open('GET', './backend/product-list.php', true);
    client.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    client.onreadystatechange = function () {
        if (client.readyState == 4 && client.status == 200) {
            let productos = JSON.parse(client.responseText);
            document.getElementById("product-result").className = "card my-4 d-none";
            
            if(productos && productos.length > 0) {
                let template = '';
                productos.forEach(producto => {
                    let descripcion = '';
                    descripcion += '<li>Precio: $'+producto.precio+'</li>';
                    descripcion += '<li>Unidades: '+producto.unidades+'</li>';
                    descripcion += '<li>Modelo: '+producto.modelo+'</li>';
                    descripcion += '<li>Marca: '+producto.marca+'</li>';
                    descripcion += '<li>Detalles: '+producto.detalles+'</li>';
                
                    template += `
                        <tr>
                            <td>${producto.id}</td>
                            <td>${producto.nombre}</td>
                            <td><ul>${descripcion}</ul></td>
                            <td>
                                <button class="btn btn-danger btn-sm" onclick="eliminarProducto(${producto.id})">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    `;
                });
                document.getElementById("products").innerHTML = template;
            } else {
                document.getElementById("products").innerHTML = '<tr><td colspan="4" class="text-center">No hay productos registrados</td></tr>';
            }
        }
    };
    client.send();
}

// FUNCIÓN DE BÚSQUEDA - MEJORADA
function buscarProducto() {
    var search = document.getElementById('search').value.trim();
    
    if (search === '') {
        listarProductos();
        return;
    }

    var client = getXMLHttpRequest();
    client.open('GET', './backend/product-search.php?search=' + encodeURIComponent(search), true);
    client.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    client.onreadystatechange = function () {
        if (client.readyState == 4 && client.status == 200) {
            let productos = JSON.parse(client.responseText);
            
            if(productos && productos.length > 0) {
                let template = '';
                let template_bar = '<strong>Productos encontrados:</strong><br>';

                productos.forEach(producto => {
                    let descripcion = '';
                    descripcion += '<li>Precio: $'+producto.precio+'</li>';
                    descripcion += '<li>Unidades: '+producto.unidades+'</li>';
                    descripcion += '<li>Modelo: '+producto.modelo+'</li>';
                    descripcion += '<li>Marca: '+producto.marca+'</li>';
                    descripcion += '<li>Detalles: '+producto.detalles+'</li>';
                
                    template += `
                        <tr>
                            <td>${producto.id}</td>
                            <td>${producto.nombre}</td>
                            <td><ul>${descripcion}</ul></td>
                            <td>
                                <button class="btn btn-danger btn-sm" onclick="eliminarProducto(${producto.id})">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    `;

                    template_bar += `<li>${producto.nombre}</li>`;
                });
                
                document.getElementById("product-result").className = "card my-4 d-block";
                document.getElementById("container").innerHTML = template_bar;  
                document.getElementById("products").innerHTML = template;
            } else {
                document.getElementById("product-result").className = "card my-4 d-block";
                document.getElementById("container").innerHTML = '<span class="text-warning">No se encontraron productos para: "' + search + '"</span>';
                document.getElementById("products").innerHTML = '<tr><td colspan="4" class="text-center">No hay productos que coincidan</td></tr>';
            }
        }
    };
    client.send();
}

// FUNCIÓN AGREGAR PRODUCTO
function agregarProducto(e) {
    e.preventDefault();

    var productoJsonString = document.getElementById('description').value;
    var finalJSON = JSON.parse(productoJsonString);
    finalJSON['nombre'] = document.getElementById('name').value;
    productoJsonString = JSON.stringify(finalJSON,null,2);

    if (!finalJSON.nombre || finalJSON.nombre.trim() === '') {
        mostrarEstado('error', 'El nombre del producto es requerido');
        return;
    }

    var client = getXMLHttpRequest();
    client.open('POST', './backend/product-add.php', true);
    client.setRequestHeader('Content-Type', "application/json;charset=UTF-8");
    client.onreadystatechange = function () {
        if (client.readyState == 4 && client.status == 200) {
            let respuesta = JSON.parse(client.responseText);
            mostrarEstado(respuesta.status, respuesta.message);

            if(respuesta.status === 'success') {
                document.getElementById('name').value = '';
                document.getElementById('description').value = JSON.stringify(baseJSON,null,2);
                listarProductos();
            }
        }
    };
    client.send(productoJsonString);
}

// FUNCIÓN ELIMINAR PRODUCTO
function eliminarProducto(id) {
    if(confirm("¿Estás seguro de que deseas eliminar este producto?")) {
        var client = getXMLHttpRequest();
        client.open('GET', './backend/product-delete.php?id='+id, true);
        client.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        client.onreadystatechange = function () {
            if (client.readyState == 4 && client.status == 200) {
                let respuesta = JSON.parse(client.responseText);
                mostrarEstado(respuesta.status, respuesta.message);
                listarProductos();
            }
        };
        client.send();
    }
}

// FUNCIÓN MOSTRAR ESTADO
function mostrarEstado(status, message) {
    let template_bar = '';
    let statusClass = status === 'success' ? 'text-success' : 'text-danger';
    
    template_bar += `
        <li style="list-style: none;" class="${statusClass}"><strong>Status:</strong> ${status}</li>
        <li style="list-style: none;" class="${statusClass}"><strong>Mensaje:</strong> ${message}</li>
    `;

    document.getElementById("product-result").className = "card my-4 d-block";
    document.getElementById("container").innerHTML = template_bar;
}

// FUNCIÓN XMLHttpRequest
function getXMLHttpRequest() {
    try{
        return new XMLHttpRequest();
    }catch(err1){
        try{
            return new ActiveXObject("Msxml2.XMLHTTP");
        }catch(err2){
            try{
                return new ActiveXObject("Microsoft.XMLHTTP");
            }catch(err3){
                return false;
            }
        }
    }
}