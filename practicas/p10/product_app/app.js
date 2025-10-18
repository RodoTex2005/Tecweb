// JSON BASE A MOSTRAR EN FORMULARIO
var baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
};

// SE CREA EL OBJETO DE CONEXIÓN COMPATIBLE CON EL NAVEGADOR
function getXMLHttpRequest() {
    var objetoAjax;
    try{
        objetoAjax = new XMLHttpRequest();
    }catch(err1){
        try{
            // IE7 y IE8
            objetoAjax = new ActiveXObject("Msxml2.XMLHTTP");
        }catch(err2){
            try{
                // IE5 y IE6
                objetoAjax = new ActiveXObject("Microsoft.XMLHTTP");
            }catch(err3){
                objetoAjax = false;
            }
        }
    }
    return objetoAjax;
}

function init() {
    /**
     * Convierte el JSON a string para poder mostrarlo
     */
    var JsonString = JSON.stringify(baseJSON,null,2);
    document.getElementById("description").value = JsonString;
}

// FUNCIÓN CALLBACK DE BOTÓN "Buscar"
function buscarID(e) {
    e.preventDefault();
    var id = document.getElementById('search').value;
    var client = getXMLHttpRequest();
    client.open('POST', './backend/read.php', true);
    client.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    client.onreadystatechange = function () {
        if (client.readyState == 4 && client.status == 200) {
            console.log('[CLIENTE]\n'+client.responseText);
            
            let productos = JSON.parse(client.responseText);
            
            if(Object.keys(productos).length > 0) {
                let descripcion = '';
                descripcion += '<li>precio: '+productos.precio+'</li>';
                descripcion += '<li>unidades: '+productos.unidades+'</li>';
                descripcion += '<li>modelo: '+productos.modelo+'</li>';
                descripcion += '<li>marca: '+productos.marca+'</li>';
                descripcion += '<li>detalles: '+productos.detalles+'</li>';
                
                let template = '';
                template += `
                    <tr>
                        <td>${productos.id}</td>
                        <td>${productos.nombre}</td>
                        <td><ul>${descripcion}</ul></td>
                    </tr>
                `;

                document.getElementById("productos").innerHTML = template;
            }
        }
    };
    client.send("id="+id);
}

// FUNCIÓN CALLBACK DE BOTÓN "Buscar" PARA BÚSQUEDA VERSÁTIL
function buscarProducto(e) {
    e.preventDefault();
    var searchText = document.getElementById('search').value;

    // CORRECCIÓN: quita el "new" antes de getXMLHttpRequest
    var client = getXMLHttpRequest(); // ✅ CORREGIDO
    client.open('POST', './backend/read.php', true);
    client.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    client.onreadystatechange = function () {
        if (client.readyState == 4 && client.status == 200) {
            console.log('[CLIENTE - BÚSQUEDA VERSÁTIL]\n'+client.responseText);
            
            let productos = JSON.parse(client.responseText);
            
            let template = '';
            
            if(productos.length > 0) {
                productos.forEach(function(producto) {
                    let descripcion = '';
                    descripcion += '<li>precio: '+producto.precio+'</li>';
                    descripcion += '<li>unidades: '+producto.unidades+'</li>';
                    descripcion += '<li>modelo: '+producto.modelo+'</li>';
                    descripcion += '<li>marca: '+producto.marca+'</li>';
                    descripcion += '<li>detalles: '+producto.detalles+'</li>';
                    
                    template += `
                        <tr>
                            <td>${producto.id}</td>
                            <td>${producto.nombre}</td>
                            <td><ul>${descripcion}</ul></td>
                        </tr>
                    `;
                });
            } else {
                template = '<tr><td colspan="3">No se encontraron productos</td></tr>';
            }

            document.getElementById("productos").innerHTML = template;
        }
    };
    client.send("search="+encodeURIComponent(searchText));
}

// FUNCIÓN CALLBACK DE BOTÓN "Agregar Producto"
function agregarProducto(e) {
    e.preventDefault();

    // VALIDACIONES
    var nombre = document.getElementById('name').value;
    var productoJsonString = document.getElementById('description').value;
    
    // Validar nombre no vacío
    if(!nombre || nombre.trim() === '') {
        alert('El nombre del producto es requerido');
        return;
    }
    
    // Validar JSON válido
    try {
        var finalJSON = JSON.parse(productoJsonString);
    } catch (error) {
        alert('JSON inválido: ' + error.message);
        return;
    }
    
    // Validar campos numéricos
    if(isNaN(finalJSON.precio) || finalJSON.precio < 0) {
        alert('El precio debe ser un número válido mayor o igual a 0');
        return;
    }
    
    if(isNaN(finalJSON.unidades) || finalJSON.unidades < 0 || !Number.isInteger(finalJSON.unidades)) {
        alert('Las unidades deben ser un número entero válido mayor o igual a 0');
        return;
    }
    
    // Validar campos de texto requeridos
    if(!finalJSON.modelo || finalJSON.modelo.trim() === '') {
        alert('El modelo es requerido');
        return;
    }
    
    if(!finalJSON.marca || finalJSON.marca.trim() === '') {
        alert('La marca es requerida');
        return;
    }

    // SE AGREGA AL JSON EL NOMBRE DEL PRODUCTO
    finalJSON['nombre'] = nombre;
    productoJsonString = JSON.stringify(finalJSON,null,2);

    var client = getXMLHttpRequest();
    client.open('POST', './backend/create.php', true);
    client.setRequestHeader('Content-Type', "application/json;charset=UTF-8");
    client.onreadystatechange = function () {
        if (client.readyState == 4 && client.status == 200) {
            console.log(client.responseText);
            alert(client.responseText);
        }
    };
    client.send(productoJsonString);
}
