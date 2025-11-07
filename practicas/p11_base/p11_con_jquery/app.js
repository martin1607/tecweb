// Variables globales para validación
let isEditing = false;
let nameValidationTimeout = null;

function init() {
    console.log("Inicializando aplicación con jQuery...");
    loadProducts();
    setupEventListeners();
    setupFieldValidations();
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
    
    // Manejo de envío del formulario PRINCIPAL (para cambiar texto del botón)
    $('#product-form').submit(function(e) {
        e.preventDefault();
        if (isEditing) {
            updateProductFromForm();
        } else {
            addProduct();
        }
        // Cambiar texto del botón a "Agregar Producto" después de enviar
        $('button.btn-primary').text("Agregar Producto");
        isEditing = false;
    });
    
    $('#updateProductBtn').on('click', function() {
        updateProduct();
    });
    
    // Validación asíncrona del nombre del producto
    $('#name').on('input', function() {
        const name = $(this).val().trim();
        
        // Limpiar timeout anterior
        if (nameValidationTimeout) {
            clearTimeout(nameValidationTimeout);
        }
        
        // Esperar 500ms después de que el usuario deje de escribir
        if (name.length > 0) {
            nameValidationTimeout = setTimeout(() => {
                validateProductName(name);
            }, 500);
        } else {
            $('#name-status').html('').hide();
        }
    });
}

function setupFieldValidations() {
    // Validación individual por campo al cambiar el foco
    const fields = ['#name', '#price', '#units', '#model', '#brand', '#description'];
    
    fields.forEach(field => {
        $(field).on('blur', function() {
            validateField($(this));
        });
        
        $(field).on('focus', function() {
            // Mostrar estado del campo cuando tiene foco
            const statusElement = $(this).next('.field-status');
            if (statusElement.html().trim() !== '') {
                statusElement.show();
            }
        });
    });
}

function validateField(field) {
    const fieldId = field.attr('id');
    const value = field.val().trim();
    const statusElement = $(`#${fieldId}-status`);
    
    let isValid = true;
    let message = '';
    
    switch(fieldId) {
        case 'name':
            if (value === '') {
                isValid = false;
                message = '<span class="text-danger">❌ El nombre es requerido</span>';
            } else if (value.length < 3) {
                isValid = false;
                message = '<span class="text-danger">❌ El nombre debe tener al menos 3 caracteres</span>';
            } else {
                isValid = true;
                message = '<span class="text-success">✅ Nombre válido</span>';
            }
            break;
            
        case 'price':
            const price = parseFloat(value);
            if (value === '' || isNaN(price) || price <= 0) {
                isValid = false;
                message = '<span class="text-danger">❌ El precio debe ser mayor a 0</span>';
            } else {
                isValid = true;
                message = '<span class="text-success">✅ Precio válido</span>';
            }
            break;
            
        case 'units':
            const units = parseInt(value);
            if (value === '' || isNaN(units) || units < 0) {
                isValid = false;
                message = '<span class="text-danger">❌ Las unidades no pueden ser negativas</span>';
            } else {
                isValid = true;
                message = '<span class="text-success">✅ Unidades válidas</span>';
            }
            break;
            
        case 'model':
            if (value === '') {
                isValid = false;
                message = '<span class="text-danger">❌ El modelo es requerido</span>';
            } else {
                isValid = true;
                message = '<span class="text-success">✅ Modelo válido</span>';
            }
            break;
            
        case 'brand':
            if (value === '') {
                isValid = false;
                message = '<span class="text-danger">❌ La marca es requerida</span>';
            } else {
                isValid = true;
                message = '<span class="text-success">✅ Marca válida</span>';
            }
            break;
            
        case 'description':
            if (value === '') {
                isValid = false;
                message = '<span class="text-danger">❌ La descripción es requerida</span>';
            } else if (value.length < 10) {
                isValid = false;
                message = '<span class="text-danger">❌ La descripción debe tener al menos 10 caracteres</span>';
            } else {
                isValid = true;
                message = '<span class="text-success">✅ Descripción válida</span>';
            }
            break;
    }
    
    statusElement.html(message).show();
    
    // Actualizar apariencia del campo
    if (isValid) {
        field.removeClass('is-invalid').addClass('is-valid');
    } else {
        field.removeClass('is-valid').addClass('is-invalid');
    }
    
    return isValid;
}

function validateProductName(name) {
    $.ajax({
        url: 'backend/product-search.php',
        type: 'GET',
        data: { search: name, exact: true },
        dataType: 'json',
        success: function(response) {
            const statusElement = $('#name-status');
            if (response && response.length > 0) {
                // Producto ya existe
                statusElement.html('<span class="text-danger">⚠ Este producto ya existe en la base de datos</span>').show();
                $('#name').removeClass('is-valid').addClass('is-invalid');
            } else {
                // Producto disponible
                statusElement.html('<span class="text-success">✅ Nombre disponible</span>').show();
                $('#name').removeClass('is-invalid').addClass('is-valid');
            }
        },
        error: function(xhr, status, error) {
            $('#name-status').html('<span class="text-warning">⚠ Error al validar nombre</span>').show();
        }
    });
}

function validateAllFields() {
    const fields = ['#name', '#price', '#units', '#model', '#brand', '#description'];
    let allValid = true;
    
    fields.forEach(field => {
        const fieldElement = $(field);
        if (!validateField(fieldElement)) {
            allValid = false;
        }
    });
    
    return allValid;
}

// FUNCIÓN PARA CAMBIAR TEXTO DEL BOTÓN AL HACER CLIC EN PRODUCTO
$(document).on('click', '.product-item', function(e) {
    if (!$(e.target).hasClass('edit-btn') && !$(e.target).hasClass('delete-btn')) {
        const productId = $(this).find('.edit-btn').data('id');
        openEditModal(productId);
        // Cambiar texto del botón a "Modificar Producto"
        $('button.btn-primary').text("Modificar Producto");
        isEditing = true;
    }
});

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
            descripcion += '<li>Descripción: '+producto.descripcion+'</li>';
        
            template += `
                <tr class="product-item">
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
    
    $('.delete-btn').on('click', function(e) {
        e.stopPropagation();
        const productId = $(this).data('id');
        deleteProduct(productId);
    });
    
    $('.edit-btn').on('click', function(e) {
        e.stopPropagation();
        const productId = $(this).data('id');
        openEditModal(productId);
        // Cambiar texto del botón a "Modificar Producto"
        $('button.btn-primary').text("Modificar Producto");
        isEditing = true;
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
    // Validar todos los campos antes de enviar
    if (!validateAllFields()) {
        showGeneralStatus('error', 'Por favor, corrige los errores en el formulario antes de continuar.');
        return;
    }
    
    const productData = {
        nombre: $('#name').val().trim(),
        precio: parseFloat($('#price').val()),
        unidades: parseInt($('#units').val()),
        modelo: $('#model').val().trim(),
        marca: $('#brand').val().trim(),
        descripcion: $('#description').val().trim(),
        imagen: $('#image').val().trim() || 'img/default.png'
    };
    
    $.ajax({
        url: 'backend/product-add.php',
        type: 'POST',
        data: JSON.stringify(productData),
        contentType: 'application/json',
        dataType: 'json',
        success: function(response) {
            showStatus(response.status, response.message, true);
            if (response.status === 'success') {
                // Limpiar formulario
                $('#product-form')[0].reset();
                $('.field-status').html('').hide();
                $('.form-control').removeClass('is-valid is-invalid');
                showGeneralStatus('success', 'Producto agregado correctamente');
                
                setTimeout(function() {
                    loadProducts('', true);
                    $('#general-status').hide();
                }, 3000);
            }
        },
        error: function(xhr, status, error) {
            showStatus('error', 'Error al agregar producto', true);
            showGeneralStatus('error', 'Error al agregar producto');
        }
    });
}

function updateProductFromForm() {
    // Validar todos los campos antes de enviar
    if (!validateAllFields()) {
        showGeneralStatus('error', 'Por favor, corrige los errores en el formulario antes de continuar.');
        return;
    }
    
    const productData = {
        id: $('#productId').val(),
        nombre: $('#name').val().trim(),
        precio: parseFloat($('#price').val()),
        unidades: parseInt($('#units').val()),
        modelo: $('#model').val().trim(),
        marca: $('#brand').val().trim(),
        descripcion: $('#description').val().trim(),
        imagen: $('#image').val().trim() || 'img/default.png'
    };
    
    $.ajax({
        url: 'backend/product-edit.php',
        type: 'POST',
        data: JSON.stringify(productData),
        contentType: 'application/json',
        dataType: 'json',
        success: function(response) {
            showStatus(response.status, response.message, true);
            if (response.status === 'success') {
                // Limpiar formulario
                $('#product-form')[0].reset();
                $('.field-status').html('').hide();
                $('.form-control').removeClass('is-valid is-invalid');
                $('#productId').val('');
                showGeneralStatus('success', 'Producto modificado correctamente');
                
                setTimeout(function() {
                    loadProducts('', true);
                    $('#general-status').hide();
                }, 3000);
            }
        },
        error: function(xhr, status, error) {
            showStatus('error', 'Error al modificar producto', true);
            showGeneralStatus('error', 'Error al modificar producto');
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

// FUNCIONES DE EDICIÓN (modal)
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
                // Llenar formulario principal con datos del producto
                $('#productId').val(response.id);
                $('#name').val(response.nombre);
                $('#price').val(response.precio);
                $('#units').val(response.unidades);
                $('#model').val(response.modelo);
                $('#brand').val(response.marca);
                $('#description').val(response.descripcion);
                $('#image').val(response.imagen);
                
                // Validar campos automáticamente
                validateAllFields();
                
                // También llenar modal por si acaso
                $('#editProductId').val(response.id);
                $('#editProductName').val(response.nombre);
                $('#editProductPrice').val(response.precio);
                $('#editProductUnits').val(response.unidades);
                $('#editProductModel').val(response.modelo);
                $('#editProductBrand').val(response.marca);
                $('#editProductDetails').val(response.descripcion);
                $('#editProductImage').val(response.imagen);
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
        descripcion: $('#editProductDetails').val().trim(),
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

function showGeneralStatus(status, message) {
    const statusBar = $('#general-status');
    statusBar.removeClass('status-success status-error')
             .addClass(status === 'success' ? 'status-success' : 'status-error')
             .html(message)
             .show();
}

$(document).ready(function() {
    init();
});