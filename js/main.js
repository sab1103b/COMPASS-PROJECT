document.addEventListener("DOMContentLoaded", function () {
    
    // Función Crear cookie con expiración de un año ---------------------------------------------------------------------------------
    function setCookie(name, value) {
        const date = new Date();
        date.setFullYear(date.getFullYear() + 1); // Sumar 1 año a la fecha actual
        const expires = "expires=" + date.toUTCString();
        document.cookie = name + "=" + value + "; " + expires + "; path=/";
    }
    
    // Seleccionar los botones de cookies --------------------------------------------------------------
    const btnCafes = document.getElementById("btn-cafes-aventurate");

    
    const cookieOverlay = document.getElementById("cookie-overlay");
    const acceptCookies = document.getElementById("accept-cookies");
    const rejectCookies = document.getElementById("reject-cookies");

    // Verificar si los botones de cookies existen antes de agregar eventos 
    if (acceptCookies) {
        acceptCookies.addEventListener("click", () => {
            cookieOverlay.style.display = "none"; // Oculta el overlay
            setCookie("username", "Acept_Cookies");
        });
    }

    if (rejectCookies) {
        rejectCookies.addEventListener("click", () => {
            window.location.href = "https://www.google.com"; // Redirige a Google
        });
    }

    // Seleccionar los botones de ingresar y registrar --------------------------------------------------------------
    const btnIngresar = document.querySelector(".auth-buttons .btn:nth-child(1)");
    const btnRegistrarse = document.querySelector(".auth-buttons .btn:nth-child(2)");

    // Seleccionar los botones de ingresar y registrar admin y perfil --------------------------------------------------------------
    const btnIngresarAdmin = document.getElementById("btn-usuario_admin");
    const btnIngresaPerfil = document.getElementById("btn-usuario");
    const btnRegis = document.getElementById("btn-usuario_registro");
    const btnsRegistroAdmin = document.querySelectorAll("#btn-usuario-registro-admin");
    const btnIngresaPerfilAdmin = document.getElementById("btn-usuario-admin");
    const btnCambioContra = document.querySelectorAll("#CambioContraseña");
    const btnCodigoContra = document.getElementById("btn-cambiar-contrasena");
    const btnVerifCod = document.getElementById("btn-verificar-codigo");
    const btnVerificador = document.getElementById("btn-verificador");
    const btnAceptarRegistro = document.getElementById("btn-aceptar-registro");
    const btnAceptarRegistroAdmin = document.getElementById("btn-Aceptar-Registro-Admin");
    const btnAceptarCambioContra = document.getElementById("btn-aceptar-cambio-contraseña");
    const btnCreacionCafeteria = document.getElementById("btn-Creacion-Cafeterias");
    const btnCreacionPremios = document.getElementById("btn-Creacion-premio");
    const btnCafeteriaCreada = document.getElementById("btn-crear-cafeteria");
    const btnPremioCreado = document.getElementById("btn-agregar-premio");

    // Seleccionar las secciones correspondientes --------------------------------------------------------------
    const seccionIngresar = "SEC_ingresar";
    const seccionRegistro = "SEC_registro";
    const seccionCafes = "SEC_cafes";
    const seccionIngresarAdmin = "SEC_ingresar_admin";
    const seccionPerfil = "SEC_perfil";
    const seccionPerfilAdmin = "SEC_perfil_admin";
    const seccionRegistroAdmin = "SEC_registro_admin";
    const seccionCambioContra = "SEC_cambio_contrasena";
    const seccionNuevaContra = "SEC_nueva_contrasena";
    const seccionVerifCod = "SEC_codigo";
    const seccionCrearCafeteria = "SEC_crear_cafeteria";
    const seccionCrearPremios = "SEC_agregar_premio";
    const seccionPrincipal = document.getElementById("principal");

    // sellos  --------------------------------------------------------------
    const sellos = document.querySelectorAll('.sello'); // Todas las imágenes de sellos
    const selectCafeteria = document.getElementById('cafeteria-select'); // Menú desplegable
    const sellarBtn = document.getElementById('btn-verificador'); // Botón "SELLAR"

    // Verificar si el botón "SELLAR" existe antes de agregar el evento --------------------------------------------------------------
    if (sellarBtn) {
        sellarBtn.addEventListener('click', () => {
            const selectedValue = selectCafeteria.value; // Obtener el valor seleccionado del menú desplegable

            if (selectedValue) {
                // Buscar el sello correspondiente y quitar la opacidad
                sellos.forEach((sello) => {
                    if (sello.dataset.cafeteria === selectedValue) {
                        sello.classList.add('activo'); // Agregar clase para mostrar el sello con opacidad completa
                    }
                });
            } else {
                alert('Por favor selecciona una cafetería.'); // Mostrar alerta si no se selecciona una cafetería
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

    if (btnAceptarRegistro) {
        btnAceptarRegistro.addEventListener("click", function () {
            mostrarSeccion(seccionIngresar);
        });
    }

    if (btnAceptarRegistroAdmin) {
        btnAceptarRegistroAdmin.addEventListener("click", function () {
            mostrarSeccion(seccionIngresarAdmin);
        });
    }

    if (btnAceptarCambioContra) {
        btnAceptarCambioContra.addEventListener("click", function () {
            mostrarSeccion(seccionIngresar);
        });
    }

    if (btnCreacionCafeteria) {
        btnCreacionCafeteria.addEventListener("click", function () {
            mostrarSeccion(seccionCrearCafeteria);
        });
    }

    if (btnCreacionPremios) {
        btnCreacionPremios.addEventListener("click", function () {
            mostrarSeccion(seccionCrearPremios);
        });
    }

    if (btnCafeteriaCreada) {
        btnCafeteriaCreada.addEventListener("click", function () {
            mostrarSeccion(seccionPerfilAdmin);
        });
    }

    if (btnPremioCreado) {
        btnPremioCreado.addEventListener("click", function () {
            mostrarSeccion(seccionPerfilAdmin);
        });
    }
    // Mostrar la sección principal al inicio --------------------------------------------------------------
    mostrarSeccion("principal");

    const enlacesNavegacion = document.querySelectorAll('a[data-seccion]');

    // Función para mostrar la sección correspondiente --------------------------------------------------------------
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
    window.mostrarSeccion = mostrarSeccion;

    // Asignar evento click a cada enlace --------------------------------------------------------------
    enlacesNavegacion.forEach(enlace => {
        enlace.addEventListener("click", function (event) {
            event.preventDefault(); // Evitar el comportamiento predeterminado del enlace
            const seccionId = this.getAttribute("data-seccion"); // Obtener el ID de la sección
            mostrarSeccion(seccionId); // Mostrar la sección correspondiente
        });
    });

    // Asignar eventos a los botones --------------------------------------------------------------
    const btnCompass = document.getElementById("btn-compass");
    const btnCafesNav = document.getElementById("btn-cafes");
    const btnLocales = document.getElementById("btn-locales");
    const btnNosotros = document.getElementById("btn-nosotros");

    if (btnCompass) {
        btnCompass.addEventListener("click", () => mostrarSeccion("principal"));
    }

    if (btnCafesNav) {
        btnCafesNav.addEventListener("click", () => mostrarSeccion("SEC_cafes"));
    }

    if (btnLocales) {
        btnLocales.addEventListener("click", () => mostrarSeccion("SEC_locales"));
    }

    if (btnNosotros) {
        btnNosotros.addEventListener("click", () => mostrarSeccion("SEC_nosotros"));
    }
});


// AJAX Y DATOS COMO OBJETOS --------------------------------------------------------------
window.onload = function () {
    // Lista de IDs de formularios a manejar por AJAX
    const formIds = [
        "registro-form",
        "crear-cafeteria-form",
        "agregar-premio-form",
        "registro-admin-form",
        "codigo-form" 
        // Agrega aquí otros IDs si tienes más formularios
    ];

    const loginFormIds = [
        "login-form",
        "login-admin-form"
        // Agrega aquí otros IDs si tienes más formularios
    ];

    const NewContrasena = [
        "nueva-contrasena-form",
        "cambio-contrasena-form"
        // Agrega aquí otros IDs si tienes más formularios
    ];

    //Formularios de registro y otros (envían a registro.php)
    formIds.forEach(formId => {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener("submit", function (event) {
                event.preventDefault();
                let inputData = new FormData(form);
                let dataObject = Object.fromEntries(inputData.entries());
                ajaxRequest("php/registro.php", "POST", dataObject, function(response){
                    // Puedes mostrar mensajes aquí si lo deseas
                    try {
                        const res = JSON.parse(response);
                        if (res.success) {
                            alert(res.success);
                        } else if (res.error) {
                            alert(res.error);
                        }
                    } catch (e) {
                        alert("Error en la respuesta del servidor.");
                    }
                });
            });
        }
    });

    // Formularios de login (envían a Ingresousu.php)
    loginFormIds.forEach(loginFormIds => {
    const form = document.getElementById(loginFormIds);
        if (form) {
            form.addEventListener("submit", function (event) {
                event.preventDefault(); // Esto evita el submit tradicional
                let inputData = new FormData(form);
                let dataObject = Object.fromEntries(inputData.entries());
                ajaxRequest("php/Ingresousu.php", "POST", dataObject, function(response){
                    let res;
                    try {
                        res = JSON.parse(response);
                    } catch (e) {
                        alert("Error en la respuesta del servidor.");
                        return;
                    }

                    if (res.success) {
                        // Solo aquí avanza a la siguiente sección
                        if (form.id === "login-admin-form") {
                            mostrarSeccion("SEC_perfil_admin");
                        } else {
                            mostrarSeccion("SEC_perfil");
                        }
                        alert(res.success);
                    } else if (res.error) {
                        // NO avanza, solo muestra el error
                        alert(res.error);
                    }
                });
            });
        }
    });

    NewContrasena.forEach(formId => {
    const form = document.getElementById(formId);
    if (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault();
            let inputData = new FormData(form);
            let dataObject = Object.fromEntries(inputData.entries());

            // Si estamos en el segundo paso (nueva contraseña), añade correo/celular del usuario recuperado
            if (formId === "nueva-contrasena-form" && window.usuarioRecuperacion) {
                dataObject.correo = window.usuarioRecuperacion.correo;
                dataObject.celular = window.usuarioRecuperacion.celular;
            }

            ajaxRequest("php/NuevaContra.php", "POST", dataObject, function(response){
                try {
                    const res = JSON.parse(response);

                    // Primer paso: verificación de usuario
                    if (formId === "cambio-contrasena-form") {
                        if (res.usuario) {
                            // Guarda datos para el segundo paso
                            window.usuarioRecuperacion = {
                                correo: res.usuario.Correo,
                                celular: res.usuario.Celular,
                                esAdmin: res.esAdmin
                            };
                            mostrarSeccion("SEC_nueva_contrasena");
                        } else if (res.error) {
                            alert(res.error);
                        }
                    }
                    // Segundo paso: cambio de contraseña
                    else if (formId === "nueva-contrasena-form") {
                        if (res.success) {
                            alert(res.success);
                            mostrarSeccion("SEC_ingresar"); // O la sección que quieras mostrar tras el cambio
                        } else if (res.error) {
                            alert(res.error);
                        }
                    }
                } catch (e) {
                    alert("Error en la respuesta del servidor.");
                }
            });
        });
    }
});

    function ajaxRequest(url, method, data, callback) {
        let xhr = new XMLHttpRequest();
        xhr.open(method, url, true);
        xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                callback(xhr.responseText);
            }
        };
        xhr.send(JSON.stringify(data));
    }
};