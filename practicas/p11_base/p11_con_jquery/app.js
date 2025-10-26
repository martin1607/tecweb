// JSON BASE A MOSTRAR EN FORMULARIO - CORREGIDO
const baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "descripcion": "NA",  // CAMBIADO: detalles -> descripcion
    "imagen": "img/default.png"
};

function init() {
    console.log("Inicializando aplicación con jQuery...");
    var JsonString = JSON.stringify(baseJSON,null,2);
    $('#description').val(JsonString);
    loadProducts();
    setupEventListeners();
}

function setupEventListeners() {
    // Búsqueda en tiempo real
    $('#search').on('input', function() {
        const searchTerm = $(this).val().trim();
        if (searchTerm.length > 0) {
            searchProducts(searchTerm);
        } else {
            loadProducts();
        }
    });
    
    $('#searchBtn').on('click', function() {
        const searchTerm = $('#search').val().trim();
        if (searchTerm.length > 0) {
            searchProducts(searchTerm);
        } else {
            loadProducts();
        }
    });
    
    $('#addProductBtn').on('click', function(e) {
        e.preventDefault();
        addProduct();
    });
    
    $('#updateProductBtn').on('click', function() {
        updateProduct();
    });
}

function loadProducts(searchTerm = '', keepMessage = false) {
    $.ajax({
        url: 'backend/product-list.php',
        type: 'GET',
        data: { search: searchTerm },
        dataType: 'json',
        success: function(response) {
            if (response && response.error) {
                showStatus('error', response.error, true);
                return;
            }
            displayProducts(response);
            if (searchTerm === '' && !keepMessage) {
                $('#product-result').addClass('d-none');
            }
        },
        error: function(xhr, status, error) {
            showStatus('error', 'Error al cargar productos', true);
        }
    });
}

function searchProducts(term) {
    $.ajax({
        url: 'backend/product-search.php',
        type: 'GET',
        data: { search: term },
        dataType: 'json',
        success: function(response) {
            if (response && response.error) {
                showStatus('error', response.error, true);
                return;
            }
            displayProducts(response);
            updateStatusBar(response, term);
        },
        error: function(xhr, status, error) {
            showStatus('error', 'Error en búsqueda', true);
        }
    });
}

function displayProducts(productos) {
    let template = '';
    
    if (productos && productos.length > 0) {
        productos.forEach(producto => {
            let descripcion = '';
            descripcion += '<li>Precio: $'+producto.precio+'</li>';
            descripcion += '<li>Unidades: '+producto.unidades+'</li>';
            descripcion += '<li>Modelo: '+producto.modelo+'</li>';
            descripcion += '<li>Marca: '+producto.marca+'</li>';
            descripcion += '<li>Descripción: '+producto.descripcion+'</li>';  // CAMBIADO: detalles -> descripcion
        
            template += `
                <tr>
                    <td>${producto.id}</td>
                    <td>${producto.nombre}</td>
                    <td><ul>${descripcion}</ul></td>
                    <td>
                        <button class="btn btn-warning btn-sm edit-btn" data-id="${producto.id}">
                            Editar
                        </button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${producto.id}">
                            Eliminar
                        </button>
                    </td>
                </tr>
            `;
        });
    } else {
        template = '<tr><td colspan="4" class="text-center">No se encontraron productos</td></tr>';
    }
    
    $('#products').html(template);
    
    $('.delete-btn').on('click', function() {
        const productId = $(this).data('id');
        deleteProduct(productId);
    });
    
    $('.edit-btn').on('click', function() {
        const productId = $(this).data('id');
        openEditModal(productId);
    });
}

function updateStatusBar(productos, searchTerm) {
    let template_bar = '';
    
    if (productos && productos.length > 0) {
        template_bar = '<strong>Productos encontrados para "' + searchTerm + '":</strong><br>';
        productos.forEach(producto => {
            template_bar += `<li>${producto.nombre}</li>`;
        });
    } else {
        template_bar = '<span class="text-warning">No se encontraron productos para: "' + searchTerm + '"</span>';
    }
    
    $('#product-result').removeClass('d-none');
    $('#container').html(template_bar);
}

function addProduct() {
    const name = $('#name').val().trim();
    const description = $('#description').val();
    
    if (!name) {
        showStatus('error', 'El nombre del producto es requerido', true);
        return;
    }
    
    let productData;
    try {
        productData = JSON.parse(description);
        productData.nombre = name;
    } catch (e) {
        showStatus('error', 'JSON inválido en la descripción', true);
        return;
    }
    
    $.ajax({
        url: 'backend/product-add.php',
        type: 'POST',
        data: JSON.stringify(productData),
        contentType: 'application/json',
        dataType: 'json',
        success: function(response) {
            showStatus(response.status, response.message, true);
            if (response.status === 'success') {
                $('#name').val('');
                $('#description').val(JSON.stringify(baseJSON, null, 2));
                setTimeout(function() {
                    loadProducts('', true);
                }, 3000);
            }
        },
        error: function(xhr, status, error) {
            showStatus('error', 'Error al agregar producto', true);
        }
    });
}

function deleteProduct(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
        $.ajax({
            url: 'backend/product-delete.php',
            type: 'GET',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                showStatus(response.status, response.message, true);
                if (response.status === 'success') {
                    setTimeout(function() {
                        loadProducts('', true);
                    }, 3000);
                }
            },
            error: function(xhr, status, error) {
                showStatus('error', 'Error al eliminar producto', true);
            }
        });
    }
}

// FUNCIONES DE EDICIÓN
function openEditModal(productId) {
    $.ajax({
        url: 'backend/product-get.php',
        type: 'GET',
        data: { id: productId },
        dataType: 'json',
        success: function(response) {
            if (response && response.error) {
                showStatus('error', response.error, true);
                return;
            }
            
            if (response) {
                $('#editProductId').val(response.id);
                $('#editProductName').val(response.nombre);
                $('#editProductPrice').val(response.precio);
                $('#editProductUnits').val(response.unidades);
                $('#editProductModel').val(response.modelo);
                $('#editProductBrand').val(response.marca);
                $('#editProductDetails').val(response.descripcion);  // CAMBIADO: detalles -> descripcion
                $('#editProductImage').val(response.imagen);
                
                $('#editModal').modal('show');
            } else {
                showStatus('error', 'Producto no encontrado', true);
            }
        },
        error: function(xhr, status, error) {
            showStatus('error', 'Error al cargar producto', true);
        }
    });
}

function updateProduct() {
    const productData = {
        id: $('#editProductId').val(),
        nombre: $('#editProductName').val().trim(),
        precio: $('#editProductPrice').val(),
        unidades: $('#editProductUnits').val(),
        modelo: $('#editProductModel').val().trim(),
        marca: $('#editProductBrand').val().trim(),
        descripcion: $('#editProductDetails').val().trim(),  // CAMBIADO: detalles -> descripcion
        imagen: $('#editProductImage').val().trim()
    };
    
    // Validaciones
    if (!productData.nombre) {
        showStatus('error', 'El nombre del producto es requerido', true);
        return;
    }
    
    if (!productData.precio || productData.precio <= 0) {
        showStatus('error', 'El precio debe ser mayor a 0', true);
        return;
    }
    
    if (!productData.unidades || productData.unidades < 0) {
        showStatus('error', 'Las unidades no pueden ser negativas', true);
        return;
    }
    
    $.ajax({
        url: 'backend/product-edit.php',
        type: 'POST',
        data: JSON.stringify(productData),
        contentType: 'application/json',
        dataType: 'json',
        success: function(response) {
            $('#editModal').modal('hide');
            showStatus(response.status, response.message, true);
            if (response.status === 'success') {
                setTimeout(function() {
                    loadProducts('', true);
                }, 3000);
            }
        },
        error: function(xhr, status, error) {
            showStatus('error', 'Error al actualizar producto', true);
        }
    });
}

function showStatus(status, message, keepVisible = false) {
    const statusClass = status === 'success' ? 'text-success' : 'text-danger';
    const template = `
        <li style="list-style: none;" class="${statusClass}"><strong>Status:</strong> ${status}</li>
        <li style="list-style: none;" class="${statusClass}"><strong>Mensaje:</strong> ${message}</li>
    `;
    
    $('#product-result').removeClass('d-none');
    $('#container').html(template);
}

$(document).ready(function() {
    init();
});