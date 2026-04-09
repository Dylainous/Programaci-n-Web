<?php
include_once "conexion.php";

$id = $_POST["id"];
$apellidos = $_POST["apellidos"];
$nombres = $_POST["nombres"];
$cedula = $_POST["cedula"];
$email = $_POST["email"];

$sql = "UPDATE alumno SET
        apellidos='$apellidos',
        nombres='$nombres',
        cedula='$cedula',
        email='$email'
        WHERE id=$id";

$stmt = $conn->prepare($sql);

$stmt->bind_param("ssssi", $apellidos, $nombres, $cedula, $email, $id);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header("Location: ../paginas/listar_alumnos.php");
    exit;
} else {
    echo "Error al actualizar";
}

?>
