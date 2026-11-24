<?php
// Punto de entrada público. Solo se cargan las rutas de esta lista.
$rutas = [
    'index.html'        => 'vistas/pacientes/index.html',
    'registro.html'     => 'vistas/pacientes/registro.html',
    'admin_login.html'  => 'vistas/admin/admin_login.html',
    'medico_login.html' => 'vistas/medicos/medico_login.html',

    'login.php'          => 'acceso/login.php',
    'login_paciente.php' => 'acceso/login_paciente.php',
    'login_medico.php'   => 'acceso/login_medico.php',
    'registro.php'       => 'acceso/registro.php',
    'logout.php'         => 'acceso/logout.php',

    'admin_citas.php'     => 'admin/admin_citas.php',
    'admin_centros.php'   => 'admin/admin_centros.php',
    'admin_consultas.php' => 'admin/admin_consultas.php',
    'admin_medicos.php'   => 'admin/admin_medicos.php',
    'panel_medico.php'    => 'medicos/panel_medico.php',

    'panel.php'     => 'pacientes/panel.php',
    'reservar.php'  => 'pacientes/reservar.php',
    'mis_citas.php' => 'pacientes/mis_citas.php',
    'perfil.php'    => 'pacientes/perfil.php',
    'ticket.php'    => 'pacientes/ticket.php',
    'consultas.php' => 'pacientes/consultas.php',

    'consulta_dni.php' => 'api/consulta_dni.php',
];

$rutaSolicitada = explode('?', $_SERVER['REQUEST_URI'] ?? '/', 2)[0];
$nombreRuta = strtolower(substr($rutaSolicitada, strrpos($rutaSolicitada, '/') + 1));
if ($nombreRuta === '' || $nombreRuta === 'index.php') {
    $nombreRuta = 'index.html';
}

if (!isset($rutas[$nombreRuta])) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=UTF-8');
    exit('Página no encontrada.');
}

$archivoRuta = __DIR__ . '/app/' . $rutas[$nombreRuta];
if (str_ends_with($archivoRuta, '.html')) {
    header('Content-Type: text/html; charset=UTF-8');
    readfile($archivoRuta);
    return;
}

require $archivoRuta;
