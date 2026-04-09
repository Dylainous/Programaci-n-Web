<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
</head>

<body>
<?php
include_once("conexion.php")
	
$sql = "UPDATE FROM alumno WHERE id=3 SET estado='activo'";

if ($conn->query($sql) === TRUE) {
  echo ("<scrip>alert('Registro inactivo')</script>");
} else {
  echo ("Error deleting record: " . $conn->error);
}

$conn->close();
?>
</body>
</html>