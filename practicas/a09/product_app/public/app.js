// JSON BASE A MOSTRAR EN FORMULARIO
var baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
};

// Base del API (index.php de Slim en el mismo directorio que index.html)
const API = 'index.php';

$(document).ready(function(){
    let edit = false;

    let JsonString = JSON.stringify(baseJSON,null,2);
    $('#description').val(JsonString);
    $('#product-result').hide();
    listarProductos();

    // --------------------------------------------------------
    // LISTAR PRODUCTOS  -> GET index.php/products
    // --------------------------------------------------------
    function listarProductos() {
        console.log("🔄 Iniciando carga de productos...");
        
        $.ajax({
            url: `${API}/products`,
            type: 'GET',
            dataType: 'json',
            success: function(productos) {
                console.log("✅ Productos recibidos:", productos);
                
                if (productos && productos.length > 0) {
                    let template = '';

                    productos.forEach(producto => {
                        let descripcion = '';
                        descripcion += '<li>Precio: $'+producto.precio+'</li>';
                        descripcion += '<li>Unidades: '+producto.unidades+'</li>';
                        descripcion += '<li>Modelo: '+producto.modelo+'</li>';
                        descripcion += '<li>Marca: '+producto.marca+'</li>';
                        descripcion += '<li>Detalles: '+producto.detalles+'</li>';
                    
                        template += `
                            <tr productId="${producto.id}">
                                <td>${producto.id}</td>
                                <td><a href="#" class="product-item">${producto.nombre}</a></td>
                                <td><ul>${descripcion}</ul></td>
                                <td>
                                    <button class="product-delete btn btn-danger">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    
                    $('#products').html(template);
                    console.log("🎉 " + productos.length + " productos mostrados");
                } else {
                    $('#products').html('<tr><td colspan="4" class="text-center">No se encontraron productos</td></tr>');
                    console.log("ℹ️ No hay productos");
                }
            },
            error: function(xhr, status, error) {
                console.error("❌ Error AJAX:", error);
                $('#products').html('<tr><td colspan="4" class="text-center text-danger">Error al cargar productos</td></tr>');
            }
        });
    }

    // --------------------------------------------------------
    // BUSCAR PRODUCTOS -> GET index.php/products/{search}
    // --------------------------------------------------------
    $('#search').keyup(function() {
        if($('#search').val()) {
            let search = $('#search').val();
            $.ajax({
                url: `${API}/products/${encodeURIComponent(search)}`,
                type: 'GET',
                dataType: 'json',
                success: function (productos) {
                    console.log("🔍 Resultados búsqueda:", productos);
                    
                    if(productos && productos.length > 0) {
                        let template = '';
                        let template_bar = '';

                        productos.forEach(producto => {
                            let descripcion = '';
                            descripcion += '<li>Precio: $'+producto.precio+'</li>';
                            descripcion += '<li>Unidades: '+producto.unidades+'</li>';
                            descripcion += '<li>Modelo: '+producto.modelo+'</li>';
                            descripcion += '<li>Marca: '+producto.marca+'</li>';
                            descripcion += '<li>Detalles: '+producto.detalles+'</li>';
                        
                            template += `
                                <tr productId="${producto.id}">
                                    <td>${producto.id}</td>
                                    <td><a href="#" class="product-item">${producto.nombre}</a></td>
                                    <td><ul>${descripcion}</ul></td>
                                    <td>
                                        <button class="product-delete btn btn-danger">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            `;

                            template_bar += `
                                <li>${producto.nombre}</li>
                            `;
                        });
                        
                        $('#product-result').show();
                        $('#container').html(template_bar);
                        $('#products').html(template);
                        console.log("🔍 " + productos.length + " productos encontrados en búsqueda");
                    } else {
                        $('#product-result').show();
                        $('#container').html('<span class="text-warning">No se encontraron productos para: "' + search + '"</span>');
                        $('#products').html('<tr><td colspan="4" class="text-center">No se encontraron productos</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("❌ Error en búsqueda:", error);
                    $('#product-result').show();
                    $('#container').html('<span class="text-danger">Error en la búsqueda</span>');
                }
            });
        }
        else {
            $('#product-result').hide();
            listarProductos();
        }
    });

    // --------------------------------------------------------
    // AGREGAR / EDITAR PRODUCTO
    //  - POST index.php/product  (crear)
    //  - PUT  index.php/product  (editar)
    // --------------------------------------------------------
    $('#product-form').submit(function(e) {
        e.preventDefault();

        let postData = JSON.parse( $('#description').val() );
        postData['nombre'] = $('#name').val();
        postData['id'] = $('#productId').val();

        const method = edit === false ? 'POST' : 'PUT';
        
        console.log("📤 Enviando datos ("+method+"):", postData);
        
        $.ajax({
            url: `${API}/product`,
            type: method,
            data: postData,
            success: function(response) {
                console.log("📥 Respuesta del servidor:", response);
                
                try {
                    let respuesta;
                    if (typeof response === 'string') {
                        respuesta = JSON.parse(response);
                    } else {
                        respuesta = response;
                    }
                    
                    let template_bar = '';
                    template_bar += `
                        <li style="list-style: none;">Status: ${respuesta.status}</li>
                        <li style="list-style: none;">Mensaje: ${respuesta.message}</li>
                    `;
                    
                    $('#name').val('');
                    $('#productId').val('');
                    $('#description').val(JsonString);
                    $('#product-result').show();
                    $('#container').html(template_bar);
                    listarProductos();
                    edit = false;
                    
                    console.log("✅ Operación completada: " + respuesta.message);
                    
                } catch (error) {
                    console.error("❌ Error parseando respuesta:", error);
                    $('#product-result').show();
                    $('#container').html('<span class="text-danger">Error en la respuesta del servidor</span>');
                }
            },
            error: function(xhr, status, error) {
                console.error("❌ Error en petición:", error);
                $('#product-result').show();
                $('#container').html('<span class="text-danger">Error al enviar datos al servidor</span>');
            }
        });
    });

    // --------------------------------------------------------
    // ELIMINAR PRODUCTO -> DELETE index.php/product
    // --------------------------------------------------------
    $(document).on('click', '.product-delete', function(e) {
        e.preventDefault();
        if(confirm('¿Realmente deseas eliminar el producto?')) {
            const element = $(this).closest('tr');
            const id = $(element).attr('productId');
            
            console.log("🗑️ Eliminando producto ID:", id);
            
            $.ajax({
                url: `${API}/product`,
                type: 'DELETE',
                data: { id: id },
                success: function(response) {
                    try {
                        let respuesta;
                        if (typeof response === 'string') {
                            respuesta = JSON.parse(response);
                        } else {
                            respuesta = response;
                        }
                        
                        $('#product-result').show();
                        $('#container').html(`
                            <li style="list-style: none;">Status: ${respuesta.status}</li>
                            <li style="list-style: none;">Mensaje: ${respuesta.message}</li>
                        `);
                        listarProductos();
                        console.log("✅ Producto eliminado: " + respuesta.message);
                    } catch (error) {
                        console.error("❌ Error eliminando:", error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("❌ Error en eliminación:", error);
                    $('#product-result').show();
                    $('#container').html('<span class="text-danger">Error al eliminar producto</span>');
                }
            });
        }
    });

    // --------------------------------------------------------
    // CARGAR PRODUCTO PARA EDICIÓN -> GET index.php/product/{id}
    // --------------------------------------------------------
    $(document).on('click', '.product-item', function(e) {
        e.preventDefault();
        const element = $(this).closest('tr');
        const id = $(element).attr('productId');
        
        console.log("✏️ Cargando producto ID:", id);
        
        $.ajax({
            url: `${API}/product/${id}`,
            type: 'GET',
            dataType: 'json',
            success: function(product) {
                console.log("📥 Producto recibido:", product);
                
                if (product && product.error) {
                    console.error("❌ Error del servidor:", product.error);
                    alert('Error: ' + product.error);
                    return;
                }
                
                if (!product || !product.nombre) {
                    console.error("❌ Datos incompletos:", product);
                    alert('Error: Datos del producto incompletos');
                    return;
                }
                
                $('#name').val(product.nombre);
                $('#productId').val(product.id);
                
                let productCopy = {...product};
                delete productCopy.nombre;
                delete productCopy.eliminado;
                delete productCopy.id;
                
                let JsonStringLocal = JSON.stringify(productCopy, null, 2);
                $('#description').val(JsonStringLocal);
                
                edit = true;
                console.log("✅ Producto cargado para edición:", product.nombre);
            },
            error: function(xhr, status, error) {
                console.error("❌ Error de conexión:", error);
                alert('Error de conexión con el servidor');
            }
        });
    });    
});
