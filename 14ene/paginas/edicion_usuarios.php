<?php
include_once "../server/conexion.php";

$id = isset($_GET["id_usuario"]) ? intval($_GET["id_usuario"]) : 0;
$sql = "SELECT * FROM usuarios WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$usuarios = $result->fetch_assoc();

if (!$usuarios) {
	die("Usuario no encontrado");
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
<nav class="menu"><a href="../paginas/listar_usuarios.php">REGRESAR</a></nav>
<div class="contenedor">
  <h1>EDICIÓN DE DATOS USUARIO</h1>
  <form id="form1" name="form1" method="post" action="../server/actualizar_usuario.php">
	  <input type="hidden" name="id_usuario" value="<?php echo $usuarios['id_usuario']; ?>">
    <fieldset>
      <legend>Datos Personales</legend>
      <p>
        <label for="textfield">Nombre:</label>
        <input type="text" name="Nombre_user" value="<?= $usuarios['Nombre_user'] ?>" id="Nombre_user" autofocus pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]{2,}" title="Solo letras" onchange="validar_letras(this)">
      </p>
      <p>
        <label for="textfield2">Nombre de usuario:</label>
        <input type="text" name="Usuario_user" id="Usuario_user" value="<?= $usuarios['Usuario_user'] ?>" 
			   pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]{2,}" title="Solo letras" onChange="validar_letras(this)">
      </p>
      <p>
        <label for="textfield3">Cedula:</label>
        <input type="text" name="Cedula_user" id="Cedula_user" value="<?= $usuarios['Cedula_user'] ?>" maxlength="10" 
			   pattern="[0-9]{10}" title="Debe contener 10 dígitos" onChange="validar_cedula()">
      </p>
      <p>
        <label for="textfield4">Clave:</label>
        <input type="text" name="Clave_user" id="Clave_user" value="<?= $usuarios['Clave_user'] ?>">
      </p>
		
	  <p>
        <label for="textfield4">ID ROL:</label>
        <input type="number" name="id_rol" id="id_rol" value="<?= $usuarios['id_rol'] ?>">
      </p>
      
    </fieldset>
	<p>
	  <input type="submit" name="submit" id="submit" value="Guardar cambios">
	</p>
	  
	<a href="listar_usuarios.php"><input type="button" value="Cancelar"></a>
	 
  </form>
</div>
<script src="../script/validacion.js" type="text/javascript"></script>
</body>
</html>