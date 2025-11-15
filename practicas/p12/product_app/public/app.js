// JSON BASE A MOSTRAR EN FORMULARIO
var baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
};

$(document).ready(function(){
    let edit = false;

    let JsonString = JSON.stringify(baseJSON,null,2);
    $('#description').val(JsonString);
    $('#product-result').hide();
    listarProductos();

    function listarProductos() {
        console.log("🔄 Iniciando carga de productos...");
        
        $.ajax({
            url: 'product-list.php',
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

    $('#search').keyup(function() {
        if($('#search').val()) {
            let search = $('#search').val();
            $.ajax({
                url: 'product-search.php?search='+search,
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

    $('#product-form').submit(function(e) {
        e.preventDefault();

        let postData = JSON.parse( $('#description').val() );
        postData['nombre'] = $('#name').val();
        postData['id'] = $('#productId').val();

        const url = edit === false ? 'product-add.php' : 'product-edit.php';
        
        console.log("📤 Enviando datos:", postData);
        
        $.post(url, postData, function(response) {
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
        }).fail(function(xhr, status, error) {
            console.error("❌ Error en petición:", error);
            $('#product-result').show();
            $('#container').html('<span class="text-danger">Error al enviar datos al servidor</span>');
        });
    });

    $(document).on('click', '.product-delete', function(e) {
        e.preventDefault();
        if(confirm('¿Realmente deseas eliminar el producto?')) {
            const element = $(this).closest('tr');
            const id = $(element).attr('productId');
            
            console.log("🗑️ Eliminando producto ID:", id);
            
            $.post('product-delete.php', {id: id}, function(response) {
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
            }).fail(function(xhr, status, error) {
                console.error("❌ Error en eliminación:", error);
                $('#product-result').show();
                $('#container').html('<span class="text-danger">Error al eliminar producto</span>');
            });
        }
    });

    $(document).on('click', '.product-item', function(e) {
        e.preventDefault();
        const element = $(this).closest('tr');
        const id = $(element).attr('productId');
        
        console.log("✏️ Cargando producto ID:", id);
        
        $.post('product-single.php', {id: id}, function(response) {
            console.log("📥 Respuesta cruda:", response);
            
            try {
                // Manejar tanto string JSON como objeto
                let product;
                if (typeof response === 'string') {
                    product = JSON.parse(response);
                } else {
                    product = response;
                }
                
                // VERIFICAR SI HAY ERROR
                if (product && product.error) {
                    console.error("❌ Error del servidor:", product.error);
                    alert('Error: ' + product.error);
                    return;
                }
                
                // VERIFICAR QUE TENGA LOS DATOS NECESARIOS
                if (!product || !product.nombre) {
                    console.error("❌ Datos incompletos:", product);
                    alert('Error: Datos del producto incompletos');
                    return;
                }
                
                $('#name').val(product.nombre);
                $('#productId').val(product.id);
                
                // Crear copia para no modificar el original
                let productCopy = {...product};
                delete(productCopy.nombre);
                delete(productCopy.eliminado);
                delete(productCopy.id);
                
                let JsonString = JSON.stringify(productCopy, null, 2);
                $('#description').val(JsonString);
                
                edit = true;
                console.log("✅ Producto cargado para edición:", product.nombre);
                
            } catch (error) {
                console.error("❌ Error procesando producto:", error, "Respuesta:", response);
                alert('Error al procesar el producto. Ver consola para detalles.');
            }
        }).fail(function(xhr, status, error) {
            console.error("❌ Error de conexión:", error);
            alert('Error de conexión con el servidor');
        });
    });    
});