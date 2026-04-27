document.addEventListener('DOMContentLoaded', () => {
    // --- Selectores de Elementos ---
    const formularioLogin = document.querySelector(".formulario__login");
    const formularioRegister = document.querySelector(".formulario__register");
    const cajaTraseraLogin = document.querySelector(".caja__trasera-login");
    const cajaTraseraRegister = document.querySelector(".caja__trasera-register");
    const btnIniciarSesion = document.getElementById("btn__iniciar-sesion");
    const btnRegistrarse = document.getElementById("btn__registrarse");
    const registerForm = document.getElementById('registerForm');
    const registerErrorsContainer = document.getElementById('registerErrors'); // Contenedor general
    const registerErrorsList = document.getElementById('registerErrorsList'); // Lista general
    const registerSuccessContainer = document.getElementById('registerSuccess');

    // Verificar si todos los elementos principales existen
    if (!formularioLogin || !formularioRegister || !cajaTraseraLogin || !cajaTraseraRegister || !btnIniciarSesion || !btnRegistrarse) {
        console.error("Error: No se encontraron todos los elementos necesarios para la interfaz de login/registro.");
        return; // Detener ejecución si falta algo esencial
    }

    // --- Estado Inicial ---
    // Asegura que el formulario de login y el prompt de registro sean visibles al inicio
    // (Considera si esto debería depender de la URL o errores previos en una recarga de página)
    formularioLogin.classList.add('active');
    cajaTraseraRegister.classList.add('active');
    formularioRegister.classList.remove('active'); // Asegurar que no esté activo
    cajaTraseraLogin.classList.remove('active');   // Asegurar que no esté activo

    // --- Event Listeners ---
    btnIniciarSesion.addEventListener("click", iniciarSesion);
    btnRegistrarse.addEventListener("click", mostrarRegistro);

    if (registerForm) {
        registerForm.addEventListener('submit', manejarRegistro);
    } else {
        console.warn("Advertencia: Formulario de registro (#registerForm) no encontrado.");
    }

    // --- Funciones de Interfaz ---
    function iniciarSesion() {
        // Muestra el formulario de Login y el prompt de Registro
        if (!formularioLogin.classList.contains('active')) {
            formularioRegister.classList.remove('active');
            cajaTraseraLogin.classList.remove('active');

            formularioLogin.classList.add('active');
            cajaTraseraRegister.classList.add('active');
        }
    }
    function mostrarRegistro() {
         // Muestra el formulario de Registro y el prompt de Login
        if (!formularioRegister.classList.contains('active')) {
            formularioLogin.classList.remove('active');
            cajaTraseraRegister.classList.remove('active');

            formularioRegister.classList.add('active');
            cajaTraseraLogin.classList.add('active');
        }
    }

    // --- Función AJAX para el Registro (Muestra errores 422 en lista general) ---
    async function manejarRegistro(e) {
        e.preventDefault(); // Prevenir envío tradicional del formulario
        console.log("Iniciando manejarRegistro...");

        // Validar existencia de elementos necesarios para feedback AJAX
        if (!registerForm || !registerErrorsContainer || !registerSuccessContainer || !registerErrorsList) {
            console.error("Error: Faltan elementos del DOM para el feedback AJAX.");
            return;
        }
        const form = e.target;
        const submitButton = form.querySelector('button[type="submit"]');
        if (!submitButton) {
            console.error("Error: Botón submit no encontrado dentro del formulario de registro.");
            return;
        }
        const originalButtonText = submitButton.textContent; // Guardar texto original del botón

        // --- Limpiar errores y mensajes anteriores ---
        console.log("Limpiando errores y mensajes anteriores...");
        registerErrorsContainer.style.display = 'none';
        registerSuccessContainer.style.display = 'none';
        registerErrorsList.innerHTML = ''; // Limpiar lista de errores
        // Opcional: Limpiar clase de error de inputs si se usara en el futuro
        // form.querySelectorAll('input').forEach(input => { input.classList.remove('input-error'); });

        // --- Mostrar estado de carga en el botón ---
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border" role="status" aria-hidden="true"></span> Registrando...';
        console.log("Enviando datos del formulario de registro...");

        try {
            const formData = new FormData(form); // Obtener datos del formulario
            const csrfToken = form.querySelector('input[name="_token"]')?.value; // Obtener token CSRF

            if (!csrfToken) {
                // Lanzar error si no se encuentra el token CSRF
                throw new Error('Token CSRF no encontrado en el formulario.');
            }

            // --- Realizar la petición Fetch ---
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    // MEJORA: Comentarios explicando las cabeceras
                    'X-Requested-With': 'XMLHttpRequest', // Indica a Laravel que es una petición AJAX
                    'Accept': 'application/json',        // Indica que esperamos una respuesta JSON
                    'X-CSRF-TOKEN': csrfToken            // Envía el token CSRF para protección
                }
            });

            const data = await response.json(); // Intentar parsear la respuesta como JSON
            console.log("Respuesta recibida del servidor:", { status: response.status, ok: response.ok, data: data });

            // --- Comprobar si la respuesta NO fue exitosa (status code fuera de 200-299) ---
            if (!response.ok) {
                console.log(`Respuesta no OK (Status: ${response.status}), lanzando error estructurado...`);
                // Lanzar un objeto error estructurado para el bloque catch
                throw { status: response.status, data: data };
            }

            // --- Éxito (Respuesta OK) ---
            console.log("Registro procesado con éxito por el servidor.");
            registerSuccessContainer.style.display = 'block'; // Mostrar contenedor de éxito
            registerSuccessContainer.textContent = data.message || '¡Registro exitoso! Redirigiendo...'; // Mostrar mensaje de éxito
            form.reset(); // Limpiar el formulario
            // Redirigir después de un breve retraso
            setTimeout(() => {
                // Usar la URL de redirección del servidor si existe, si no, ir a /login
                window.location.href = data.redirect || '/login';
            }, 2000); // Esperar 2 segundos

        } catch (error) {
            // --- Manejo de Errores (Fetch falló o respuesta no OK) ---
            console.error("Error capturado durante el proceso de registro:", error);

            // Restaurar botón a su estado original
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;

            // Limpiar lista de errores antes de añadir nuevos
            registerErrorsList.innerHTML = '';

            if (error.status === 422 && error.data && error.data.errors) {
                // --- Error de Validación Laravel (422) ---
                console.log("Detectado error de validación 422. Mostrando errores en la lista general.");
                const validationErrors = error.data.errors;

                // Llenar la lista general #registerErrorsList con los mensajes de error
                Object.values(validationErrors).forEach(errorMessages => {
                    errorMessages.forEach(message => {
                        const li = document.createElement('li');
                        li.textContent = message;
                        registerErrorsList.appendChild(li);
                        // console.log(`Añadido error a lista general: ${message}`); // Log opcional
                    });
                });

            } else {
                // --- Otro tipo de error (Servidor 500, Red, Error CSRF, etc.) ---
                console.log("Detectado error diferente de 422 o con formato inesperado.");
                const li = document.createElement('li');
                let errorMessage = 'Ocurrió un error inesperado durante el registro. Por favor, intenta nuevamente.';
                // Intentar obtener un mensaje más específico del error
                if (error.data && error.data.message) {
                    errorMessage = error.data.message;
                } else if (error.message) { // Captura errores de red o CSRF no encontrado
                    errorMessage = error.message;
                }
                li.textContent = errorMessage;
                registerErrorsList.appendChild(li);
                console.error("Detalle del error (no 422 o inesperado):", error);
            }

            // Mostrar el contenedor general de errores si se añadieron elementos a la lista
            if (registerErrorsList.children.length > 0) {
                registerErrorsContainer.style.display = 'block';
                console.log("Mostrando contenedor de errores general.");
            } else {
                console.log("No se añadieron errores a la lista general (¿objeto errors vacío o error no manejado?).");
                 // Como fallback, mostrar un mensaje genérico si la lista está vacía pero hubo error
                 if(registerErrorsContainer.style.display !== 'block') {
                     const li = document.createElement('li');
                     li.textContent = 'Error desconocido. Revisa la consola del navegador.';
                     registerErrorsList.appendChild(li);
                     registerErrorsContainer.style.display = 'block';
                 }
            }

        } // Fin catch
    } // Fin manejarRegistro

}); // Fin DOMContentLoaded