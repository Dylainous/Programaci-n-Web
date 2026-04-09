<?php
include_once "conexion.php";

$id = $_POST["id"];
$estado = $_POST["estado"];

$sql = "UPDATE alumno SET estado = '$estado' WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    header("Location: ../paginas/listar_alumnos.php");
} else {
    echo "Error al cambiar el estado";
}

$conn->close();
?>
