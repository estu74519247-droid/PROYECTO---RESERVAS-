<?php
// login.php
// Procesa el formulario de acceso del administrador que está en admin_login.html
session_start();
require_once __DIR__ . '/../core/file_conexion.php';

if (isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true) {
    redirigir("admin_citas.php");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir("admin_login.html");
}

$usuario  = trim($_POST['usuario'] ?? '');
$correo   = trim($_POST['correo'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($usuario === '' || $correo === '' || $password === '') {
    redirigir("admin_login.html?error=campos");
}

// Se admiten varios administradores (tabla `administrador`), con respaldo
// en la lista interna. El administrador principal es `prime`.
$mysqli = ConectarseOpcional();
$admin  = $mysqli ? buscarAdministrador($mysqli, $usuario, $correo) : null;

$claveOk = false;
if ($admin) {
    $guardada = (string) ($admin['contrasena'] ?? '');
    if (strpos($guardada, '$2y$') === 0 || strpos($guardada, '$argon2') === 0) {
        $claveOk = password_verify($password, $guardada);
    } else {
        $claveOk = hash_equals($guardada, $password);
    }
}

if ($claveOk) {
    session_regenerate_id(true);
    $_SESSION['admin_logged'] = true;
    $_SESSION['admin_user']   = $admin['usuario'];
    $_SESSION['admin_email']  = $admin['correo'];
    $_SESSION['admin_nombre'] = $admin['nombre'] ?? $admin['usuario'];

    if ($mysqli) $mysqli->close();
    flash('exito', 'Bienvenido al panel administrativo, ' . ($admin['nombre'] ?? $admin['usuario']) . '.');
    redirigir("admin_citas.php");
}

if ($mysqli) $mysqli->close();
redirigir("admin_login.html?error=credenciales");
