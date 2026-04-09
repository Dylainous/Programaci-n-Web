<?php
include_once "conexion.php";

$id_usuario = $_POST["id_usuario"];
$Nombre_user = $_POST["Nombre_user"];
$Usuario_user = $_POST["Usuario_user"];
$Cedula_user = $_POST["Cedula_user"];
$Clave_user = $_POST["Clave_user"];
$id_rol = $_POST["id_rol"];

$sql = "UPDATE usuarios SET
        Nombre_user='$Nombre_user',
        Usuario_user='$Usuario_user',
        Cedula_user='$Cedula_user',
        Clave_user='$Clave_user',
		id_rol='$id_rol'
        WHERE id_usuario=$id_usuario";

$stmt = $conn->prepare($sql);

$stmt->bind_param("ssssi", $Nombre_user, $Usuario_user, $Cedula_user, $Clave_user, $id_rol, $id_usuario);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: ../paginas/listar_usuarios.php");
    exit;
} else {
    echo "Error al actualizar";
}

?>