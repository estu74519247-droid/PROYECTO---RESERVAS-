// js/main.js — Interacciones del sistema PrimeLab.

/**
 * Filtro en tiempo real para las tablas del panel administrativo.
 * Requiere: input#adminSearchInput y table#tablaAdmin
 */
function filtrarTablaAdmin() {
    const input = document.getElementById('adminSearchInput');
    const tabla = document.getElementById('tablaAdmin');
    if (!input || !tabla) return;

    const filtro = input.value.toLowerCase().trim();
    const filas = tabla.querySelectorAll('tbody tr');

    filas.forEach((fila) => {
        if (fila.querySelector('.table-empty')) return;
        const texto = (fila.textContent || '').toLowerCase();
        fila.style.display = texto.includes(filtro) ? '' : 'none';
    });
}

/**
 * Mensajes que llegan por la URL desde los archivos PHP.
 * Ejemplo: index.html?error=noexiste
 */
const MENSAJES = {
    login: {
        dni: 'El DNI debe tener exactamente 8 dígitos numéricos.',
        pass: 'La contraseña no es correcta. Recuerda que la contraseña inicial de tu cuenta es tu DNI.',
        noexiste: 'No encontramos una cuenta con ese DNI. Regístrate para crear tu perfil.'
    },
    medico_login: {
        cmp: 'Ingresa un número de CMP válido.',
        noexiste: 'No encontramos un médico con ese CMP. Verifica el número.'
    },
    admin: {
        campos: 'Completa usuario, correo y contraseña.',
        credenciales: 'Las credenciales no coinciden con la cuenta administrativa.'
    },
    registro: {
        campos: 'Completa todos los campos obligatorios.',
        nombre: 'Los nombres y apellidos solo deben contener letras, sin números.',
        dni: 'El DNI debe tener exactamente 8 dígitos numéricos.',
        tel: 'El teléfono debe tener exactamente 9 dígitos numéricos.',
        correo: 'El correo electrónico no tiene un formato válido.',
        existe: 'Ya existe una cuenta registrada con ese DNI.',
        sistema: 'No se pudo completar el registro. Intenta nuevamente.'
    },
    perfil: {
        campos: 'Completa los campos obligatorios.',
        tel: 'El teléfono debe tener exactamente 9 dígitos numéricos.',
        correo: 'El correo electrónico no tiene un formato válido.',
        actualiza: 'Tus datos fueron actualizados correctamente.',
        confirma: 'Debes confirmar tu DNI para eliminar la cuenta.',
        confirma_dni: 'El DNI ingresado no coincide con tu cuenta.'
    },
    reservar: {
        campos: 'Completa los datos de la reserva y el motivo de la consulta.',
        centro: 'Selecciona un centro para continuar con la reserva.',
        seleccion: 'Elige una especialidad del centro y un médico de esa especialidad.',
        motivo: 'El motivo de la consulta no puede superar los 255 caracteres.',
        horario: 'Selecciona una fecha válida y un horario disponible.',
        nohorario: 'Ese horario ya no está disponible para el médico seleccionado.',
        sistema: 'No se pudo registrar la cita. Intenta nuevamente.'
    },
    ticket: {
        creada: 'Tu cita fue registrada correctamente. Guarda tu código de cita.'
    }
};

const MENSAJES_GENERALES = {
    salir: { tipo: 'success', texto: 'Cerraste sesión correctamente.' },
    eliminado: { tipo: 'success', texto: 'Tu cuenta y todas tus citas fueron eliminadas.' },
    sesion: { tipo: 'error', texto: 'Debes iniciar sesión para continuar.' }
};

/**
 * Dirección del proyecto cuando se ejecuta con Apache + PHP (XAMPP).
 * Live Server (puerto 5500) solo sirve archivos estáticos y no ejecuta PHP,
 * por eso los enlaces y formularios se redirigen a la carpeta del proyecto
 * en Apache. Se calcula con el mismo host para funcionar en cualquier IP.
 * Puedes cambiarla antes de cargar main.js con:
 *   window.PRIMELAB_APACHE_URL = 'http://192.168.1.50:8080/PrimeLab/';
 */
const RUTA_APACHE = (
    window.PRIMELAB_APACHE_URL ||
    (location.protocol + '//' + location.hostname + '/PrimeLab/')
).replace(/\/+$/, '/');

/** Indica si la página se abrió con un servidor que no ejecuta PHP. */
function entornoSinPhp() {
    const puerto = parseInt(location.port || '0', 10);
    return location.protocol === 'file:' || (puerto >= 5500 && puerto <= 5599);
}

/**
 * Corrige los enlaces y formularios cuando el proyecto se abre con Live Server.
 * Así el registro y el acceso llegan a los archivos PHP de Apache y no a un
 * error 405 del servidor estático.
 */
function configurarEntorno() {
    window.RUTA_API_DNI = 'consulta_dni.php';

    if (!entornoSinPhp()) {
        return;
    }

    window.RUTA_API_DNI = RUTA_APACHE + 'consulta_dni.php';

    const convertirRuta = (ruta) => {
        if (!ruta || /^(https?:|#|mailto:|javascript:)/i.test(ruta)) {
            return ruta;
        }
        return RUTA_APACHE + ruta.replace(/^\.?\//, '');
    };

    // Enlaces entre páginas HTML y PHP
    document.querySelectorAll('a[href]').forEach((enlace) => {
        if (/\.(html|php)(\?.*)?(#.*)?$/i.test(enlace.getAttribute('href'))) {
            enlace.setAttribute('href', convertirRuta(enlace.getAttribute('href')));
        }
    });

    // Formularios que envían datos a los archivos PHP
    document.querySelectorAll('form[action]').forEach((formulario) => {
        if (/\.php(\?.*)?$/i.test(formulario.getAttribute('action'))) {
            formulario.setAttribute('action', convertirRuta(formulario.getAttribute('action')));
        }
    });

    const aviso = document.createElement('div');
    aviso.className = 'entorno-aviso';
    aviso.innerHTML = '<strong>Live Server no ejecuta PHP.</strong> ' +
        'Los enlaces y formularios se enviarán a Apache para que el sistema funcione. ' +
        '<a href="' + RUTA_APACHE + '">Abrir la versión correcta</a>';
    document.body.appendChild(aviso);
}

/**
 * Muestra el mensaje indicado en la URL dentro de #alertBox.
 */
function mostrarMensajeUrl() {
    const box = document.getElementById('alertBox');
    if (!box) return;

    const params = new URLSearchParams(window.location.search);
    const page = document.body.dataset.page || '';

    let texto = '';
    let tipo = 'error';

    const error = params.get('error');
    const ok = params.get('ok');
    const msg = params.get('msg');

    if (ok && MENSAJES[page] && MENSAJES[page][ok]) {
        texto = MENSAJES[page][ok];
        tipo = 'success';
    } else if (error && MENSAJES[page] && MENSAJES[page][error]) {
        texto = MENSAJES[page][error];
        tipo = 'error';
    } else if (msg && MENSAJES_GENERALES[msg]) {
        texto = MENSAJES_GENERALES[msg].texto;
        tipo = MENSAJES_GENERALES[msg].tipo;
    }

    if (!texto) return;

    box.textContent = texto;
    box.className = 'alert show ' + (tipo === 'success' ? 'success' : 'error');

    // Restaura los valores enviados por el formulario (si existen).
    ['dni', 'telp', 'correo', 'nomp', 'apep', 'usuario', 'seguro', 'cmpm'].forEach((nombre) => {
        const valor = params.get(nombre);
        const campo = document.querySelector('[name="' + nombre + '"]');
        if (valor !== null && campo && !campo.value) {
            campo.value = valor;
            if (campo.tagName === 'SELECT') {
                campo.dispatchEvent(new Event('change', { bubbles: true }));
            }
        }
    });
}

/**
 * Aplica restricciones numéricas a los campos marcados con data-numerico.
 * Muestra avisos cuando se escriben letras o se supera el máximo.
 */
function configurarCamposNumericos() {
    const campos = document.querySelectorAll('[data-numerico="true"]');

    campos.forEach((campo) => {
        let ayuda = campo.parentElement.querySelector('.field-inline-help');
        if (!ayuda) {
            ayuda = document.createElement('small');
            ayuda.className = 'field-inline-help';
            const ref = campo.closest('.input-with-button') || campo;
            ref.insertAdjacentElement('afterend', ayuda);
        }

        let temporizador;
        const mostrar = (texto, esError = true) => {
            ayuda.textContent = texto;
            ayuda.className = 'field-inline-help show ' + (esError ? 'error' : '');
            campo.classList.toggle('input-error', esError);
            clearTimeout(temporizador);
            temporizador = setTimeout(() => {
                ayuda.className = 'field-inline-help';
                campo.classList.remove('input-error');
            }, 2800);
        };

        campo.addEventListener('input', () => {
            const original = campo.value;
            const soloNumeros = original.replace(/\D/g, '');
            const max = parseInt(campo.dataset.max || '0', 10);

            if (original !== soloNumeros) {
                mostrar('Solo se permiten números en este campo.');
            } else if (max > 0 && soloNumeros.length > max) {
                mostrar('Máximo ' + max + ' dígitos.');
            } else {
                ayuda.className = 'field-inline-help';
                campo.classList.remove('input-error');
            }

            campo.value = max > 0 ? soloNumeros.slice(0, max) : soloNumeros;
        });

        campo.addEventListener('blur', () => {
            const exacto = parseInt(campo.dataset.exacto || '0', 10);
            if (exacto > 0 && campo.value && campo.value.length !== exacto) {
                const etiqueta = campo.dataset.label || 'Este campo';
                mostrar(etiqueta + ' debe tener exactamente ' + exacto + ' dígitos.');
            }
        });
    });
}

/**
 * Restringe los campos marcados con data-texto="true" a letras y signos
 * propios de un nombre (espacios, apóstrofo, guion y punto). Impide números.
 */
function configurarCamposTexto() {
    const campos = document.querySelectorAll('[data-texto="true"]');
    const noPermitidos = /[^A-Za-zÁÉÍÓÚÜÑáéíóúüñ\s'’\-.,\/()]/g;

    campos.forEach((campo) => {
        let ayuda = campo.parentElement.querySelector('.field-inline-help');
        if (!ayuda) {
            ayuda = document.createElement('small');
            ayuda.className = 'field-inline-help';
            campo.insertAdjacentElement('afterend', ayuda);
        }

        let temporizador;
        const avisar = (texto) => {
            ayuda.textContent = texto;
            ayuda.className = 'field-inline-help show error';
            campo.classList.add('input-error');
            clearTimeout(temporizador);
            temporizador = setTimeout(() => {
                ayuda.className = 'field-inline-help';
                campo.classList.remove('input-error');
            }, 2800);
        };

        campo.addEventListener('input', () => {
            const original = campo.value;
            const limpio = original.replace(noPermitidos, '').replace(/\s{2,}/g, ' ');
            if (limpio !== original) {
                avisar('Solo se permiten letras en este campo.');
                campo.value = limpio;
            }
        });
    });
}

/**
 * Consulta el DNI en SUNAT mediante consulta_dni.php y completa
 * automáticamente los nombres y apellidos.
 * Se ejecuta sola al llegar a 8 dígitos o al pulsar "Buscar DNI".
 */
let dniConsultado = '';
let consultaDniEnCurso = false;
let temporizadorDni = null;

async function buscarDni(forzar = false) {
    const inputDni = document.getElementById('dni');
    const estado = document.getElementById('dniEstado');
    const inputNombres = document.getElementById('nomp');
    const inputApellidos = document.getElementById('apep');
    const boton = document.getElementById('btnBuscarDni');

    if (!inputDni || !estado) return;

    const dni = inputDni.value.trim();

    if (!/^\d{8}$/.test(dni)) {
        if (forzar) {
            estado.className = 'field-help error';
            estado.textContent = 'Ingresa un DNI de 8 dígitos antes de buscar.';
            inputDni.focus();
        }
        return;
    }

    if (consultaDniEnCurso) return;
    if (!forzar && dni === dniConsultado) return;

    consultaDniEnCurso = true;
    estado.className = 'field-help';
    estado.textContent = 'Consultando DNI...';
    if (boton) {
        boton.disabled = true;
        boton.textContent = 'Buscando...';
    }

    try {
        const url = (window.RUTA_API_DNI || 'consulta_dni.php') + '?dni=' + encodeURIComponent(dni);
        const respuesta = await fetch(url, { headers: { 'Accept': 'application/json' } });

        if (!respuesta.ok) {
            throw new Error('Respuesta ' + respuesta.status);
        }

        const datos = await respuesta.json();

        if (datos.ok) {
            if (inputNombres) {
                inputNombres.value = (datos.nombres || '').slice(0, 40);
                inputNombres.readOnly = true;
            }
            if (inputApellidos) {
                inputApellidos.value = (datos.apellidos || '').slice(0, 40);
                inputApellidos.readOnly = true;
            }
            estado.className = 'field-help success';
            estado.textContent = 'DNI encontrado: ' + (datos.nombres || '') + ' ' + (datos.apellidos || '');
            dniConsultado = dni;
        } else {
            if (inputNombres) inputNombres.readOnly = false;
            if (inputApellidos) inputApellidos.readOnly = false;
            estado.className = 'field-help error';
            estado.textContent = (datos.mensaje || 'No encontramos datos para ese DNI.') + ' Puedes escribir tus nombres y apellidos manualmente.';
            dniConsultado = '';
        }
    } catch (error) {
        dniConsultado = '';
        const inputNombres = document.getElementById('nomp');
        const inputApellidos = document.getElementById('apep');
        if (inputNombres) inputNombres.readOnly = false;
        if (inputApellidos) inputApellidos.readOnly = false;
        estado.className = 'field-help error';
        estado.textContent = 'No se pudo consultar el DNI. Revisa tu conexión o escribe tus nombres y apellidos manualmente.';
    } finally {
        consultaDniEnCurso = false;
        if (boton) {
            boton.disabled = false;
            boton.textContent = 'Buscar DNI';
        }
    }
}

/**
 * Lanza la consulta automática al escribir los 8 dígitos del DNI.
 */
function configurarAutoConsultaDni() {
    const inputDni = document.getElementById('dni');
    const estado = document.getElementById('dniEstado');

    if (!inputDni) return;

    if (estado) {
        estado.className = 'field-help';
        estado.textContent = 'Escribe tus 8 números y buscaremos tus nombres y apellidos automáticamente. También puedes usar el botón «Buscar DNI».';
    }

    const limpiarNombres = () => {
        const n = document.getElementById('nomp');
        const a = document.getElementById('apep');
        if (n) { n.value = ''; n.readOnly = true; }
        if (a) { a.value = ''; a.readOnly = true; }
    };

    const intentar = (forzar = false, demora = 350) => {
        clearTimeout(temporizadorDni);
        const dni = inputDni.value.trim();
        if (dni.length < 8) {
            if (dniConsultado !== '' || dni.length < 8) {
                dniConsultado = '';
                limpiarNombres();
            }
            return;
        }
        if (/^\d{8}$/.test(dni)) {
            if (dni !== dniConsultado) {
                limpiarNombres();
            }
            if (forzar || dni !== dniConsultado) {
                temporizadorDni = setTimeout(() => buscarDni(forzar), demora);
            }
        }
    };

    inputDni.addEventListener('input', () => intentar(false, 350));
    inputDni.addEventListener('change', () => intentar(false, 0));
    inputDni.addEventListener('paste', () => setTimeout(() => intentar(false, 100), 0));

    inputDni.addEventListener('blur', () => {
        clearTimeout(temporizadorDni);
        const dni = inputDni.value.trim();
        if (/^\d{8}$/.test(dni) && dni !== dniConsultado) {
            buscarDni();
        }
    });

    // Si el campo ya tiene 8 dígitos (autocompletado, recarga, etc.)
    // Cuando el formulario vuelve con datos restaurados (por ejemplo tras un
    // error de teléfono), se conservan los nombres ya obtenidos y no se
    // vuelve a consultar el DNI para no borrarlos si la consulta falla.
    if (/^\d{8}$/.test(inputDni.value.trim())) {
        const nomActual = (document.getElementById('nomp')?.value || '').trim();
        const apeActual = (document.getElementById('apep')?.value || '').trim();
        if (nomActual !== '' && apeActual !== '') {
            dniConsultado = inputDni.value.trim();
        } else {
            setTimeout(() => intentar(true, 0), 200);
        }
    }

    // En registro, los nombres se obtienen por DNI: bloquear envío si faltan.
    const form = inputDni.form;
    if (form && (document.getElementById('nomp')?.hasAttribute('readonly') || document.getElementById('apep')?.hasAttribute('readonly'))) {
        form.addEventListener('submit', (e) => {
            const nom = (document.getElementById('nomp')?.value || '').trim();
            const ape = (document.getElementById('apep')?.value || '').trim();
            const dni = inputDni.value.trim();
            if (!nom || !ape) {
                e.preventDefault();
                estado.className = 'field-help error';
                estado.textContent = /^\d{8}$/.test(dni)
                    ? 'Escribe tu DNI de 8 números y espera a que aparezcan tus nombres. Si no aparecen, escríbelos manualmente.'
                    : 'Escribe tu DNI de 8 números para buscar tus nombres.';
                inputDni.focus();
            }
        });
    }
}

/**
 * Reemplaza los <select> nativos por un desplegable con el diseño del sistema.
 * El <select> original se conserva oculto para que el formulario siga enviando
 * su valor y para que funcionen los onchange existentes.
 */
let idOpcionSelect = 0;

function mejorarSelects() {
    document.querySelectorAll('select').forEach((select) => {
        if (select.dataset.mejorado || select.multiple || select.size > 1) return;
        select.dataset.mejorado = '1';

        const eraRequerido = select.required;
        if (eraRequerido) {
            select.dataset.requerido = '1';
            select.required = false;
        }

        const contenedor = document.createElement('div');
        contenedor.className = 'select-custom';
        select.parentNode.insertBefore(contenedor, select);
        contenedor.appendChild(select);
        select.classList.add('select-native');

        const boton = document.createElement('button');
        boton.type = 'button';
        boton.className = 'select-trigger';
        boton.setAttribute('aria-haspopup', 'listbox');
        boton.setAttribute('aria-expanded', 'false');
        boton.innerHTML = '<span class="select-value"></span>' +
            '<svg class="select-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" ' +
            'stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
            '<path d="m6 9 6 6 6-6"/></svg>';

        const menu = document.createElement('ul');
        menu.className = 'select-menu';
        menu.setAttribute('role', 'listbox');

        contenedor.appendChild(boton);
        contenedor.appendChild(menu);

        const valor = boton.querySelector('.select-value');
        let indiceActivo = -1;

        const actualizarBoton = () => {
            const opcion = select.options[select.selectedIndex];
            valor.textContent = opcion ? opcion.textContent.trim() : '';
            boton.classList.toggle('placeholder', select.value === '');
            boton.disabled = select.disabled;
        };

        const cerrar = () => {
            contenedor.classList.remove('open');
            boton.setAttribute('aria-expanded', 'false');
            boton.removeAttribute('aria-activedescendant');
            menu.innerHTML = '';
            indiceActivo = -1;
        };

        const marcarActivo = (indice) => {
            const opciones = Array.from(menu.children);
            if (!opciones.length) return;

            indiceActivo = Math.max(0, Math.min(indice, opciones.length - 1));
            opciones.forEach((li, i) => li.classList.toggle('activa', i === indiceActivo));

            const activa = opciones[indiceActivo];
            if (activa) {
                const arriba = activa.offsetTop;
                const abajo = arriba + activa.offsetHeight;
                if (arriba < menu.scrollTop) {
                    menu.scrollTop = arriba;
                } else if (abajo > menu.scrollTop + menu.clientHeight) {
                    menu.scrollTop = abajo - menu.clientHeight;
                }
                boton.setAttribute('aria-activedescendant', activa.id);
            }
        };

        const elegir = (indice) => {
            const opcion = select.options[indice];
            if (!opcion || opcion.disabled) return;

            select.selectedIndex = indice;
            actualizarBoton();
            select.dispatchEvent(new Event('change', { bubbles: true }));
            cerrar();
            boton.focus();
        };

        const construirMenu = () => {
            menu.innerHTML = '';

            Array.from(select.options).forEach((opcion, indice) => {
                const li = document.createElement('li');
                li.className = 'select-option';
                li.id = 'select-opcion-' + (++idOpcionSelect);
                li.setAttribute('role', 'option');
                li.textContent = opcion.textContent.trim();

                if (opcion.disabled) {
                    li.classList.add('disabled');
                    li.setAttribute('aria-disabled', 'true');
                }
                if (opcion.selected) {
                    li.classList.add('selected');
                    li.setAttribute('aria-selected', 'true');
                }

                li.addEventListener('click', () => elegir(indice));
                li.addEventListener('mouseenter', () => {
                    if (!opcion.disabled) marcarActivo(indice);
                });

                menu.appendChild(li);
            });
        };

        const abrir = () => {
            if (boton.disabled) return;

            document.querySelectorAll('.select-custom.open').forEach((otro) => {
                if (otro !== contenedor) {
                    otro.classList.remove('open');
                    const otroBoton = otro.querySelector('.select-trigger');
                    if (otroBoton) otroBoton.setAttribute('aria-expanded', 'false');
                    const otroMenu = otro.querySelector('.select-menu');
                    if (otroMenu) otroMenu.innerHTML = '';
                }
            });

            construirMenu();
            contenedor.classList.add('open');
            boton.setAttribute('aria-expanded', 'true');
            marcarActivo(select.selectedIndex >= 0 ? select.selectedIndex : 0);
        };

        boton.addEventListener('click', (evento) => {
            evento.stopPropagation();
            if (contenedor.classList.contains('open')) {
                cerrar();
            } else {
                abrir();
            }
        });

        boton.addEventListener('keydown', (evento) => {
            const abierto = contenedor.classList.contains('open');

            if (!abierto && ['ArrowDown', 'ArrowUp', 'Enter', ' '].includes(evento.key)) {
                evento.preventDefault();
                abrir();
                return;
            }

            if (!abierto) return;

            const opciones = Array.from(menu.children);

            if (evento.key === 'Escape') {
                evento.preventDefault();
                cerrar();
            } else if (evento.key === 'ArrowDown') {
                evento.preventDefault();
                marcarActivo(indiceActivo + 1);
            } else if (evento.key === 'ArrowUp') {
                evento.preventDefault();
                marcarActivo(indiceActivo - 1);
            } else if (evento.key === 'Home') {
                evento.preventDefault();
                marcarActivo(0);
            } else if (evento.key === 'End') {
                evento.preventDefault();
                marcarActivo(opciones.length - 1);
            } else if (evento.key === 'Enter' || evento.key === ' ') {
                evento.preventDefault();
                elegir(indiceActivo);
            } else if (evento.key === 'Tab') {
                cerrar();
            }
        });

        document.addEventListener('click', (evento) => {
            if (!contenedor.contains(evento.target)) cerrar();
        });

        select.addEventListener('change', actualizarBoton);

        // Validación para los selects que eran "required".
        const formulario = select.form;
        if (formulario && !formulario.dataset.validaSelects) {
            formulario.dataset.validaSelects = '1';
            formulario.addEventListener('submit', (evento) => {
                const faltantes = formulario.querySelectorAll('select[data-requerido="1"]');
                let invalido = null;

                faltantes.forEach((campo) => {
                    if (campo.value === '' && !invalido) invalido = campo;
                });

                if (invalido) {
                    evento.preventDefault();
                    const disparador = invalido.closest('.select-custom')?.querySelector('.select-trigger');
                    if (disparador) {
                        disparador.classList.add('input-error');
                        disparador.focus();
                    }
                }
            });
        }

        select.addEventListener('change', () => {
            const disparador = contenedor.querySelector('.select-trigger');
            if (disparador) disparador.classList.remove('input-error');
        });

        actualizarBoton();
    });
}

/* ============================================================
   ACCESIBILIDAD PARA ADULTOS MAYORES
   Letra más grande, alto contraste, foco visible y botón para
   mostrar la contraseña. Las preferencias se guardan en el navegador.
   ============================================================ */
const ACC_CLAVE = 'primelab_accesibilidad_v1';
const ACC_ESCALAS = ['normal', 'grande', 'muy-grande'];
const ACC_ETIQUETAS = {
    'normal': 'Letra normal',
    'grande': 'Letra grande',
    'muy-grande': 'Letra muy grande'
};

/** Lee las preferencias guardadas (si el navegador las permite). */
function accLeerPreferencias() {
    try {
        const datos = JSON.parse(localStorage.getItem(ACC_CLAVE) || '{}');
        return {
            escala: ACC_ESCALAS.includes(datos.escala) ? datos.escala : 'normal',
            contraste: datos.contraste === true
        };
    } catch (error) {
        return { escala: 'normal', contraste: false };
    }
}

/** Guarda las preferencias en el navegador. */
function accGuardarPreferencias(preferencias) {
    try {
        localStorage.setItem(ACC_CLAVE, JSON.stringify(preferencias));
    } catch (error) {
        /* Si el navegador bloquea el almacenamiento, se ignora. */
    }
}

/** Aplica el tamaño de letra y el contraste al documento. */
function accAplicarPreferencias(preferencias) {
    const body = document.body;
    if (!body) return;
    body.classList.remove('texto-grande', 'texto-muy-grande');
    if (preferencias.escala === 'grande') body.classList.add('texto-grande');
    if (preferencias.escala === 'muy-grande') body.classList.add('texto-muy-grande');
    body.classList.toggle('alto-contraste', preferencias.contraste);
}

/** Crea la barra flotante de accesibilidad en todas las páginas. */
function configurarAccesibilidad() {
    if (!document.body || document.querySelector('.barra-accesibilidad')) return;

    let preferencias = accLeerPreferencias();
    accAplicarPreferencias(preferencias);

    const barra = document.createElement('div');
    barra.className = 'barra-accesibilidad';
    barra.innerHTML =
        '<div class="panel-accesibilidad" id="panelAccesibilidad" role="dialog" aria-label="Opciones de accesibilidad">' +
            '<p class="panel-accesibilidad-title">Accesibilidad</p>' +
            '<p class="panel-accesibilidad-help">Ajusta el tamaño de la letra y el contraste para leer con más comodidad.</p>' +
            '<div class="acc-fila">' +
                '<button type="button" class="acc-opcion" data-accion="menos" aria-label="Reducir el tamaño de la letra">A−</button>' +
                '<span class="acc-estado" id="accEstado" aria-live="polite">Letra normal</span>' +
                '<button type="button" class="acc-opcion" data-accion="mas" aria-label="Aumentar el tamaño de la letra">A+</button>' +
            '</div>' +
            '<button type="button" class="acc-opcion acc-ancho" data-accion="contraste" aria-pressed="false">Alto contraste</button>' +
            '<button type="button" class="acc-opcion acc-ancho" data-accion="reiniciar">Restablecer</button>' +
        '</div>' +
        '<button type="button" class="boton-accesibilidad" id="btnAccesibilidad" aria-expanded="false" aria-controls="panelAccesibilidad">' +
            '<span aria-hidden="true">♿</span> Accesibilidad' +
        '</button>';
    document.body.appendChild(barra);

    const panel = barra.querySelector('#panelAccesibilidad');
    const boton = barra.querySelector('#btnAccesibilidad');
    const estado = barra.querySelector('#accEstado');
    const botonContraste = barra.querySelector('[data-accion="contraste"]');

    const refrescar = () => {
        accAplicarPreferencias(preferencias);
        accGuardarPreferencias(preferencias);
        if (estado) estado.textContent = ACC_ETIQUETAS[preferencias.escala];
        if (botonContraste) {
            botonContraste.setAttribute('aria-pressed', String(preferencias.contraste));
            botonContraste.classList.toggle('activo', preferencias.contraste);
        }
    };

    const cerrarPanel = () => {
        panel.classList.remove('abierto');
        boton.setAttribute('aria-expanded', 'false');
    };

    boton.addEventListener('click', () => {
        const abierto = panel.classList.toggle('abierto');
        boton.setAttribute('aria-expanded', String(abierto));
    });

    barra.addEventListener('click', (evento) => {
        const opcion = evento.target.closest('[data-accion]');
        if (!opcion) return;
        const indice = ACC_ESCALAS.indexOf(preferencias.escala);

        if (opcion.dataset.accion === 'mas') {
            preferencias.escala = ACC_ESCALAS[Math.min(indice + 1, ACC_ESCALAS.length - 1)];
        } else if (opcion.dataset.accion === 'menos') {
            preferencias.escala = ACC_ESCALAS[Math.max(indice - 1, 0)];
        } else if (opcion.dataset.accion === 'contraste') {
            preferencias.contraste = !preferencias.contraste;
        } else if (opcion.dataset.accion === 'reiniciar') {
            preferencias = { escala: 'normal', contraste: false };
        }
        refrescar();
    });

    document.addEventListener('click', (evento) => {
        if (!barra.contains(evento.target)) cerrarPanel();
    });

    document.addEventListener('keydown', (evento) => {
        if (evento.key === 'Escape' && panel.classList.contains('abierto')) {
            cerrarPanel();
            boton.focus();
        }
    });

    refrescar();
}

/** Agrega un botón «Mostrar / Ocultar» a los campos de contraseña. */
function configurarVerContrasena() {
    document.querySelectorAll('input[type="password"]').forEach((input) => {
        if (input.dataset.verClave === '1') return;
        input.dataset.verClave = '1';

        const contenedor = document.createElement('div');
        contenedor.className = 'campo-clave';
        input.parentNode.insertBefore(contenedor, input);
        contenedor.appendChild(input);

        const boton = document.createElement('button');
        boton.type = 'button';
        boton.className = 'boton-ver-clave';
        boton.textContent = 'Mostrar';
        boton.setAttribute('aria-pressed', 'false');
        boton.setAttribute('aria-label', 'Mostrar u ocultar la contraseña');
        contenedor.appendChild(boton);

        boton.addEventListener('click', () => {
            const estabaOculta = input.type === 'password';
            input.type = estabaOculta ? 'text' : 'password';
            boton.textContent = estabaOculta ? 'Ocultar' : 'Mostrar';
            boton.setAttribute('aria-pressed', String(estabaOculta));
        });
    });
}

/** Enlace «Saltar al contenido» para navegación con teclado. */
function configurarSaltarContenido() {
    const destino = document.querySelector('main') || document.querySelector('.content');
    if (!destino) return;
    if (!destino.id) destino.id = 'contenido-principal';

    const enlace = document.createElement('a');
    enlace.className = 'saltar-contenido';
    enlace.href = '#' + destino.id;
    enlace.textContent = 'Saltar al contenido principal';
    document.body.insertBefore(enlace, document.body.firstChild);
}

document.addEventListener('DOMContentLoaded', () => {
    configurarEntorno();
    mostrarMensajeUrl();
    configurarCamposNumericos();
    configurarCamposTexto();
    configurarAutoConsultaDni();
    mejorarSelects();
    configurarAccesibilidad();
    configurarVerContrasena();
    configurarSaltarContenido();
});
