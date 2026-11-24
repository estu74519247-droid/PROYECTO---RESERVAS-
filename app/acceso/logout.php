<?php
// logout.php — Cierra la sesión del paciente, médico o administrador.
session_start();

$eraAdmin  = !empty($_SESSION['admin_logged']);
$eraMedico = !empty($_SESSION['medico_codm']);

$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
session_destroy();

if ($eraAdmin) {
    header('Location: admin_login.html?msg=salir');
} elseif ($eraMedico) {
    header('Location: medico_login.html?msg=salir');
} else {
    header('Location: index.html?msg=salir');
}
exit;
