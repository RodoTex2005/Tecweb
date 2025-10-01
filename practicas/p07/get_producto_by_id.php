<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<?php
if(isset($_GET['id']))
    $id = $_GET['id'];

if (!empty($id))
{
    /** SE CREA EL OBJETO DE CONEXION */
    @$link = new mysqli('localhost', 'root', 'Rudytexcuc@no', 'marketzone');	

    /** comprobar la conexión */
    if ($link->connect_errno) 
    {
        die('Falló la conexión: '.$link->connect_error.'<br/>');
    }

    /** Ejecutar consulta */
    if ( $result = $link->query("SELECT * FROM productos WHERE id = '{$id}'") ) 
    {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $result->free();
    }

    $link->close();
}
?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Producto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" 
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" 
          crossorigin="anonymous">
</head>
<body>
    <h3>PRODUCTO</h3>
    <br/>
    
    <?php if( isset($row) ) : ?>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Unidades</th>
                    <th scope="col">Imagen</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th scope="row"><?= $row['id'] ?></th>
                    <td><?= htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= nl2br(htmlspecialchars($row['descripcion'], ENT_QUOTES, 'UTF-8')) ?></td>
                    <td><?= number_format($row['precio'], 2) ?></td>
                    <td><?= (int)$row['unidades'] ?></td>
                    <td>
						<img src="img/<?= htmlspecialchars($row['imagen'], ENT_QUOTES, 'UTF-8') ?>" 
							style="max-width:120px;" 
							alt="<?= htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8') ?>" />
					</td>

                </tr>
            </tbody>
        </table>
    <?php elseif(!empty($id)) : ?>
        <script>
            alert('El ID del producto no existe');
        </script>
    <?php endif; ?>
</body>
</html>
