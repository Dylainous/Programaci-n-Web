function validar_letras(campo) {
    let regex = /^[A-Za-zÁÉÍÓÚáéíóúÑñ ]+$/;

    if (!regex.test(campo.value)) {
        alert("Solo se permiten letras");
        campo.value = "";
        campo.focus();
        return false;
    }
    return true;
}

function validar_email() {
    let email = document.getElementById("email").value;
    let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!regex.test(email)) {
        alert("Correo electrónico inválido");
        document.getElementById("email").focus();
        return false;
    }
    return true;
}

function validar_cedula() {
    let cedula = document.getElementById("cedula").value;

    if (cedula.length !== 10 || isNaN(cedula)) {
        alert("La cédula debe tener 10 dígitos numéricos");
        return false;
    }

    let provincia = parseInt(cedula.substring(0, 2));
    if (provincia < 1 || provincia > 24) {
        alert("Código de provincia inválido");
        return false;
    }

    let digitoVerificador = parseInt(cedula.charAt(9));
    let suma = 0;

    for (let i = 0; i < 9; i++) {
        let num = parseInt(cedula.charAt(i));

        if (i % 2 === 0) { // posiciones pares
            num *= 2;
            if (num > 9) num -= 9;
        }
        suma += num;
    }

    let resultado = (10 - (suma % 10)) % 10;

    if (resultado !== digitoVerificador) {
        alert("Cédula ecuatoriana inválida");
        document.getElementById("cedula").focus();
        return false;
    }

    return true;
}
