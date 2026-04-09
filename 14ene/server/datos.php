<?php
$apellidos = $_POST["apellidos"];
$nombres   = $_POST["nombres"];
$cedula    = $_POST["cedula"];
$email     = $_POST["email"];

echo "<h2>Datos recibidos</h2>";
echo "<p><strong>Apellidos:</strong> $apellidos</p>";
echo "<p><strong>Nombres:</strong> $nombres</p>";
echo "<p><strong>Cédula:</strong> $cedula</p>";
echo "<p><strong>Email:</strong> $email</p>";
?>
