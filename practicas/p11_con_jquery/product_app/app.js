// VARIABLES GLOBALES PARA EDICIÓN
let editMode = false;
let currentEditId = null;

// FUNCIÓN PRINCIPAL AL CARGAR LA PÁGINA
$(document).ready(function() {
    init();
    
    // ASIGNACIÓN DE EVENTOS CON JQUERY
    $('#search-form').on('submit', buscarProducto);
    $('#product-form').on('submit', agregarProducto);
    $('#search').on('input', buscarEnTiempoReal);
    $(document).on('click', '.product-delete', eliminarProducto);
    $(document).on('click', '.product-edit', editarProducto);
    $('#cancel-btn').on('click', cancelarEdicion);
    
    // CONFIGURAR VALIDACIONES
    configurarValidaciones();
});

function init() {
    // CARGAR LISTA DE PRODUCTOS AL INICIAR
    listarProductos();
}

// CONFIGURAR VALIDACIONES DE CAMPOS
function configurarValidaciones() {
    // Validación al perder el foco
    $('#nombre, #marca, #modelo, #precio, #unidades').on('blur', function() {
        const campo = $(this).attr('id');
        const valor = $(this).val();
        validarCampo(campo, valor);
    });

    // Validación asíncrona del nombre en tiempo real
    $('#nombre').on('input', async function() {
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

        // Mostrar mensaje de verificación
        mostrarEstado('nombre', '⏳ Verificando disponibilidad del nombre...', false);
        
        try {
            // TEMPORAL: Usar simulación para pruebas
            const esValido = await simularValidacionNombre(nombre);
            // const esValido = await validarNombreProducto(nombre); // Comentar esta línea
            
            if (esValido) {
                mostrarEstado('nombre', '✓ Nombre disponible', false);
            } else {
                mostrarEstado('nombre', '✗ Este nombre de producto ya existe');
            }
        } catch (error) {
            mostrarEstado('nombre', '⚠️ Error al verificar el nombre');
        }
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

        default:
            return true;
    }
}

// VALIDACIÓN ASÍNCRONA DEL NOMBRE DEL PRODUCTO
function validarNombreProducto(nombre) {
    return new Promise((resolve) => {
        // Simulamos una consulta a la BD primero para pruebas
        setTimeout(() => {
            // Consulta real al backend para verificar nombre
            $.ajax({
                url: './backend/product-validate.php',
                type: 'GET',
                data: { 
                    nombre: nombre,
                    excludeId: currentEditId 
                },
                dataType: 'json',
                success: function(respuesta) {
                    console.log('Respuesta validación:', respuesta);
                    if (respuesta.disponible !== undefined) {
                        resolve(respuesta.disponible);
                    } else {
                        // Si hay error, asumimos que está disponible
                        resolve(true);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en validación:', error);
                    console.log('Response:', xhr.responseText);
                    // En caso de error, asumimos que está disponible
                    resolve(true);
                }
            });
        }, 300); // Pequeño delay para pruebas
    });
}

// MOSTRAR MENSAJE DE ESTADO
function mostrarEstado(campo, mensaje, esError = true) {
    const elementoEstado = $(`#${campo}-status`);
    elementoEstado.text(mensaje);
    elementoEstado.removeClass('text-success text-warning');
    elementoEstado.addClass(esError ? 'text-warning' : 'text-success');
    elementoEstado.show();
    
    // Actualizar clase visual del campo
    const elementoCampo = $(`#${campo}`);
    elementoCampo.removeClass('is-valid is-invalid');
    if (mensaje) {
        elementoCampo.addClass(esError ? 'is-invalid' : 'is-valid');
    }
}

// OCULTAR MENSAJE DE ESTADO
function ocultarEstado(campo) {
    $(`#${campo}-status`).hide();
    $(`#${campo}`).removeClass('is-invalid');
}

// OCULTAR TODOS LOS MENSAJES DE ESTADO
function ocultarTodosEstados() {
    $('#nombre-status, #marca-status, #modelo-status, #precio-status, #unidades-status').hide();
    $('#nombre, #marca, #modelo, #precio, #unidades').removeClass('is-valid is-invalid');
}

// FUNCIÓN PARA LISTAR TODOS LOS PRODUCTOS
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
        error: function(xhr, status, error) {
            console.error('Error al cargar productos:', error);
            mostrarMensaje('error', 'Error al cargar productos');
        }
    });
}

// BÚSQUEDA EN TIEMPO REAL (AL TECLEAR)
function buscarEnTiempoReal() {
    const search = $(this).val().trim();
    
    if (search.length >= 1) {
        realizarBusqueda(search);
    } else if (search.length === 0) {
        listarProductos();
        $('#product-result').addClass('d-none');
    }
}

// BÚSQUEDA CON BOTÓN "BUSCAR"
function buscarProducto(e) {
    e.preventDefault();
    const search = $('#search').val().trim();
    
    if (search) {
        realizarBusqueda(search);
    }
}

// FUNCIÓN COMÚN PARA BÚSQUEDAS
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
                $('#products').html(`
                    <tr>
                        <td colspan="7" class="text-center">No se encontraron productos</td>
                    </tr>
                `);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error en búsqueda:', error);
            mostrarMensaje('error', 'Error en la búsqueda');
        }
    });
}

// AGREGAR O ACTUALIZAR PRODUCTO
async function agregarProducto(e) {
    e.preventDefault();
    
    // OBTENER VALORES DE LOS CAMPOS DIRECTAMENTE
    const nombre = $('#nombre').val() ? $('#nombre').val().trim() : '';
    const marca = $('#marca').val() ? $('#marca').val().trim() : '';
    const modelo = $('#modelo').val() ? $('#modelo').val().trim() : '';
    const precio = $('#precio').val() || '';
    const unidades = $('#unidades').val() || '';
    const detalles = $('#detalles').val() ? $('#detalles').val().trim() : '';
    const imagen = $('#imagen').val() ? $('#imagen').val().trim() : 'img/default.png';

    console.log('Datos del formulario:', { nombre, marca, modelo, precio, unidades, detalles, imagen }); // Debug

    // VALIDAR CAMPOS REQUERIDOS
    const camposRequeridos = [
        { campo: 'nombre', valor: nombre, nombre: 'Nombre del Producto' },
        { campo: 'marca', valor: marca, nombre: 'Marca' },
        { campo: 'modelo', valor: modelo, nombre: 'Modelo' },
        { campo: 'precio', valor: precio, nombre: 'Precio' },
        { campo: 'unidades', valor: unidades, nombre: 'Unidades' }
    ];

    // VERIFICAR CAMPOS VACÍOS
    const camposVacios = camposRequeridos.filter(campo => !campo.valor);
    
    if (camposVacios.length > 0) {
        const nombresCampos = camposVacios.map(campo => campo.nombre).join(', ');
        mostrarMensaje('error', `Complete los campos requeridos: ${nombresCampos}`);
        
        // Resaltar campos vacíos
        camposVacios.forEach(campo => {
            $(`#${campo.campo}`).addClass('is-invalid');
        });
        return;
    }

    // VALIDAR CADA CAMPO INDIVIDUALMENTE
    const nombreValido = validarCampo('nombre', nombre);
    const marcaValida = validarCampo('marca', marca);
    const modeloValido = validarCampo('modelo', modelo);
    const precioValido = validarCampo('precio', precio);
    const unidadesValidas = validarCampo('unidades', unidades);

    if (!nombreValido || !marcaValida || !modeloValido || !precioValido || !unidadesValidas) {
        mostrarMensaje('error', 'Corrija los errores en el formulario');
        return;
    }

    // VALIDAR NOMBRE ÚNICO
    const nombreUnico = await validarNombreProducto(nombre);
    if (!nombreUnico) {
        mostrarEstado('nombre', '✗ Este nombre de producto ya existe');
        mostrarMensaje('error', 'El nombre del producto ya existe. Use otro nombre.');
        return;
    }

    // SI TODAS LAS VALIDACIONES PASAN, ENVIAR EL FORMULARIO
    console.log('Enviando formulario...');
    
    // Crear objeto con los datos
    const datosProducto = {
        nombre: nombre,
        marca: marca,
        modelo: modelo,
        precio: parseFloat(precio),
        unidades: parseInt(unidades),
        detalles: detalles,
        imagen: imagen
    };

    if (editMode) {
        datosProducto.id = currentEditId;
    }

    console.log('Datos a enviar:', datosProducto);

    // ENVIAR AL SERVIDOR COMO JSON (más simple)
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
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al guardar producto:', error);
            console.error('Response:', xhr.responseText);
            mostrarMensaje('error', 'Error al comunicarse con el servidor: ' + error);
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
                    // Si eliminamos el producto que estábamos editando
                    if (currentEditId === id) {
                        cancelarEdicion();
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al eliminar producto:', error);
                mostrarMensaje('error', 'Error al eliminar producto');
            }
        });
    }
}

// EDITAR PRODUCTO
function editarProducto() {
    const productId = $(this).data('id');
    
    // BUSCAR EL PRODUCTO EN LA TABLA
    const $fila = $(this).closest('tr');
    const producto = {
        id: productId,
        nombre: $fila.find('td:eq(1)').text(),
        marca: $fila.find('td:eq(2)').text(),
        modelo: $fila.find('td:eq(3)').text(),
        precio: parseFloat($fila.find('td:eq(4)').text().replace('$', '')),
        unidades: parseInt($fila.find('td:eq(5)').text()),
        detalles: '', // Estos datos vendrían del backend en una aplicación real
        imagen: 'img/default.png'
    };
    
    // ENTRAR EN MODO EDICIÓN
    entrarModoEdicion(producto);
}

// FUNCIÓN PARA ACTIVAR MODO EDICIÓN
function entrarModoEdicion(producto) {
    editMode = true;
    currentEditId = producto.id;
    
    // LLENAR FORMULARIO CON DATOS DEL PRODUCTO
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
    
    // SCROLL AL FORMULARIO
    $('html, body').animate({
        scrollTop: $('#product-form').offset().top
    }, 500);
    
    mostrarMensaje('info', `Editando producto: ${producto.nombre}`);
    
    // Configurar evento para cambiar texto del botón al hacer clic en productos
    $(document).on('click', '.product-item', function(e) {
        if (!$(e.target).hasClass('product-delete') && !$(e.target).hasClass('product-edit')) {
            $('#submit-btn').text("Modificar Producto");
        }
    });
}

// CANCELAR EDICIÓN
function cancelarEdicion() {
    editMode = false;
    currentEditId = null;
    
    // RESTABLECER FORMULARIO
    limpiarFormulario();
    
    // RESTABLECER INTERFAZ
    $('#submit-btn').text('Agregar Producto').removeClass('btn-success').addClass('btn-primary');
    $('#cancel-btn').hide();
    
    mostrarMensaje('info', 'Edición cancelada');
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
    $('#submit-btn').text("Agregar Producto");
}

// FUNCIÓN AUXILIAR PARA MOSTRAR MENSAJES
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
    
    // AUTO-OCULTAR MENSAJES DE ÉXITO DESPUÉS DE 5 SEGUNDOS
    if (status === 'success') {
        setTimeout(() => {
            $('#product-result').addClass('d-none');
        }, 5000);
    }
}
// FUNCIÓN TEMPORAL PARA PRUEBAS - SIMULA LA VALIDACIÓN
function simularValidacionNombre(nombre) {
    return new Promise((resolve) => {
        // Simulamos delay de red
        setTimeout(() => {
            // Lista de nombres que ya existen (simula BD)
            const nombresExistentes = [
                'Laptop Gaming Pro', 
                'Calular Inteligente', 
                'Calular Inteligente 2',
                'Set de cubiertos'
            ];
            
            const existe = nombresExistentes.some(nombreExistente => 
                nombreExistente.toLowerCase() === nombre.toLowerCase()
            );
            
            resolve(!existe); // true si está disponible, false si ya existe
        }, 800);
    });
}