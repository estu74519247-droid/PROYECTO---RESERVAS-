<?php
// file_conexion.php — Conexión y utilidades comunes (estilo ByteCore/procedural)

require_once __DIR__ . '/config.php';

// Estilo procedural: las funciones mysqli devuelven false en lugar de lanzar
// excepciones, lo que permite crear la base de datos o mostrar un mensaje claro.
if (function_exists('mysqli_report')) {
    mysqli_report(MYSQLI_REPORT_OFF);
}

if (session_status() === PHP_SESSION_NONE && !defined('PRIMELAB_SIN_SESION')) {
    session_start();
}

/** Ejecuta una consulta ignorando errores (para migraciones automáticas). */
function primelab_query($mysqli, $sql)
{
    try {
        return $mysqli->query($sql);
    } catch (\Throwable $e) {
        return false;
    }
}

/** Indica si una tabla tiene una columna determinada. */
function primelab_tiene_columna($mysqli, $tabla, $columna)
{
    $tabla = preg_replace('/[^A-Za-z0-9_]/', '', $tabla);
    $res = primelab_query($mysqli, "SHOW COLUMNS FROM `$tabla` LIKE '" . $mysqli->real_escape_string($columna) . "'");
    return ($res && $res->num_rows > 0);
}

/**
 * Abre la conexión con MySQL. Si la base de datos todavía no existe la crea,
 * de modo que el sistema arranca en cualquier instalación de XAMPP.
 */
function primelab_abrir_conexion($detener = true)
{
    if (!function_exists('mysqli_init')) {
        if ($detener) die("La extensión mysqli de PHP no está habilitada. Actívala en XAMPP.");
        return null;
    }

    $mysqli = mysqli_init();
    if (!$mysqli) {
        if ($detener) die("No se pudo inicializar la conexión a MySQL.");
        return null;
    }
    @$mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 4);
    @$mysqli->real_connect(
        PRIMELAB_DB_HOST,
        PRIMELAB_DB_USER,
        PRIMELAB_DB_PASS,
        PRIMELAB_DB_NAME,
        (int) PRIMELAB_DB_PORT
    );

    // Error 1049 = base de datos inexistente: la creamos automáticamente.
    if ($mysqli->connect_errno === 1049) {
        $creador = @mysqli_init();
        if ($creador) {
            @$creador->options(MYSQLI_OPT_CONNECT_TIMEOUT, 4);
            @$creador->real_connect(PRIMELAB_DB_HOST, PRIMELAB_DB_USER, PRIMELAB_DB_PASS, '', (int) PRIMELAB_DB_PORT);
            if (!$creador->connect_errno) {
                @$creador->query("CREATE DATABASE IF NOT EXISTS `" . PRIMELAB_DB_NAME . "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci");
            }
            @$creador->close();
            @$mysqli->close();
            $mysqli = mysqli_init();
            @$mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 4);
            @$mysqli->real_connect(PRIMELAB_DB_HOST, PRIMELAB_DB_USER, PRIMELAB_DB_PASS, PRIMELAB_DB_NAME, (int) PRIMELAB_DB_PORT);
        }
    }

    if ($mysqli->connect_errno) {
        if ($detener) {
            die("Error de conexión a MySQL (" . PRIMELAB_DB_HOST . ":" . PRIMELAB_DB_PORT . "): " . $mysqli->connect_error
                . "<br>Verifica que MySQL esté iniciado en XAMPP y que el puerto sea el correcto.");
        }
        return null;
    }

    $mysqli->set_charset("utf8mb4");
    return $mysqli;
}

/**
 * Abre una conexión con MySQL usando la configuración de config.php.
 * Devuelve null si el servidor no está disponible (sin detener la página).
 */
function ConectarseOpcional()
{
    $mysqli = primelab_abrir_conexion(false);
    if (!$mysqli) return null;
    asegurarEsquemaPaciente($mysqli);
    asegurarEsquemaCita($mysqli);
    return $mysqli;
}

function Conectarse()
{
    $mysqli = primelab_abrir_conexion(true);
    asegurarBaseInicial($mysqli);
    asegurarEsquemaPaciente($mysqli);
    asegurarEsquemaCita($mysqli);
    asegurarAdministradores($mysqli);
    return $mysqli;
}

/**
 * Si la base de datos está vacía, importa el script BD/bd_primelab.sql.
 * Así el sistema queda operativo en cualquier XAMPP recién instalado.
 */
function asegurarBaseInicial($mysqli)
{
    static $verificado = false;
    if ($verificado) return;
    $verificado = true;

    $res = primelab_query($mysqli, "SHOW TABLES LIKE 'paciente'");
    if ($res && $res->num_rows > 0) return;

    $archivo = dirname(__DIR__, 2) . '/BD/bd_primelab.sql';
    if (!is_file($archivo)) return;
    $sql = @file_get_contents($archivo);
    if (!is_string($sql) || trim($sql) === '') return;

    // Se elimina CREATE DATABASE / USE para importar sobre la base ya conectada.
    $sql = preg_replace('/^\s*CREATE DATABASE[^;]*;/mi', '', $sql);
    $sql = preg_replace('/^\s*USE\s+`[^`]+`\s*;/mi', '', $sql);

    if (@$mysqli->multi_query($sql)) {
        do {
            if ($res = @$mysqli->store_result()) {
                $res->free();
            }
        } while (@$mysqli->more_results() && @$mysqli->next_result());
    }
}

/**
 * Ajustes idempotentes para bases de datos creadas con la versión anterior.
 * - Agrega la columna `contrasena` si no existe.
 * - Permite teléfono nulo para cuentas importadas desde la API.
 * - Asigna como contraseña inicial el DNI de cada paciente.
 */
function asegurarEsquemaPaciente($mysqli)
{
    static $verificado = false;
    if ($verificado) return;
    $verificado = true;

    $agregada = false;
    if (!primelab_tiene_columna($mysqli, 'paciente', 'contrasena')) {
        if (primelab_query($mysqli, "ALTER TABLE paciente ADD COLUMN contrasena VARCHAR(255) DEFAULT NULL AFTER correo")) {
            $agregada = true;
        }
    }

    $colTel = primelab_query($mysqli, "SHOW COLUMNS FROM paciente LIKE 'telp'");
    if ($colTel && ($infoTel = $colTel->fetch_assoc()) && strtoupper($infoTel['Null']) === 'NO') {
        primelab_query($mysqli, "ALTER TABLE paciente MODIFY telp CHAR(9) NULL DEFAULT NULL");
    }

    if ($agregada) {
        primelab_query($mysqli, "UPDATE paciente SET contrasena = dni WHERE contrasena IS NULL OR contrasena = ''");
    } else {
        $sinClave = primelab_query($mysqli, "SELECT 1 FROM paciente WHERE contrasena IS NULL OR contrasena = '' LIMIT 1");
        if ($sinClave && $sinClave->num_rows > 0) {
            primelab_query($mysqli, "UPDATE paciente SET contrasena = dni WHERE contrasena IS NULL OR contrasena = ''");
        }
    }
}

primelab_query(
    $mysqli,
    "ALTER TABLE paciente MODIFY contrasena VARCHAR(255) DEFAULT NULL"
);
/**
 * Auto-reparación del esquema de `cita` y `cita_historial`.
 * Evita el error fatal "Unknown column 'c.diagnostico'" cuando la base de
 * datos fue importada con una versión anterior del sistema.
 */
function asegurarEsquemaCita($mysqli)
{
    static $verificado = false;
    if ($verificado) return;
    $verificado = true;

    if (!primelab_tiene_columna($mysqli, 'cita', 'diagnostico')) {
        primelab_query($mysqli, "ALTER TABLE cita ADD COLUMN diagnostico TEXT DEFAULT NULL");
    }
    if (!primelab_tiene_columna($mysqli, 'cita', 'indicaciones')) {
        primelab_query($mysqli, "ALTER TABLE cita ADD COLUMN indicaciones TEXT DEFAULT NULL");
    }
    if (!primelab_tiene_columna($mysqli, 'cita', 'motivo_reprogramacion')) {
        primelab_query($mysqli, "ALTER TABLE cita ADD COLUMN motivo_reprogramacion VARCHAR(255) DEFAULT NULL");
    }
    if (!primelab_tiene_columna($mysqli, 'cita', 'reprogramada_de')) {
        primelab_query($mysqli, "ALTER TABLE cita ADD COLUMN reprogramada_de CHAR(6) DEFAULT NULL");
    }
    if (!primelab_tiene_columna($mysqli, 'cita', 'observaciones')) {
        primelab_query($mysqli, "ALTER TABLE cita ADD COLUMN observaciones VARCHAR(255) DEFAULT NULL");
    }
    // El estado ahora admite 'extendida' y 'reprogramada'.
    primelab_query($mysqli, "ALTER TABLE cita MODIFY estado VARCHAR(20) NOT NULL DEFAULT 'pendiente'");

    if (!primelab_tiene_columna($mysqli, 'cita_historial', 'usuario_accion')) {
        primelab_query($mysqli, "ALTER TABLE cita_historial ADD COLUMN usuario_accion VARCHAR(40) DEFAULT 'Sistema'");
    }
}

/**
 * Crea la tabla `administrador` y siembra tres cuentas de acceso si está vacía.
 * El administrador principal sigue siendo `prime`.
 */
function asegurarAdministradores($mysqli)
{
    static $verificado = false;
    if ($verificado) return;
    $verificado = true;

    $ok = primelab_query($mysqli, "
        CREATE TABLE IF NOT EXISTS `administrador` (
          `idadm` int(11) NOT NULL AUTO_INCREMENT,
          `usuario` varchar(30) NOT NULL,
          `correo` varchar(80) NOT NULL,
          `contrasena` varchar(255) NOT NULL,
          `nombre` varchar(80) DEFAULT NULL,
          `activo` tinyint(1) NOT NULL DEFAULT 1,
          PRIMARY KEY (`idadm`),
          UNIQUE KEY `uq_admin_usuario` (`usuario`),
          UNIQUE KEY `uq_admin_correo` (`correo`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci
    ");
    if (!$ok) return;

    $res = primelab_query($mysqli, "SELECT COUNT(*) AS n FROM administrador");
    $n = $res ? (int) $res->fetch_assoc()['n'] : 0;
    if ($n > 0) return;

    foreach (administradoresDefecto() as $a) {
        $stmt = @$mysqli->prepare("INSERT INTO administrador (usuario, correo, contrasena, nombre, activo) VALUES (?, ?, ?, ?, 1)");
        if (!$stmt) continue;
        $stmt->bind_param("ssss", $a['usuario'], $a['correo'], $a['contrasena'], $a['nombre']);
        @$stmt->execute();
        $stmt->close();
    }
}
primelab_query(
    $mysqli,
    "ALTER TABLE administrador MODIFY contrasena VARCHAR(255) NOT NULL"
);
/** Lista de administradores de ejemplo (mínimo 3, solicitado para la demo). */

function administradoresDefecto()
{
    return [
        [
            'usuario' => 'prime',
            'correo' => 'primelab@centrosalud.pe',
            'contrasena' => password_hash('CLAVE_NUEVA_PRIME', PASSWORD_DEFAULT),
            'nombre' => 'Administrador principal'
        ],
        [
            'usuario' => 'admin2',
            'correo' => 'gestion@centrosalud.pe',
            'contrasena' => password_hash('CLAVE_NUEVA_ADMIN2', PASSWORD_DEFAULT),
            'nombre' => 'Coordinación de citas'
        ],
        [
            'usuario' => 'admin3',
            'correo' => 'soporte@centrosalud.pe',
            'contrasena' => password_hash('CLAVE_NUEVA_ADMIN3', PASSWORD_DEFAULT),
            'nombre' => 'Soporte técnico'
        ]
    ];
}


/** Devuelve los administradores activos desde la base de datos. */
function administradoresActivos($mysqli)
{
    $lista = [];
    $res = primelab_query($mysqli, "SELECT usuario, correo, contrasena, nombre FROM administrador WHERE activo = 1");
    if ($res) {
        while ($fila = $res->fetch_assoc()) {
            $lista[] = $fila;
        }
    }
    return $lista;
}

/** Busca un administrador por usuario + correo (sin distinguir mayúsculas). */

function buscarAdministrador($mysqli, $usuario, $correo)
{
    $usuario = mb_strtolower(trim((string)$usuario), 'UTF-8');
    $correo = mb_strtolower(trim((string)$correo), 'UTF-8');

    foreach (administradoresActivos($mysqli) as $a) {
        if (
            mb_strtolower($a['usuario'], 'UTF-8') === $usuario &&
            mb_strtolower($a['correo'], 'UTF-8') === $correo
        ) {
            return $a;
        }
    }

    return null;
}


/**
 * Carga la copia local de `usuarios.json` incluida en el proyecto.
 * Así la búsqueda por DNI funciona incluso si la API Node.js está apagada.
 */
function usuariosLocales()
{
    static $usuarios = null;
    if (is_array($usuarios)) return $usuarios;
    $usuarios = [];

    $rutas = [
        dirname(__DIR__, 2) . '/datos/usuarios.json',
        dirname(__DIR__, 2) . '/API_Citas_Medicas/data/usuarios.json',
    ];
    foreach ($rutas as $ruta) {
        if (!is_file($ruta)) continue;
        $json = json_decode((string) @file_get_contents($ruta), true);
        if (isset($json['usuarios']) && is_array($json['usuarios'])) {
            $usuarios = $json['usuarios'];
            break;
        }
    }
    return $usuarios;
}

/** Busca un usuario en la copia local de usuarios.json por DNI. */
function usuarioLocalPorDni($dni)
{
    $dni = trim((string) $dni);
    if (!preg_match('/^\d{8}$/', $dni)) return null;
    foreach (usuariosLocales() as $u) {
        if ((string) ($u['dni'] ?? '') === $dni) {
            return $u;
        }
    }
    return null;
}

/**
 * Devuelve el usuario (local o de la API interna) que corresponde al DNI.
 */
function usuarioPorDni($dni)
{
    $local = usuarioLocalPorDni($dni);
    if ($local) return $local;
    return apiUsuarioPorDni($dni);
}

/** Convierte un texto a formato título: primera letra de cada palabra en mayúscula. */
function tituloPersona($texto)
{
    $texto = trim(preg_replace('/\s+/', ' ', (string)$texto));
    if ($texto === '') return '';
    return mb_convert_case(mb_strtolower($texto, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
}

/**
 * Busca un usuario por DNI en la API interna de usuarios.
 * Devuelve null si la API no está disponible o el DNI no existe.
 */
function apiUsuarioPorDni($dni)
{
    static $cache = [];
    $dni = trim((string)$dni);
    if (!preg_match('/^\d{8}$/', $dni)) return null;
    if (array_key_exists($dni, $cache)) return $cache[$dni];

    $url = PRIMELAB_API_URL . '/api/usuarios/buscar?dni=' . urlencode($dni);
    $body = null;

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 2,
            CURLOPT_TIMEOUT        => 4,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER     => ['Accept: application/json'],
        ]);
        $resp = curl_exec($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($resp !== false && $code >= 200 && $code < 300) {
            $body = $resp;
        }
    }

    if ($body === null && ini_get('allow_url_fopen')) {
        $ctx = stream_context_create([
            'http' => [
                'method'        => 'GET',
                'timeout'       => 3,
                'header'        => "Accept: application/json\r\n",
                'ignore_errors' => true,
            ],
        ]);
        $resp = @file_get_contents($url, false, $ctx);
        if (is_string($resp) && $resp !== '') $body = $resp;
    }

    $usuario = null;
    if (is_string($body) && $body !== '') {
        $json = json_decode($body, true);
        if (is_array($json)) {
            $u = $json['usuario'] ?? $json;
            if (is_array($u) && (string)($u['dni'] ?? '') === $dni) {
                $usuario = $u;
            }
        }
    }

    $cache[$dni] = $usuario;
    return $usuario;
}

/**
 * Busca nombres y apellidos por DNI en las dos fuentes del sistema:
 * primero en la base de datos de PrimeLab y luego en la API interna.
 * Devuelve null si el DNI no está registrado en ninguna.
 */
function buscarPersonaPorDni($dni)
{
    $dni = trim((string)$dni);
    if (!preg_match('/^\d{8}$/', $dni)) return null;

    // 1) Pacientes ya registrados en MySQL (incluye las 7 cuentas nuevas).
    $db = ConectarseOpcional();
    if ($db) {
        $stmt = @$db->prepare("SELECT nomp, apep, telp, correo FROM paciente WHERE dni = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("s", $dni);
            $stmt->execute();
            $fila = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            $db->close();
            if ($fila) {
                return [
                    'dni'       => $dni,
                    'nombres'   => tituloPersona($fila['nomp'] ?? ''),
                    'apellidos' => tituloPersona($fila['apep'] ?? ''),
                    'telefono'  => (string)($fila['telp'] ?? ''),
                    'correo'    => (string)($fila['correo'] ?? ''),
                    'fuente'    => 'Base de datos PrimeLab',
                ];
            }
        } else {
            $db->close();
        }
    }

    // 2) Copia local de usuarios.json incluida en el proyecto (sin depender de la API).
    $local = usuarioLocalPorDni($dni);
    if ($local) {
        return [
            'dni'       => $dni,
            'nombres'   => tituloPersona($local['nombres'] ?? ''),
            'apellidos' => tituloPersona($local['apellidos'] ?? ''),
            'telefono'  => (string)($local['telefono'] ?? ''),
            'correo'    => (string)($local['correo'] ?? ''),
            'fuente'    => 'Directorio local de usuarios',
        ];
    }

    // 3) Usuarios registrados en la API interna.
    $api = apiUsuarioPorDni($dni);
    if ($api) {
        return [
            'dni'       => $dni,
            'nombres'   => tituloPersona($api['nombres'] ?? ''),
            'apellidos' => tituloPersona($api['apellidos'] ?? ''),
            'telefono'  => (string)($api['telefono'] ?? ''),
            'correo'    => (string)($api['correo'] ?? ''),
            'fuente'    => 'API interna',
        ];
    }

    return null;
}

/**
 * Crea un paciente local a partir de un usuario de la API interna.
 * La contraseña inicial siempre es el DNI y el correo/teléfono son opcionales.
 */
function crearPacienteDesdeApi($mysqli, array $usuario)
{
    $dni = preg_replace('/\D/', '', (string)($usuario['dni'] ?? ''));
    if (!preg_match('/^\d{8}$/', $dni)) return null;

    $stmt = $mysqli->prepare("SELECT codp, nomp, apep, dni, telp, correo, seguro, fechanac, direccion, distrito, fechareg FROM paciente WHERE dni = ? LIMIT 1");
    $stmt->bind_param("s", $dni);
    $stmt->execute();
    $existente = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if ($existente) return $existente;

    $nomp = tituloPersona($usuario['nombres'] ?? '');
    $apep = tituloPersona($usuario['apellidos'] ?? '');
    if ($nomp === '' || $apep === '') return null;

    $telp = preg_replace('/\D/', '', (string)($usuario['telefono'] ?? ''));
    $telpDb = (strlen($telp) === 9) ? $telp : null;

    $correo = trim((string)($usuario['correo'] ?? ''));
    $correoDb = ($correo !== '' && filter_var($correo, FILTER_VALIDATE_EMAIL)) ? $correo : null;

    $codp = generarCodigo($mysqli, 'paciente', 'codp', 'PAC');
    $seguro = 'SIS';
    $fechareg = date('Y-m-d');
    $contrasena = $dni;

    $stmt = $mysqli->prepare("INSERT INTO paciente (codp, nomp, apep, dni, telp, correo, contrasena, seguro, fechareg) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $codp, $nomp, $apep, $dni, $telpDb, $correoDb, $contrasena, $seguro, $fechareg);
    $ok = $stmt->execute();
    $stmt->close();

    // Respaldo por si la base antigua aún exige teléfono obligatorio.
    if (!$ok && $telpDb === null) {
        $telpDb = '000000000';
        $stmt = $mysqli->prepare("INSERT INTO paciente (codp, nomp, apep, dni, telp, correo, contrasena, seguro, fechareg) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $codp, $nomp, $apep, $dni, $telpDb, $correoDb, $contrasena, $seguro, $fechareg);
        $ok = $stmt->execute();
        $stmt->close();
    }
    if (!$ok) return null;

    return [
        'codp' => $codp,
        'nomp' => $nomp,
        'apep' => $apep,
        'dni' => $dni,
        'telp' => $telpDb,
        'correo' => $correoDb,
        'seguro' => $seguro,
        'fechanac' => null,
        'direccion' => null,
        'distrito' => null,
        'fechareg' => $fechareg,
    ];
}

/** Escapa texto para mostrarlo en HTML. */
function e($texto)
{
    return htmlspecialchars((string)$texto, ENT_QUOTES, 'UTF-8');
}

/** Redirige y termina la ejecución. */
function redirigir($url)
{
    header("Location: " . $url);
    exit;
}

/** Guarda un mensaje flash en sesión. */
function flash($tipo, $texto)
{
    $_SESSION['flash'] = ['tipo' => $tipo, 'texto' => $texto];
}

/** Muestra y limpia el mensaje flash si existe. */
function mostrarFlash()
{
    if (empty($_SESSION['flash'])) {
        return;
    }
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    $clase = ($f['tipo'] === 'exito' || $f['tipo'] === 'success') ? 'success' : 'error';
    echo '<div class="alert show ' . $clase . '" role="alert">' . e($f['texto']) . '</div>';
}

/** Exige sesión de paciente; si no hay, redirige al inicio. */
function requerirPaciente()
{
    if (empty($_SESSION['paciente_codp'])) {
        redirigir("index.html?msg=sesion");
    }
}

/** Exige sesión de administrador. */
function requerirAdmin()
{
    if (empty($_SESSION['admin_logged'])) {
        redirigir("admin_login.html");
    }
}

/** Genera un código correlativo tipo PREF001, PREF002... */
function generarCodigo($mysqli, $tabla, $campo, $prefijo)
{
    $sql = "SELECT $campo AS cod FROM $tabla WHERE $campo LIKE ? ORDER BY $campo DESC LIMIT 1";
    $stmt = $mysqli->prepare($sql);
    $like = $prefijo . '%';
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $res = $stmt->get_result();
    $ultimo = $res->fetch_assoc();
    $stmt->close();

    $num = 0;
    if ($ultimo && preg_match('/(\d+)$/', $ultimo['cod'], $m)) {
        $num = (int)$m[1];
    }
    $num++;
    return $prefijo . str_pad((string)$num, 3, '0', STR_PAD_LEFT);
}

/** Formatea fecha YYYY-MM-DD a texto legible. */
function fechaTexto($fecha)
{
    if (!$fecha) return '';
    $ts = strtotime($fecha);
    if (!$ts) return $fecha;
    $meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
    $d = date('j', $ts);
    $m = (int)date('n', $ts);
    $y = date('Y', $ts);
    return $d . ' de ' . $meses[$m - 1] . ' de ' . $y;
}

/** Formatea hora HH:MM:SS a HH:MM. */
function horaTexto($hora)
{
    if (!$hora) return '';
    return substr($hora, 0, 5);
}

/** Devuelve el paciente actual desde sesión (o null). */
function pacienteActual($mysqli)
{
    if (empty($_SESSION['paciente_codp'])) return null;
    $stmt = $mysqli->prepare("SELECT codp, nomp, apep, dni, telp, correo, seguro, fechanac, direccion, distrito, fechareg FROM paciente WHERE codp = ? LIMIT 1");
    $stmt->bind_param("s", $_SESSION['paciente_codp']);
    $stmt->execute();
    $res = $stmt->get_result();
    $pac = $res->fetch_assoc();
    $stmt->close();
    return $pac ?: null;
}

/** Exige sesión de médico. */
function requerirMedico()
{
    if (empty($_SESSION['medico_codm'])) {
        redirigir("medico_login.html?msg=sesion");
    }
}

/** Devuelve el médico actual desde sesión (o null). */
function medicoActual($mysqli)
{
    if (empty($_SESSION['medico_codm'])) return null;
    $stmt = $mysqli->prepare("SELECT m.codm, m.nomm, m.cmpm, m.codesp, m.dni, m.telefono, m.correo, m.anios_experiencia, m.formacion, e.nomesp
                              FROM medico m JOIN especialidad e ON e.codesp = m.codesp
                              WHERE m.codm = ? LIMIT 1");
    $stmt->bind_param("s", $_SESSION['medico_codm']);
    $stmt->execute();
    $res = $stmt->get_result();
    $med = $res->fetch_assoc();
    $stmt->close();
    return $med ?: null;
}

/** Calcula el costo de la cita según el tipo de seguro. */
function calcularCosto($seguro)
{
    $s = strtoupper(trim((string)$seguro));
    if ($s === 'EPS') return 20.00;
    if ($s === 'PRIVADO') return 50.00;
    return 0.00; // SIS u otros
}

/** Devuelve texto legible del costo. */
function costoTexto($seguro, $costo = null)
{
    if ($costo === null) $costo = calcularCosto($seguro);
    $monto = number_format((float)$costo, 2);
    $s = strtoupper(trim((string)$seguro));
    if ((float)$costo <= 0) return 'S/ 0.00 (cubierto por ' . ($s ?: 'SIS') . ')';
    return 'S/ ' . $monto;
}

/** Nombre legible del seguro. */
function textoSeguro($seguro)
{
    $s = strtoupper(trim((string)$seguro));
    if ($s === 'EPS') return 'EPS';
    if ($s === 'PRIVADO') return 'Seguro privado';
    return 'SIS';
}

/**
 * Devuelve el HTML de la foto/avatar del médico.
 * Usa img/medicos/{codm}.svg si existe; si no, muestra un avatar con inicial.
 */
function fotoMedico($codm, $nomm = '', $tam = 44, $clase = 'medico-foto')
{
    $codm = preg_replace('/[^A-Za-z0-9_-]/', '', (string)$codm);
    $archivo = dirname(__DIR__, 2) . '/img/medicos/' . $codm . '.svg';
    $src = 'img/medicos/' . $codm . '.svg';
    $alt = $nomm !== '' ? $nomm : 'Médico';
    $style = 'width:' . (int)$tam . 'px;height:' . (int)$tam . 'px;';
    if ($codm !== '' && file_exists($archivo)) {
        return '<img src="' . e($src) . '" alt="' . e($alt) . '" class="' . e($clase) . '" style="' . $style . '">';
    }
    $inicial = $nomm !== '' ? mb_strtoupper(mb_substr(trim($nomm), 0, 1, 'UTF-8'), 'UTF-8') : 'M';
    return '<span class="' . e($clase) . ' medico-foto-fallback" style="' . $style . '" aria-label="' . e($alt) . '">' . e($inicial) . '</span>';
}
?>
