document.addEventListener("DOMContentLoaded", function () {
    
    const btnCafes = document.getElementById("btn-cafes-aventurate");

    // Seleccionar los botones de cookies
    const cookieOverlay = document.getElementById("cookie-overlay");
    const acceptCookies = document.getElementById("accept-cookies");
    const rejectCookies = document.getElementById("reject-cookies");

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

    // Seleccionar los botones de navegación
    const btnCafesAventurate = document.getElementById("btn-cafes-aventurate");

    acceptCookies.addEventListener("click", () => {
        cookieOverlay.style.display = "none"; // Oculta el overlay
    });

    // Al rechazar cookies
    rejectCookies.addEventListener("click", () => {
        window.location.href = "https://www.google.com"; // Redirige a Google
    });

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

    btnIngresar.addEventListener("click", function () {
        mostrarSeccion(seccionIngresar);
    });

    btnRegistrarse.addEventListener("click", function () {
        mostrarSeccion(seccionRegistro);
    });

    btnIngresarAdmin.addEventListener("click", function () {
        mostrarSeccion(seccionIngresarAdmin);
    });

    btnCafes.addEventListener("click", function() {
        mostrarSeccion(seccionCafes);
    });

    btnIngresaPerfil.addEventListener("click", function() {
        mostrarSeccion(seccionPerfil);
    });

    btnRegis.addEventListener("click", function() {
        mostrarSeccion(seccionRegistro);
    });

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

    btnCodigoContra.addEventListener("click", function() {
        mostrarSeccion(seccionNuevaContra);
    });

    btnIngresaPerfilAdmin.addEventListener("click", function() {
        mostrarSeccion(seccionPerfilAdmin);
    });

    // Mostrar la sección principal al inicio
    mostrarSeccion("principal");

    // Asignar eventos a los botones
    document.getElementById("btn-compass").addEventListener("click", () => mostrarSeccion("principal"));
    document.getElementById("btn-cafes").addEventListener("click", () => mostrarSeccion("SEC_cafes"));
    document.getElementById("btn-locales").addEventListener("click", () => mostrarSeccion("SEC_locales"));
    document.getElementById("btn-nosotros").addEventListener("click", () => mostrarSeccion("SEC_nosotros"));
});
