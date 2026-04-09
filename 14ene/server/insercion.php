<?php
include_once 'conexion.php';

//Recoger datos formulario
$apellidos = $_POST["apellidos"];
$nombres   = $_POST["nombres"];
$cedula    = $_POST["cedula"];
$email     = $_POST["email"];

// Create connection
//$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection


//if ($conn->connect_error) {
//  die("Conexión fallida: " . $conn->connect_error);
//}

//Insertar datos en la base programacionweb, tabla alumno
$sql = "INSERT INTO alumno (apellidos, nombres, cedula, email)
VALUES ('$apellidos', '$nombres', '$cedula', '$email')";

if ($conn->query($sql) === TRUE) {
  echo "<script>alert('Registro creado');
  				window.location.href='../paginas/index.html';
        </script>";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>