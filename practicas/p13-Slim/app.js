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
    
    // Manejo de envío del formulario PRINCIPAL
    $('#product-form').submit(function(e) {
        e.preventDefault();
        if (isEditing) {
            updateProductFromForm();
        } else {
            addProduct();
        }
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
        data: { search: name },
        success: function(response) {
            try {
                const productos = JSON.parse(response);
                const statusElement = $('#name-status');
                
                if (productos && productos.length > 0) {
                    // Producto ya existe
                    statusElement.html('<span class="text-danger">⚠ Este producto ya existe</span>').show();
                    $('#name').removeClass('is-valid').addClass('is-invalid');
                } else {
                    // Producto disponible
                    statusElement.html('<span class="text-success">✅ Nombre disponible</span>').show();
                    $('#name').removeClass('is-invalid').addClass('is-valid');
                }
            } catch (e) {
                console.error('Error parsing search response:', e);
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

function loadProducts(searchTerm = '') {
    $.ajax({
        url: 'backend/product-list.php',
        type: 'GET',
        success: function(response) {
            try {
                const productos = JSON.parse(response);
                displayProducts(productos);
                if (searchTerm === '') {
                    $('#product-result').hide();
                }
            } catch (e) {
                console.error('Error parsing product list:', e);
                showStatus('error', 'Error al cargar productos', true);
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
        success: function(response) {
            try {
                const productos = JSON.parse(response);
                displayProducts(productos);
                updateStatusBar(productos, term);
            } catch (e) {
                console.error('Error parsing search results:', e);
                showStatus('error', 'Error en búsqueda', true);
            }
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
                <tr productId="${producto.id}">
                    <td>${producto.id}</td>
                    <td><a href="#" class="product-item">${producto.nombre}</a></td>
                    <td><ul>${descripcion}</ul></td>
                    <td>
                        <button class="btn btn-danger btn-sm product-delete">
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
}

function updateStatusBar(productos, searchTerm) {
    let template_bar = '';
    
    if (productos && productos.length > 0) {
        template_bar = '<strong>Productos encontrados para "' + searchTerm + '":</strong><br>';
        productos.forEach(producto => {
            template_bar += `<li>${producto.nombre}</li>`;
        });
        $('#product-result').show();
    } else {
        template_bar = '<span class="text-warning">No se encontraron productos para: "' + searchTerm + '"</span>';
        $('#product-result').show();
    }
    
    $('#container').html(template_bar);
}

function addProduct() {
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
    
    $.post('./backend/product-add.php', productData, function(response) {
        try {
            const respuesta = JSON.parse(response);
            showStatus(respuesta.status, respuesta.message, true);
            
            if (respuesta.status === 'success') {
                // Limpiar formulario
                $('#product-form')[0].reset();
                $('.field-status').html('').hide();
                $('.form-control').removeClass('is-valid is-invalid');
                showGeneralStatus('success', 'Producto agregado correctamente');
                
                // Recargar productos después de 1 segundo
                setTimeout(function() {
                    loadProducts();
                    $('#general-status').hide();
                }, 1000);
            }
        } catch (e) {
            console.error('Error parsing add response:', e);
            showStatus('error', 'Error al procesar respuesta del servidor', true);
        }
    }).fail(function() {
        showStatus('error', 'Error al agregar producto', true);
    });
}

function updateProductFromForm() {
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
    
    $.post('./backend/product-edit.php', productData, function(response) {
        try {
            const respuesta = JSON.parse(response);
            showStatus(respuesta.status, respuesta.message, true);
            
            if (respuesta.status === 'success') {
                // Limpiar formulario
                $('#product-form')[0].reset();
                $('.field-status').html('').hide();
                $('.form-control').removeClass('is-valid is-invalid');
                $('#productId').val('');
                showGeneralStatus('success', 'Producto modificado correctamente');
                
                // Cambiar botón de vuelta a "Agregar"
                $('#submit-btn').text("Agregar Producto");
                isEditing = false;
                
                // Recargar productos después de 1 segundo
                setTimeout(function() {
                    loadProducts();
                    $('#general-status').hide();
                }, 1000);
            }
        } catch (e) {
            console.error('Error parsing edit response:', e);
            showStatus('error', 'Error al procesar respuesta del servidor', true);
        }
    }).fail(function() {
        showStatus('error', 'Error al modificar producto', true);
    });
}

// ELIMINAR PRODUCTO
$(document).on('click', '.product-delete', function(e) {
    e.stopPropagation();
    const element = $(this).closest('tr');
    const id = $(element).attr('productId');
    
    if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
        $.post('./backend/product-delete.php', {id: id}, function(response) {
            try {
                const respuesta = JSON.parse(response);
                showStatus(respuesta.status, respuesta.message, true);
                if (respuesta.status === 'success') {
                    loadProducts();
                }
            } catch (e) {
                console.error('Error parsing delete response:', e);
            }
        });
    }
});

// EDITAR PRODUCTO (al hacer clic en el nombre)
$(document).on('click', '.product-item', function(e) {
    e.preventDefault();
    const element = $(this).closest('tr');
    const id = $(element).attr('productId');
    
    $.post('./backend/product-single.php', {id: id}, function(response) {
        try {
            const product = JSON.parse(response);
            
            // Llenar formulario con datos del producto
            $('#productId').val(product.id);
            $('#name').val(product.nombre);
            $('#price').val(product.precio);
            $('#units').val(product.unidades);
            $('#model').val(product.modelo);
            $('#brand').val(product.marca);
            $('#description').val(product.descripcion);
            $('#image').val(product.imagen);
            
            // Cambiar a modo edición
            $('#submit-btn').text("Modificar Producto");
            isEditing = true;
            
            // Validar campos automáticamente
            validateAllFields();
            
        } catch (e) {
            console.error('Error parsing product data:', e);
            showStatus('error', 'Error al cargar producto', true);
        }
    }).fail(function() {
        showStatus('error', 'Error al cargar producto', true);
    });
});

function showStatus(status, message, keepVisible = false) {
    const statusClass = status === 'success' ? 'text-success' : 'text-danger';
    const template = `
        <li style="list-style: none;" class="${statusClass}"><strong>Status:</strong> ${status}</li>
        <li style="list-style: none;" class="${statusClass}"><strong>Mensaje:</strong> ${message}</li>
    `;
    
    $('#product-result').show();
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