<?php
// registro.php — Crea una cuenta de paciente.
// La contraseña inicial es el DNI; el correo es opcional.
session_start();
require_once __DIR__ . '/../core/file_conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir("registro.html");
}

$dni       = trim($_POST['dni'] ?? '');
$nomp      = tituloPersona($_POST['nomp'] ?? '');
$apep      = tituloPersona($_POST['apep'] ?? '');
$telp      = trim($_POST['telp'] ?? '');
$correo    = trim($_POST['correo'] ?? '');
$seguro    = trim($_POST['seguro'] ?? '');
$contrasena = $dni; // Regla del sistema: la contraseña es el DNI.

// Respaldo del autocompletado: si el JavaScript no pudo completar los nombres,
// se buscan por DNI en la base de datos y en la API interna antes de validar.
if (($nomp === '' || $apep === '') && preg_match('/^\d{8}$/', $dni)) {
    $persona = buscarPersonaPorDni($dni);
    if ($persona) {
        if ($nomp === '') $nomp = $persona['nombres'];
        if ($apep === '') $apep = $persona['apellidos'];
        if ($telp === '' && preg_match('/^\d{9}$/', (string)$persona['telefono'])) {
            $telp = $persona['telefono'];
        }
    }
}

$params = '&dni=' . urlencode($dni) . '&nomp=' . urlencode($nomp) . '&apep=' . urlencode($apep) . '&telp=' . urlencode($telp) . '&correo=' . urlencode($correo) . '&seguro=' . urlencode($seguro);

$segurosValidos = ['SIS', 'EPS', 'Privado'];
if ($dni === '' || $nomp === '' || $apep === '' || $telp === '' || $seguro === '') {
    redirigir("registro.html?error=campos" . $params);
}
// Los nombres y apellidos solo pueden contener letras y signos de nombre.
$patronNombre = "/^[\p{L}\s'’.,\/()\-]+$/u";
if (!preg_match($patronNombre, $nomp) || !preg_match($patronNombre, $apep)) {
    redirigir("registro.html?error=nombre" . $params);
}
if (!preg_match('/^\d{8}$/', $dni)) {
    redirigir("registro.html?error=dni" . $params);
}
if (!preg_match('/^\d{9}$/', $telp)) {
    redirigir("registro.html?error=tel" . $params);
}
if ($correo !== '' && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    redirigir("registro.html?error=correo" . $params);
}
if (!in_array($seguro, $segurosValidos, true)) {
    $seguro = 'SIS';
}

$mysqli = Conectarse();

// Verifica DNI duplicado
$stmt = $mysqli->prepare("SELECT codp FROM paciente WHERE dni = ? LIMIT 1");
$stmt->bind_param("s", $dni);
$stmt->execute();
$existe = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($existe) {
    $mysqli->close();
    redirigir("registro.html?error=existe" . $params);
}

$codp = generarCodigo($mysqli, 'paciente', 'codp', 'PAC');
$fechareg = date('Y-m-d');
$correoDb = ($correo === '') ? null : $correo;

$stmt = $mysqli->prepare("INSERT INTO paciente (codp, nomp, apep, dni, telp, correo, contrasena, seguro, fechareg) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssss", $codp, $nomp, $apep, $dni, $telp, $correoDb, $contrasena, $seguro, $fechareg);
$ok = $stmt->execute();
$stmt->close();

if (!$ok) {
    $mysqli->close();
    redirigir("registro.html?error=sistema" . $params);
}

session_regenerate_id(true);
$_SESSION['paciente_codp']   = $codp;
$_SESSION['paciente_nombre'] = $nomp . ' ' . $apep;
$_SESSION['paciente_dni']    = $dni;

$mysqli->close();
flash('exito', 'Tu perfil fue creado correctamente. ¡Bienvenido a PrimeLab!');
redirigir("panel.php");
?>
