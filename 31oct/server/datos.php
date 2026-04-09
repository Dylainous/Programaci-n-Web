<?php
$nombreMascota = $_POST["nombreMascota"];
$especie = $_POST["especie"];
$raza = $_POST["raza"];
$edad = $_POST["edad"];
$sexo = $_POST["sexo"];
$nombreDuenio = $_POST["nombreDuenio"];
$telefono = $_POST["telefono"];
$email = $_POST["email"];
$fechaIngreso = $_POST["fechaIngreso"];
$motivo = $_POST["motivo"];

echo "Nombre de la mascota: $nombreMascota<br>";
echo "Especie: $especie<br>";
echo "Raza: $raza<br>";
echo "Edad: $edad años<br>";
echo "Sexo: $sexo<br><br>";
echo "Nombre del dueño: $nombreDuenio<br>";
echo "Teléfono: $telefono<br>";
echo "Email: $email<br><br>";
echo "Fecha de ingreso: $fechaIngreso<br>";
echo "Motivo de consulta: $motivo";
?>
