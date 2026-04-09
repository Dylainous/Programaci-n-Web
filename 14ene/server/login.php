<?php
include_once("conexion.php");

$usuario = $_POST['usuario'];
$clave   = $_POST['clave'];

$sql = "SELECT * FROM usuarios 
        WHERE Usuario_user = ? 
        AND Clave_user = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $usuario, $clave);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Datos correctos → entra a index.html
    echo "<script>
            window.location.href = '../paginas/index.html';
          </script>";
} else {
    // Datos incorrectos → alert
    echo "<script>
            alert('Usuario o contraseña incorrectos');
            window.location.href = '../index.php';
          </script>";
}
?>
