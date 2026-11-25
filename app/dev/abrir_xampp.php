<?php
// La extensión PHP Server de VS Code no usa Apache ni su configuración.
// En este equipo la usamos solo para abrir la página equivalente en XAMPP.
if (PHP_SAPI !== 'cli-server') {
    http_response_code(404);
    exit;
}

$ruta = explode('?', $_SERVER['REQUEST_URI'] ?? '/', 2)[0];
$vista = basename($ruta);
$vistas = ['registro.html', 'admin_login.html', 'medico_login.html'];
$destino = in_array($vista, $vistas, true) ? $vista : '';

// 303: no reenvía contraseñas u otros datos enviados por POST.
header('Cache-Control: no-store');
header('Location: http://localhost:8080/PrimeLab/' . $destino, true, 303);
exit;
