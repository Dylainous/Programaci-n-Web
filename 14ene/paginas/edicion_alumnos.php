<?php
include_once "../server/conexion.php";

$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
$sql = "SELECT * FROM alumno WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$alumno = $result->fetch_assoc();

if (!$alumno) {
	die("Alumno no encontrado");
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">

<title>Formulario Base de Datos</title>
<link rel="stylesheet" href="../estilos/estilos.css">
</head>

<body>
<nav class="menu"><a href="../paginas/listar_alumnos.php">REGRESAR</a></nav>
<div class="contenedor">
  <h1>EDICIÓN DE DATOS ALUMNO</h1>
  <form id="form1" name="form1" method="post" action="../server/actualizar_alumno.php">
	  <input type="hidden" name="id" value="<?php echo $alumno['id']; ?>">
    <fieldset>
      <legend>Datos Personales</legend>
      <p>
        <label for="textfield">Apellidos:</label>
        <input type="text" name="apellidos" value="<?= $alumno['apellidos'] ?>" id="apellidos" autofocus pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]{2,}" title="Solo letras" onchange="validar_letras(this)">
      </p>
      <p>
        <label for="textfield2">Nombres:</label>
        <input type="text" name="nombres" id="nombres" value="<?= $alumno['nombres'] ?>" 
			   pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]{2,}" title="Solo letras" onChange="validar_letras(this)">
      </p>
      <p>
        <label for="textfield3">Cedula:</label>
        <input type="text" name="cedula" id="cedula" value="<?= $alumno['cedula'] ?>" maxlength="10" 
			   pattern="[0-9]{10}" title="Debe contener 10 dígitos" onChange="validar_cedula()">
      </p>
      <p>
        <label for="textfield4">Email:</label>
        <input type="email" name="email" id="email" value="<?= $alumno['email'] ?>" onChange="validar_email()">
      </p>
      
    </fieldset>
	<p>
	  <input type="submit" name="submit" id="submit" value="Guardar cambios">
	</p>
	  
	<a href="listar_alumnos.php"><input type="button" value="Cancelar"></a>
	 
  </form>
</div>
<script src="../script/validacion.js" type="text/javascript"></script>
</body>
</html>
