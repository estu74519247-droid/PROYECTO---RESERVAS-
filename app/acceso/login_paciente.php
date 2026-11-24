<?php
// login_paciente.php — Ingreso del paciente con DNI y contraseña.
// La contraseña inicial de cada cuenta es su propio DNI.
// También se acepta la contraseña registrada en la API interna (usuarios.json).
session_start();
require_once __DIR__ . '/../core/file_conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir("index.html");
}

$dni = trim($_POST['dni'] ?? '');
$contrasena = trim($_POST['contrasena'] ?? '');

if (!preg_match('/^\d{8}$/', $dni)) {
    redirigir("index.html?error=dni&dni=" . urlencode($dni));
}
if ($contrasena === '') {
    redirigir("index.html?error=pass&dni=" . urlencode($dni));
}

$mysqli = Conectarse();
$stmt = $mysqli->prepare("SELECT codp, nomp, apep, dni, contrasena FROM paciente WHERE dni = ? LIMIT 1");
$stmt->bind_param("s", $dni);
$stmt->execute();
$paciente = $stmt->get_result()->fetch_assoc();
$stmt->close();

// El DNI puede existir en el directorio local de usuarios o en la API interna
// y todavía no en MySQL: se crea la cuenta local automáticamente.
$usuarioDirectorio = usuarioPorDni($dni);
if (!$paciente && $usuarioDirectorio) {
    $paciente = crearPacienteDesdeApi($mysqli, $usuarioDirectorio);
}

if (!$paciente) {
    $mysqli->close();
    redirigir("index.html?error=noexiste&dni=" . urlencode($dni));
}

$guardada = (string)($paciente['contrasena'] ?? '');
if ($guardada === '') {
    // Compatibilidad con registros creados antes de agregar la contraseña.
    $guardada = (string)$paciente['dni'];
    $stmt = $mysqli->prepare("UPDATE paciente SET contrasena = ? WHERE codp = ?");
    $stmt->bind_param("ss", $guardada, $paciente['codp']);
    $stmt->execute();
    $stmt->close();
}

$claveApi = (string)($usuarioDirectorio['contrasena'] ?? '');
$claveOk = hash_equals($guardada, $contrasena)
        || hash_equals((string)$paciente['dni'], $contrasena)
        || ($claveApi !== '' && hash_equals($claveApi, $contrasena));

if (!$claveOk) {
    $mysqli->close();
    redirigir("index.html?error=pass&dni=" . urlencode($dni));
}

session_regenerate_id(true);
$_SESSION['paciente_codp']   = $paciente['codp'];
$_SESSION['paciente_nombre'] = $paciente['nomp'] . ' ' . $paciente['apep'];
$_SESSION['paciente_dni']    = $paciente['dni'];

$mysqli->close();
flash('exito', 'Bienvenido, ' . $paciente['nomp'] . ' ' . $paciente['apep'] . '.');
redirigir("panel.php");
