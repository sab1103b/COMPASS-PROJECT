document.addEventListener("DOMContentLoaded", function () {
    const btnCafes = document.getElementById("btn-cafes-aventurate");

    // Seleccionar los botones de cookies
    const cookieOverlay = document.getElementById("cookie-overlay");
    const acceptCookies = document.getElementById("accept-cookies");
    const rejectCookies = document.getElementById("reject-cookies");

    // Verificar si los botones de cookies existen antes de agregar eventos
    if (acceptCookies) {
        acceptCookies.addEventListener("click", () => {
            cookieOverlay.style.display = "none"; // Oculta el overlay
        });
    }

    if (rejectCookies) {
        rejectCookies.addEventListener("click", () => {
            window.location.href = "https://www.google.com"; // Redirige a Google
        });
    }

    // Seleccionar los botones de ingresar y registrar
    const btnIngresar = document.querySelector(".auth-buttons .btn:nth-child(1)");
    const btnRegistrarse = document.querySelector(".auth-buttons .btn:nth-child(2)");

    // Seleccionar los botones de ingresar y registrar admin y perfil
    const btnIngresarAdmin = document.getElementById("btn-usuario_admin");
    const btnIngresaPerfil = document.getElementById("btn-usuario");
    const btnRegis = document.getElementById("btn-usuario_registro");
    const btnsRegistroAdmin = document.querySelectorAll("#btn-usuario-registro-admin");
    const btnIngresaPerfilAdmin = document.getElementById("btn-usuario-admin");
    const btnCambioContra = document.querySelectorAll("#CambioContraseña");
    const btnCodigoContra = document.getElementById("btn-cambiar-contrasena");
<<<<<<< HEAD
    const btnVerifCod = document.getElementById("btn-verificar-codigo");
    const btnVerificador = document.getElementById("btn-verificador");

=======
>>>>>>> 698807bea0fe4756e6aa09b21de5634a9672764a
    // Seleccionar las secciones correspondientes
    const seccionIngresar = "SEC_ingresar";
    const seccionRegistro = "SEC_registro";
    const seccionCafes = "SEC_cafes";
    const seccionIngresarAdmin = "SEC_ingresar_admin";
    const seccionPerfil = "SEC_perfil";
    const seccionPerfilAdmin = "SEC_perfil_admin";
    const seccionRegistroAdmin = "SEC_registro_admin";
    const seccionCambioContra = "SEC_cambio_contrasena";
    const seccionNuevaContra = "SEC_nueva_contrasena";
    const seccionPrincipal = document.getElementById("principal");

    // sellos
    const sellos = document.querySelectorAll('.sello');
    const selectCafeteria = document.getElementById('cafeteria-select');
    const sellarBtn = document.getElementById('sellar-btn');

    // Verificar si el botón "SELLAR" existe antes de agregar el evento
    if (sellarBtn) {
        sellarBtn.addEventListener('click', () => {
            const selectedValue = selectCafeteria.value;

            if (selectedValue) {
                // Buscar el sello correspondiente y quitar la opacidad
                sellos.forEach((sello) => {
                    if (sello.dataset.cafeteria === selectedValue) {
                        sello.classList.add('activo');
                    }
                });
            } else {
                alert('Por favor selecciona una cafetería.');
            }
        });
    }

    function mostrarSeccion(seccionId) {
        // Ocultar todas las secciones
        document.querySelectorAll("main section").forEach(seccion => {
            seccion.style.display = "none";
        });

        // Mostrar la sección seleccionada
        const seccionMostrada = document.getElementById(seccionId);
        if (seccionMostrada) {
            seccionMostrada.style.display = "block";
        }
    }

    if (btnIngresar) {
        btnIngresar.addEventListener("click", function () {
            mostrarSeccion(seccionIngresar);
        });
    }

    if (btnRegistrarse) {
        btnRegistrarse.addEventListener("click", function () {
            mostrarSeccion(seccionRegistro);
        });
    }

    if (btnIngresarAdmin) {
        btnIngresarAdmin.addEventListener("click", function () {
            mostrarSeccion(seccionIngresarAdmin);
        });
    }

    if (btnCafes) {
        btnCafes.addEventListener("click", function () {
            mostrarSeccion(seccionCafes);
        });
    }

    if (btnIngresaPerfil) {
        btnIngresaPerfil.addEventListener("click", function () {
            mostrarSeccion(seccionPerfil);
        });
    }

    if (btnRegis) {
        btnRegis.addEventListener("click", function () {
            mostrarSeccion(seccionRegistro);
        });
    }

    btnsRegistroAdmin.forEach(btn => {
        btn.addEventListener("click", function () {
            mostrarSeccion(seccionRegistroAdmin);
        });
    });

    btnCambioContra.forEach(btn => {
        btn.addEventListener("click", function () {
            mostrarSeccion(seccionCambioContra);
        });
    });

    if (btnCodigoContra) {
        btnCodigoContra.addEventListener("click", function () {
            mostrarSeccion(seccionNuevaContra);
        });
    }

    if (btnIngresaPerfilAdmin) {
        btnIngresaPerfilAdmin.addEventListener("click", function () {
            mostrarSeccion(seccionPerfilAdmin);
        });
    }

    if (btnVerifCod) {
        btnVerifCod.addEventListener("click", function () {
            mostrarSeccion(seccionVerifCod);
        });
    }

    if (btnVerificador) {
        btnVerificador.addEventListener("click", function () {
            mostrarSeccion(seccionPerfil);
        });
    }

    // Mostrar la sección principal al inicio
    mostrarSeccion("principal");

    // Asignar eventos a los botones
    const btnCompass = document.getElementById("btn-compass");
    const btnLocales = document.getElementById("btn-locales");
    const btnNosotros = document.getElementById("btn-nosotros");

    if (btnCompass) {
        btnCompass.addEventListener("click", () => mostrarSeccion("principal"));
    }

    if (btnCafes) {
        btnCafes.addEventListener("click", () => mostrarSeccion("SEC_cafes"));
    }

    if (btnLocales) {
        btnLocales.addEventListener("click", () => mostrarSeccion("SEC_locales"));
    }

    if (btnNosotros) {
        btnNosotros.addEventListener("click", () => mostrarSeccion("SEC_nosotros"));
    }
});