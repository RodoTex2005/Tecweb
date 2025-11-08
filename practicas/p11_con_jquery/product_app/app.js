// VARIABLES GLOBALES PARA EDICIÓN
let editMode = false;
let currentEditId = null;
let nombreRepetido = false;
let mensajesErrores = [];

$(document).ready(function() {
    // INICIALIZACIÓN
    init();
    
    // ASIGNACIÓN DE EVENTOS CON JQUERY
    $('#search-form').on('submit', buscarProducto);
    $('#product-form').on('submit', agregarProducto);
    $('#search').on('input', buscarEnTiempoReal);
    $(document).on('click', '.product-delete', eliminarProducto);
    $(document).on('click', '.product-edit', editarProducto);
    $(document).on('click', '.product-item', seleccionarProducto);
    $('#cancel-btn').on('click', cancelarEdicion);
    
    // CONFIGURAR VALIDACIONES
    configurarValidaciones();
});

function init() {
    // CARGAR LISTA DE PRODUCTOS AL INICIAR
    listarProductos();
    $('#product-result').hide();
}

// CONFIGURAR VALIDACIONES DE CAMPOS
function configurarValidaciones() {
    // Validación del nombre en tiempo real
    $('#nombre').on('input', function() {
        const nombre = $(this).val().trim();
        
        if (nombre.length === 0) {
            ocultarEstado('nombre');
            return;
        }
        
        if (nombre.length < 2) {
            mostrarEstado('nombre', 'El nombre debe tener al menos 2 caracteres');
            return;
        }

        if (nombre.length > 100) {
            mostrarEstado('nombre', 'El nombre no puede exceder 100 caracteres');
            return;
        }

        // Validación asíncrona del nombre
        mostrarEstado('nombre', '⏳ Verificando disponibilidad del nombre...', false);
        
        validarNombreProducto(nombre).then(esValido => {
            if (esValido) {
                mostrarEstado('nombre', '✓ Nombre disponible', false);
                nombreRepetido = false;
            } else {
                mostrarEstado('nombre', '✗ Este nombre de producto ya existe');
                nombreRepetido = true;
            }
        });
    });

    // Validación al perder el foco (blur)
    $('#nombre, #marca, #modelo, #precio, #unidades, #detalles').on('blur', function() {
        const campo = $(this).attr('id');
        const valor = $(this).val();
        validarCampo(campo, valor);
    });
}

// VALIDACIÓN DE CAMPO INDIVIDUAL
function validarCampo(campo, valor) {
    switch(campo) {
        case 'nombre':
            if (!valor.trim()) {
                mostrarEstado('nombre', 'El nombre del producto es requerido');
                return false;
            }
            if (valor.length < 2) {
                mostrarEstado('nombre', 'El nombre debe tener al menos 2 caracteres');
                return false;
            }
            if (valor.length > 100) {
                mostrarEstado('nombre', 'El nombre no puede exceder 100 caracteres');
                return false;
            }
            if (nombreRepetido) {
                mostrarEstado('nombre', 'Este nombre de producto ya existe');
                return false;
            }
            ocultarEstado('nombre');
            return true;

        case 'marca':
            if (!valor.trim()) {
                mostrarEstado('marca', 'La marca es requerida');
                return false;
            }
            if (valor.length < 2) {
                mostrarEstado('marca', 'La marca debe tener al menos 2 caracteres');
                return false;
            }
            ocultarEstado('marca');
            return true;

        case 'modelo':
            if (!valor.trim()) {
                mostrarEstado('modelo', 'El modelo es requerido');
                return false;
            }
            if (valor.length < 2) {
                mostrarEstado('modelo', 'El modelo debe tener al menos 2 caracteres');
                return false;
            }
            ocultarEstado('modelo');
            return true;

        case 'precio':
            if (!valor) {
                mostrarEstado('precio', 'El precio es requerido');
                return false;
            }
            const precio = parseFloat(valor);
            if (isNaN(precio) || precio <= 0) {
                mostrarEstado('precio', 'El precio debe ser mayor a 0');
                return false;
            }
            ocultarEstado('precio');
            return true;

        case 'unidades':
            if (!valor) {
                mostrarEstado('unidades', 'Las unidades son requeridas');
                return false;
            }
            const unidades = parseInt(valor);
            if (isNaN(unidades) || unidades < 0) {
                mostrarEstado('unidades', 'Las unidades deben ser un número no negativo');
                return false;
            }
            ocultarEstado('unidades');
            return true;

        case 'detalles':
            if (valor.length > 250) {
                mostrarEstado('detalles', 'Los detalles no pueden exceder 250 caracteres');
                return false;
            }
            ocultarEstado('detalles');
            return true;

        default:
            return true;
    }
}

// VALIDACIÓN ASÍNCRONA DEL NOMBRE DEL PRODUCTO
function validarNombreProducto(nombre) {
    return new Promise((resolve) => {
        $.ajax({
            url: './backend/product-validate.php',
            type: 'GET',
            data: { 
                nombre: nombre,
                excludeId: currentEditId 
            },
            dataType: 'json',
            success: function(respuesta) {
                if (respuesta.disponible !== undefined) {
                    resolve(respuesta.disponible);
                } else {
                    resolve(true);
                }
            },
            error: function() {
                resolve(true);
            }
        });
    });
}

// FUNCIÓN PARA LISTAR PRODUCTOS
function listarProductos() {
    $.ajax({
        url: './backend/product-list.php',
        type: 'GET',
        dataType: 'json',
        success: function(productos) {
            if (productos.length > 0) {
                let template = '';
                
                productos.forEach(producto => {
                    template += `
                        <tr class="product-item" productId="${producto.id}">
                            <td>${producto.id}</td>
                            <td>${producto.nombre}</td>
                            <td>${producto.marca}</td>
                            <td>${producto.modelo}</td>
                            <td>$${parseFloat(producto.precio).toFixed(2)}</td>
                            <td>${producto.unidades}</td>
                            <td>
                                <button class="product-edit btn btn-warning btn-sm" data-id="${producto.id}">
                                    Editar
                                </button>
                                <button class="product-delete btn btn-danger btn-sm" data-id="${producto.id}">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    `;
                });
                
                $('#products').html(template);
            } else {
                $('#products').html(`
                    <tr>
                        <td colspan="7" class="text-center">No hay productos registrados</td>
                    </tr>
                `);
            }
        },
        error: function(error) {
            mostrarMensaje('error', 'Error al cargar productos');
        }
    });
}

// BÚSQUEDA
function buscarEnTiempoReal() {
    const search = $(this).val().trim();
    
    if (search.length >= 1) {
        realizarBusqueda(search);
    } else if (search.length === 0) {
        listarProductos();
        $('#product-result').addClass('d-none');
    }
}

function buscarProducto(e) {
    e.preventDefault();
    const search = $('#search').val().trim();
    
    if (search) {
        realizarBusqueda(search);
    }
}

function realizarBusqueda(search) {
    $.ajax({
        url: './backend/product-search.php',
        type: 'GET',
        data: { search: search },
        dataType: 'json',
        success: function(productos) {
            if (productos.length > 0) {
                let template = '';
                let template_bar = '';
                
                productos.forEach(producto => {
                    template += `
                        <tr class="product-item" productId="${producto.id}">
                            <td>${producto.id}</td>
                            <td>${producto.nombre}</td>
                            <td>${producto.marca}</td>
                            <td>${producto.modelo}</td>
                            <td>$${parseFloat(producto.precio).toFixed(2)}</td>
                            <td>${producto.unidades}</td>
                            <td>
                                <button class="product-edit btn btn-warning btn-sm" data-id="${producto.id}">
                                    Editar
                                </button>
                                <button class="product-delete btn btn-danger btn-sm" data-id="${producto.id}">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    `;
                    
                    template_bar += `<li>${producto.nombre} - ${producto.marca}</li>`;
                });
                
                $('#product-result').removeClass('d-none').addClass('d-block');
                $('#container').html(template_bar);
                $('#products').html(template);
            } else {
                mostrarMensaje('info', 'No se encontraron productos');
            }
        },
        error: function(error) {
            mostrarMensaje('error', 'Error en la búsqueda');
        }
    });
}

// AGREGAR O ACTUALIZAR PRODUCTO
async function agregarProducto(e) {
    e.preventDefault();
    
    // OBTENER VALORES
    const nombre = $('#nombre').val().trim();
    const marca = $('#marca').val().trim();
    const modelo = $('#modelo').val().trim();
    const precio = $('#precio').val();
    const unidades = $('#unidades').val();
    const detalles = $('#detalles').val().trim();
    const imagen = $('#imagen').val().trim() || 'img/default.png';

    // REINICIAR MENSAJES DE ERROR
    mensajesErrores = [];
    ocultarTodosEstados();

    // VALIDAR TODOS LOS CAMPOS REQUERIDOS
    const camposValidos = 
        validarCampo('nombre', nombre) &&
        validarCampo('marca', marca) &&
        validarCampo('modelo', modelo) &&
        validarCampo('precio', precio) &&
        validarCampo('unidades', unidades) &&
        validarCampo('detalles', detalles);

    // VERIFICAR SI HAY ERRORES
    if (!camposValidos || nombreRepetido) {
        mostrarMensaje('error', 'Corrija los errores en el formulario antes de enviar');
        return;
    }

    // CREAR OBJETO CON LOS DATOS
    const datosProducto = {
        nombre: nombre,
        marca: marca,
        modelo: modelo,
        precio: parseFloat(precio),
        unidades: parseInt(unidades),
        detalles: detalles,
        imagen: imagen
    };

    // AGREGAR ID SI ESTÁ EN MODO EDICIÓN
    if (editMode) {
        datosProducto.id = currentEditId;
    }

    // ENVIAR AL SERVIDOR
    $.ajax({
        url: './backend/product-add.php',
        type: 'POST',
        data: JSON.stringify(datosProducto),
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        success: function(respuesta) {
            mostrarMensaje(respuesta.status, respuesta.message);
            
            if (respuesta.status === 'success') {
                limpiarFormulario();
                listarProductos();
                cancelarEdicion();
                // REQUISITO 3: Cambiar texto del botón al enviar
                $('button.btn-primary').text("Agregar Producto");
            }
        },
        error: function(xhr, status, error) {
            mostrarMensaje('error', 'Error al comunicarse con el servidor');
        }
    });
}

// ELIMINAR PRODUCTO
function eliminarProducto() {
    const id = $(this).data('id');
    const nombre = $(this).closest('tr').find('td:eq(1)').text();
    
    if (confirm(`¿Estás seguro de que deseas eliminar el producto "${nombre}"?`)) {
        $.ajax({
            url: './backend/product-delete.php',
            type: 'GET',
            data: { id: id },
            dataType: 'json',
            success: function(respuesta) {
                mostrarMensaje(respuesta.status, respuesta.message);
                
                if (respuesta.status === 'success') {
                    listarProductos();
                    if (currentEditId === id) {
                        cancelarEdicion();
                    }
                }
            },
            error: function(error) {
                mostrarMensaje('error', 'Error al eliminar producto');
            }
        });
    }
}

// EDITAR PRODUCTO
function editarProducto() {
    const productId = $(this).data('id');
    
    $.ajax({
        url: './backend/product-single.php',
        type: 'POST',
        data: { id: productId },
        dataType: 'json',
        success: function(producto) {
            entrarModoEdicion(producto);
        },
        error: function(error) {
            mostrarMensaje('error', 'Error al cargar producto');
        }
    });
}

// REQUISITO 2: SELECCIONAR PRODUCTO (cambiar texto del botón)
function seleccionarProducto() {
    $('button.btn-primary').text("Modificar Producto");
}

// ACTIVAR MODO EDICIÓN
function entrarModoEdicion(producto) {
    editMode = true;
    currentEditId = producto.id;
    nombreRepetido = false;
    
    // LLENAR FORMULARIO
    $('#nombre').val(producto.nombre);
    $('#marca').val(producto.marca);
    $('#modelo').val(producto.modelo);
    $('#precio').val(producto.precio);
    $('#unidades').val(producto.unidades);
    $('#detalles').val(producto.detalles);
    $('#imagen').val(producto.imagen);
    $('#productId').val(producto.id);
    
    // CAMBIAR INTERFAZ
    $('#submit-btn').text('Actualizar Producto').removeClass('btn-primary').addClass('btn-success');
    $('#cancel-btn').show();
    
    mostrarMensaje('info', `Editando producto: ${producto.nombre}`);
}

// CANCELAR EDICIÓN
function cancelarEdicion() {
    editMode = false;
    currentEditId = null;
    nombreRepetido = false;
    
    limpiarFormulario();
    
    $('#submit-btn').text('Agregar Producto').removeClass('btn-success').addClass('btn-primary');
    $('#cancel-btn').hide();
}

// LIMPIAR FORMULARIO
function limpiarFormulario() {
    $('#nombre').val('');
    $('#marca').val('');
    $('#modelo').val('');
    $('#precio').val('');
    $('#unidades').val('');
    $('#detalles').val('');
    $('#imagen').val('img/default.png');
    $('#productId').val('');
    
    ocultarTodosEstados();
}

// FUNCIONES AUXILIARES
function mostrarEstado(campo, mensaje, esError = true) {
    const elementoEstado = $(`#${campo}-status`);
    elementoEstado.text(mensaje);
    elementoEstado.removeClass('text-success text-warning');
    elementoEstado.addClass(esError ? 'text-warning' : 'text-success');
    elementoEstado.show();
    
    const elementoCampo = $(`#${campo}`);
    elementoCampo.removeClass('is-valid is-invalid');
    if (mensaje) {
        elementoCampo.addClass(esError ? 'is-invalid' : 'is-valid');
    }
}

function ocultarEstado(campo) {
    $(`#${campo}-status`).hide();
    $(`#${campo}`).removeClass('is-invalid is-valid');
}

function ocultarTodosEstados() {
    $('#nombre-status, #marca-status, #modelo-status, #precio-status, #unidades-status, #detalles-status').hide();
    $('#nombre, #marca, #modelo, #precio, #unidades, #detalles').removeClass('is-valid is-invalid');
}

function mostrarMensaje(status, mensaje) {
    let clase = 'alert-info';
    if (status === 'error') clase = 'alert-danger';
    if (status === 'success') clase = 'alert-success';
    
    const template = `
        <li style="list-style: none;" class="${clase}">status: ${status}</li>
        <li style="list-style: none;" class="${clase}">message: ${mensaje}</li>
    `;
    
    $('#product-result').removeClass('d-none').addClass('d-block');
    $('#container').html(template);
    
    if (status === 'success') {
        setTimeout(() => {
            $('#product-result').addClass('d-none');
        }, 5000);
    }
}