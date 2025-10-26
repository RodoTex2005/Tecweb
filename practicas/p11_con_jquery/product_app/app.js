// JSON BASE A MOSTRAR EN FORMULARIO
const baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
};

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
});

function init() {
    // CONVERTIR JSON A STRING Y MOSTRARLO
    const jsonString = JSON.stringify(baseJSON, null, 2);
    $('#description').val(jsonString);
    
    // CARGAR LISTA DE PRODUCTOS AL INICIAR
    listarProductos();
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
                    const descripcion = `
                        <li>precio: ${producto.precio}</li>
                        <li>unidades: ${producto.unidades}</li>
                        <li>modelo: ${producto.modelo}</li>
                        <li>marca: ${producto.marca}</li>
                        <li>detalles: ${producto.detalles}</li>
                    `;
                    
                    template += `
                        <tr productId="${producto.id}">
                            <td>${producto.id}</td>
                            <td>${producto.nombre}</td>
                            <td><ul>${descripcion}</ul></td>
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
                    const descripcion = `
                        <li>precio: ${producto.precio}</li>
                        <li>unidades: ${producto.unidades}</li>
                        <li>modelo: ${producto.modelo}</li>
                        <li>marca: ${producto.marca}</li>
                        <li>detalles: ${producto.detalles}</li>
                    `;
                    
                    template += `
                        <tr productId="${producto.id}">
                            <td>${producto.id}</td>
                            <td>${producto.nombre}</td>
                            <td><ul>${descripcion}</ul></td>
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
                    
                    template_bar += `<li>${producto.nombre}</li>`;
                });
                
                $('#product-result').removeClass('d-none').addClass('d-block');
                $('#container').html(template_bar);
                $('#products').html(template);
            } else {
                mostrarMensaje('info', 'No se encontraron productos');
                $('#products').html('');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error en búsqueda:', error);
            mostrarMensaje('error', 'Error en la búsqueda');
        }
    });
}

// AGREGAR O ACTUALIZAR PRODUCTO
function agregarProducto(e) {
    e.preventDefault();
    
    const productoJsonString = $('#description').val();
    const nombre = $('#name').val().trim();
    
    if (!nombre) {
        mostrarMensaje('error', 'El nombre del producto es requerido');
        return;
    }
    
    try {
        const finalJSON = JSON.parse(productoJsonString);
        finalJSON.nombre = nombre;
        
        // SI ESTAMOS EN MODO EDICIÓN, AGREGAR EL ID
        if (editMode) {
            finalJSON.id = currentEditId;
        }
        
        const productoJsonEnviar = JSON.stringify(finalJSON, null, 2);
        
        $.ajax({
            url: './backend/product-add.php',
            type: 'POST',
            data: productoJsonEnviar,
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
           success: function(respuesta) {
    mostrarMensaje(respuesta.status, respuesta.message);
    
    if (respuesta.status === 'success') {
        // LIMPIAR FORMULARIO
        $('#name').val('');
        $('#description').val(JSON.stringify(baseJSON, null, 2));
        
        // SI ESTÁBAMOS EDITANDO, SALIR DEL MODO EDICIÓN SIN MOSTRAR MENSAJE DE CANCELACIÓN
        if (editMode) {
            // Salir del modo edición silenciosamente
            editMode = false;
            currentEditId = null;
            $('#submit-btn').text('Agregar Producto').removeClass('btn-success').addClass('btn-primary');
            $('#cancel-btn').hide();
        }
    }
    
    // ACTUALIZAR LISTA DE PRODUCTOS
    listarProductos();
},
            error: function(xhr, status, error) {
                console.error('Error al guardar producto:', error);
                mostrarMensaje('error', 'Error al comunicarse con el servidor');
            }
        });
        
    } catch (error) {
        mostrarMensaje('error', 'JSON inválido: ' + error.message);
    }
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
    
    // BUSCAR EL PRODUCTO EN LA TABLA ACTUAL Y CARGAR SUS DATOS
    const $fila = $(this).closest('tr');
    const nombre = $fila.find('td:eq(1)').text();
    const descripcionItems = $fila.find('td:eq(2) li');
    
    // EXTRAER DATOS DE LA DESCRIPCIÓN
    const producto = {
        id: productId,
        nombre: nombre,
        precio: parseFloat(descripcionItems.eq(0).text().replace('precio: ', '')),
        unidades: parseInt(descripcionItems.eq(1).text().replace('unidades: ', '')),
        modelo: descripcionItems.eq(2).text().replace('modelo: ', ''),
        marca: descripcionItems.eq(3).text().replace('marca: ', ''),
        detalles: descripcionItems.eq(4).text().replace('detalles: ', ''),
        imagen: 'img/default.png' // Valor por defecto
    };
    
    // ENTRAR EN MODO EDICIÓN
    entrarModoEdicion(producto);
}

// FUNCIÓN PARA ACTIVAR MODO EDICIÓN
function entrarModoEdicion(producto) {
    editMode = true;
    currentEditId = producto.id;
    
    // LLENAR FORMULARIO CON DATOS DEL PRODUCTO
    $('#name').val(producto.nombre);
    $('#productId').val(producto.id);
    
    // CREAR JSON CON DATOS ACTUALES
    const productJSON = {
        precio: producto.precio,
        unidades: producto.unidades,
        modelo: producto.modelo,
        marca: producto.marca,
        detalles: producto.detalles,
        imagen: producto.imagen
    };
    
    $('#description').val(JSON.stringify(productJSON, null, 2));
    
    // CAMBIAR INTERFAZ
    $('#submit-btn').text('Actualizar Producto').removeClass('btn-primary').addClass('btn-success');
    $('#cancel-btn').show();
    
    // SCROLL AL FORMULARIO
    $('html, body').animate({
        scrollTop: $('#product-form').offset().top
    }, 500);
    
    mostrarMensaje('info', `Editando producto: ${producto.nombre}`);
}

// CANCELAR EDICIÓN
function cancelarEdicion() {
    editMode = false;
    currentEditId = null;
    
    // RESTABLECER FORMULARIO
    $('#name').val('');
    $('#productId').val('');
    $('#description').val(JSON.stringify(baseJSON, null, 2));
    
    // RESTABLECER INTERFAZ
    $('#submit-btn').text('Agregar Producto').removeClass('btn-success').addClass('btn-primary');
    $('#cancel-btn').hide();
    
    mostrarMensaje('info', 'Edición cancelada');
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