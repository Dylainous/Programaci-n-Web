// JavaScript Document
document.addEventListener("DOMContentLoaded", function () {

    const formulario = document.getElementById("formulario");

    formulario.addEventListener("submit", function (event) {
        event.preventDefault();
		
        const usuario = document.getElementById("textfield").value;
        const clave = document.getElementById("password").value;

        if (usuario === "Usuario" && clave === "Clave") {
            window.location.href = "html/principal.php";
        } else {
            window.location.href = "html/error.html";
        }
    });

});

