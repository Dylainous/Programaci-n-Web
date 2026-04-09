<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Página Principal</title>
<link rel="stylesheet" href="../css/principal.css">
</head>

<body>

<header class="cabecera">
    <div class="logo">
        <img src="../img/logo.png" alt="Logo">
    </div>

    <div class="usuario">
        <p>Usuario Logueado</p>
        <p id="hora"></p>
    </div>
</header>


<nav class="menu">
    <button onclick="cargarIngreso()">Ingresar</button>
    <button onclick="salir()">Salir</button>
</nav>

<section class="contenido">
    <h2>Ingreso de Datos</h2>
    <div id="zonaFormulario"></div>
</section>


<section class="datos">
    <h2>Datos Ingresados</h2>
    <p id="resultado">Aún no hay datos</p>
</section>

<script src="../js/principal.js"></script>
</body>
</html>
