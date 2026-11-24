<?php
// login_medico.php — Ingreso del médico usando su CMP.
session_start();
require_once __DIR__ . '/../core/file_conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir("medico_login.html");
}

$cmpm = trim($_POST['cmpm'] ?? '');

if ($cmpm === '' || !preg_match('/^\d{4,10}$/', $cmpm)) {
    redirigir("medico_login.html?error=cmp&cmpm=" . urlencode($cmpm));
}

$mysqli = Conectarse();
$stmt = $mysqli->prepare("SELECT m.codm, m.nomm, m.cmpm, m.codesp, e.nomesp
                          FROM medico m JOIN especialidad e ON e.codesp = m.codesp
                          WHERE m.cmpm = ? LIMIT 1");
$stmt->bind_param("s", $cmpm);
$stmt->execute();
$res = $stmt->get_result();
$medico = $res->fetch_assoc();
$stmt->close();

if (!$medico) {
    $mysqli->close();
    redirigir("medico_login.html?error=noexiste&cmpm=" . urlencode($cmpm));
}

session_regenerate_id(true);
$_SESSION['medico_codm']   = $medico['codm'];
$_SESSION['medico_nombre'] = $medico['nomm'];
$_SESSION['medico_cmpm']   = $medico['cmpm'];

$mysqli->close();
flash('exito', 'Bienvenido(a), ' . $medico['nomm'] . '.');
redirigir("panel_medico.php");
?>
