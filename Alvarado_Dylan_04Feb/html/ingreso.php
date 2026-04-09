<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Formulario</title>
<link rel="stylesheet" href="../css/estilosForm.css">
</head>

<body>
<div class="contenedor">
  <form id="form" class="form" name="form" method="post">
	<h1>Formulario</h1>
	<fieldset>
	<p>
	  <label for="textfield">Dylan:</label>
      <input type="text" name="textfield" id="textfield" onChange="validar_letras(this)">
	</p>
	<p>
	  <label for="textfield2">Apellido:</label>
      <input type="text" name="textfield2" id="textfield2" onChange="validar_letras(this)">
	</p>
	<p>
	  <label for="textfield3">Cedula:</label>
      <input type="text" name="cedula" id="cedula" max="10" maxlength="10" onChange="validar_cedula()">
	</p>
	<p>
	  <label for="textfield4">Cargo:</label>
      <input type="text" name="textfield4" id="textfield4">
	</p>
  <p>Genero</p>
	<p>
	  <label>
	    <input type="radio" name="Genero" value="opción" id="Genero_0">
      Masculino</label>
	  <br>
	  <label>
	    <input type="radio" name="Genero" value="opción" id="Genero_1">
	    Femenino</label>
	</p>
	<p>
	  <label for="number">Sueldo:</label>
      <input type="number" name="number" id="number">
    </p>
	</fieldset>
	<p>
	  <input type="submit" name="submit" id="submit" value="Enviar">
	  <input type="reset" name="reset" id="reset" value="Restablecer">
	  <br>
    </p>
	
  </form>
</div>

<script src="../js/validacion.js"></script>
</body>
</html>