// JavaScript Document

function actualizarHora() {
    const ahora = new Date();
    document.getElementById("hora").textContent =
        ahora.toLocaleTimeString();
}
setInterval(actualizarHora, 1000);
actualizarHora();

function cargarIngreso() {
    fetch("../html/ingreso.php")
        .then(res => res.text())
        .then(html => {
            document.getElementById("zonaFormulario").innerHTML = html;
        });
}


function salir() {
    window.location.href = "../html/cerrar.php";
}
