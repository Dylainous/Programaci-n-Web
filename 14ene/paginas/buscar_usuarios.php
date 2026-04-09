<?php
include_once "../server/conexion.php";

$registros_por_pagina = 5;

$pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
if ($pagina < 1) $pagina = 1;

$inicio = ($pagina - 1) * $registros_por_pagina;

$where = "";
$parametros = "";

if (isset($_GET['buscar'])) {
    if ($_GET['buscar_por'] == "cedula" && !empty($_GET['cedula'])) {
        $cedula = $_GET['cedula'];
        $where = "WHERE Cedula_user LIKE '%$cedula%'";
        $parametros = "&buscar=1&buscar_por=cedula&cedula=$cedula";
    }

    if ($_GET['buscar_por'] == "nombre" && !empty($_GET['nombre'])) {
        $nombre = $_GET['nombre'];
        $where = "WHERE Nombre_user LIKE '%$nombre%'";
        $parametros = "&buscar=1&buscar_por=nombre&nombre=$nombre";
    }
}

$sql_total = "SELECT COUNT(*) AS total FROM usuarios $where";
$result_total = $conn->query($sql_total);
$fila_total = $result_total->fetch_assoc();
$total_registros = $fila_total['total'];

$total_paginas = ceil($total_registros / $registros_por_pagina);

$sql = "SELECT * FROM usuarios $where LIMIT $inicio, $registros_por_pagina";
$result = $conn->query($sql);
?>


<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Listado de Alumnos</title><link rel="stylesheet" href="../estilos/estilos.css">
</head>

<body>

<h2 align="center">LISTADO DE LOS USUARIOS</h2>

	
<form method="get" align="center">
<script>
function toggleBusqueda(opcion) {
    if (opcion === 'cedula') {
        document.getElementById('chkNombre').checked = false;
        document.getElementById('nombre').disabled = true;
        document.getElementById('cedula').disabled = false;
    } else {
        document.getElementById('chkCedula').checked = false;
        document.getElementById('cedula').disabled = true;
        document.getElementById('nombre').disabled = false;
    }
}
</script>

<label>
	<input type="checkbox" id="chkCedula" name="buscar_por" value="cedula" onclick="toggleBusqueda('cedula')"> Cédula
</label>
<input type="text" name="cedula" id="cedula" disabled>

&nbsp;&nbsp;

<label>
	<input type="checkbox" id="chkNombre" name="buscar_por" value="nombre" onclick="toggleBusqueda('nombre')"> Nombre
</label>
<input type="text" name="nombre" id="nombre" disabled>

&nbsp;&nbsp;

<button type="submit" name="buscar" value="1">Buscar</button>
<a href="buscar_usuarios.php">Limpiar</a>
</form>

<br>
	
	
	
	
<table border="1">
  <tr>
    <th>ID_USER</th>
    <th>Nombre</th>
    <th>Usuario</th>
    <th>Cédula</th>
    <th>ID_ROL</th>
	<th>Estado</th>
    <th colspan="3">Acción</th>
  </tr>

<?php
if ($result->num_rows > 0) {

  while ($fila = $result->fetch_assoc()) {

    echo "<tr>
            <td>{$fila['id_usuario']}</td>
            <td>{$fila['Nombre_user']}</td>
            <td>{$fila['Usuario_user']}</td>
            <td>{$fila['Cedula_user']}</td>
            <td>{$fila['id_rol']}</td>";
            if ($fila['Estado_user'] == 'Activo') {
				echo "<td class='estado-activo'>Activo</td>";
			} else {
				echo "<td class='estado-inactivo'>Inactivo</td>";
			}

	  //<td><a href="edicion_alumno.php?id='.$row["apellidos"].'">
	  
    echo "<td>
            <form action='edicion_usuarios.php' method='get'>
              <input type='hidden' name='id_usuario' value='{$fila['id_usuario']}'>
              <button type='submit'>EDITAR</button>
            </form>
          </td>";

    if ($fila['Estado_user'] == 'Activo') {
      echo "<td>
              <form action='../server/cambiar_estado_usuario.php' method='post'
                    onsubmit=\"return confirm('¿Desea eliminar este alumno?')\">
                <input type='hidden' name='id_usuario' value='{$fila['id_usuario']}'>
                <input type='hidden' name='Estado_user' value='Inactivo'>
                <button type='submit'>ELIMINAR</button>
              </form>
            </td>
            <td></td>";
    } else {
      echo "<td></td>
            <td>
              <form action='../server/cambiar_estado_usuario.php' method='post'>
                <input type='hidden' name='id_usuario' value='{$fila['id_usuario']}'>
                <input type='hidden' name='Estado_user' value='Activo'>
                <button type='submit'>ACTIVAR</button>
              </form>
            </td>";
    }

    echo "</tr>";
  }

} else {
  echo "<tr><td colspan='10'>No hay registros</td></tr>";
}

$conn->close();
?>
</table>
	
<div class="paginacion">

  
  <?php if ($pagina > 1) { ?>
    <a class="btn-pag" href="?pagina=<?= $pagina - 1 ?><?= $parametros ?>">Anterior</a>
  <?php } else { ?>
    <span class="btn-pag disabled">Anterior</span>
  <?php } ?>

  
  <?php
  for ($i = 1; $i <= $total_paginas; $i++) {
    if ($i == $pagina) {
      echo "<span class='numero actual'>$i</span>";
    } else {
      echo "<a class='numero' href='?pagina=$i'>$i</a>";
    }
  }
  ?>

  
  <?php if ($pagina < $total_paginas) { ?>
    <a class="btn-pag" href="?pagina=<?= $pagina + 1 ?><?= $parametros ?>">Siguiente</a>
  <?php } else { ?>
    <span class="btn-pag disabled">Siguiente</span>
  <?php } ?>

</div>
	
<br>
<p align="center">
    <a href="index.html">Volver al principio</a>
</p>
</body>
</html>