document.addEventListener("DOMContentLoaded", function () {
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

    // Mostrar la sección principal al inicio
    mostrarSeccion("principal");

    // Asignar eventos a los botones
    document.getElementById("btn-compass").addEventListener("click", () => mostrarSeccion("principal"));
    document.getElementById("btn-cafes").addEventListener("click", () => mostrarSeccion("SEC_cafes"));
    document.getElementById("btn-locales").addEventListener("click", () => mostrarSeccion("SEC_locales"));
    document.getElementById("btn-nosotros").addEventListener("click", () => mostrarSeccion("SEC_nosotros"));
});
