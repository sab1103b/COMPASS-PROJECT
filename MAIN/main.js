document.addEventListener("DOMContentLoaded", function () {
    
    // Seleccionar los botones de cookies
    const cookieOverlay = document.getElementById("cookie-overlay");
    const acceptCookies = document.getElementById("accept-cookies");
    const rejectCookies = document.getElementById("reject-cookies");

    // Seleccionar los botones de ingresar y registrar
    const btnIngresar = document.querySelector(".auth-buttons .btn:nth-child(1)");
    const btnRegistrarse = document.querySelector(".auth-buttons .btn:nth-child(2)");

    // Seleccionar las secciones correspondientes
    const seccionIngresar = "SEC_ingresar";
    const seccionRegistro = "SEC_registro";
    const seccionPrincipal = document.getElementById("principal");

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

    // Mostrar la sección principal al inicio
    mostrarSeccion("principal");

    // Asignar eventos a los botones
    document.getElementById("btn-compass").addEventListener("click", () => mostrarSeccion("principal"));
    document.getElementById("btn-cafes").addEventListener("click", () => mostrarSeccion("SEC_cafes"));
    document.getElementById("btn-locales").addEventListener("click", () => mostrarSeccion("SEC_locales"));
    document.getElementById("btn-nosotros").addEventListener("click", () => mostrarSeccion("SEC_nosotros"));
});
