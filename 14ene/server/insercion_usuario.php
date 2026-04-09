<?php
include_once 'conexion.php';

//Recoger datos formulario
$Nombre_user  = $_POST["Nombre_user"];
$Usuario_user = $_POST["Usuario_user"];
$Cedula_user  = $_POST["Cedula_user"];
$Clave_user   = $_POST["Clave_user"];
$id_rol       = $_POST["id_rol"];

// Create connection
//$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection


//if ($conn->connect_error) {
//  die("Conexión fallida: " . $conn->connect_error);
//}

//Insertar datos en la base programacionweb, tabla alumnos
$sql = "INSERT INTO usuarios (Nombre_user, Usuario_user, Cedula_user, Clave_user, id_rol)
VALUES ('$Nombre_user', '$Usuario_user', '$Cedula_user', '$Clave_user' , '$id_rol')";

if ($conn->query($sql) === TRUE) {
  echo "<script>alert('Registro creado');
  				window.location.href='../index.php';
        </script>";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>