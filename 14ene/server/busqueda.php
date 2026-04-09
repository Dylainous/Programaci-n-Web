<?php
include("conexion.php");

if (isset($_POST['buscar'])) {

    $sql = "";

    if ($_POST['buscar_por'] == "cedula") {
        $cedula = $_POST['cedula'];
        $sql = "SELECT * FROM usuarios WHERE cedula LIKE '%$cedula%'";
    }

    if ($_POST['buscar_por'] == "nombre") {
        $nombre = $_POST['nombre'];
        $sql = "SELECT * FROM usuarios WHERE nombres LIKE '%$nombre%'";
    }

    $resultado = $conn->query($sql);

    if ($resultado->num_rows > 0) {
        echo "<table>";
        echo "<tr>
                <th>ID</th>
                <th>Apellidos</th>
                <th>Nombres</th>
                <th>Cédula</th>
                <th>Email</th>
                <th>Estado</th>
              </tr>";

        while ($fila = $resultado->fetch_assoc()) {
            echo "<tr>
                    <td>{$fila['id']}</td>
                    <td>{$fila['apellidos']}</td>
                    <td>{$fila['nombres']}</td>
                    <td>{$fila['cedula']}</td>
                    <td>{$fila['email']}</td>
                    <td>{$fila['estado']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No se encontraron resultados.";
    }
}
?>
</body>
</html>
