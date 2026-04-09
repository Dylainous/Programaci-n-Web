<?php
include_once "conexion.php";

$id_usuario = $_POST["id_usuario"];
$Estado_user = $_POST["Estado_user"];

$sql = "UPDATE usuarios SET Estado_user = '$Estado_user' WHERE id_usuario = $id_usuario";

if ($conn->query($sql) === TRUE) {
    header("Location: ../paginas/listar_usuarios.php");
} else {
    echo "Error al cambiar el estado";
}

$conn->close();
?>