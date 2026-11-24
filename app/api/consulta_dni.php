<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

// La consulta puede probar varias fuentes externas; se amplía el límite de tiempo
// para que XAMPP no corte la respuesta a la mitad.
@set_time_limit(45);

// Este endpoint solo devuelve JSON: no necesita abrir sesión.
if (!defined('PRIMELAB_SIN_SESION')) define('PRIMELAB_SIN_SESION', true);

require_once __DIR__ . '/../core/file_conexion.php';

// consulta_dni.php — Obtiene nombres/apellidos por DNI.
// Primero consulta la base de datos y la API interna de PrimeLab (Node.js)
// y, si no hay datos, prueba servicios públicos/terceros disponibles sin token.
// Uso: consulta_dni.php?dni=12345678&debug=1

function limpiarTexto($texto) {
    $texto = trim((string)$texto);
    if ($texto === '') return '';
    $texto = preg_replace('/\s+/', ' ', $texto);
    return mb_convert_case(mb_strtolower($texto, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
}

function responder($ok, $dni, $nombres = '', $apellidos = '', $mensaje = '', $extra = []) {
    $out = [
        'ok'        => $ok,
        'dni'       => $dni,
        'nombres'   => $nombres,
        'apellidos' => $apellidos,
        'mensaje'   => $mensaje
    ];
    if (!empty($extra)) $out['detalle'] = $extra;
    echo json_encode($out, JSON_UNESCAPED_UNICODE);
    exit;
}

function fetchRaw($url, $timeout = 10) {
    // Se limita el tiempo para no agotar el máximo de ejecución de PHP.
    $timeout = min((int)$timeout, 6);
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS      => 5,
        CURLOPT_TIMEOUT        => $timeout,
        CURLOPT_CONNECTTIMEOUT => 4,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_ENCODING       => '',
        CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36',
        CURLOPT_HTTPHEADER     => [
            'Accept: application/json, text/plain, */*',
            'Accept-Language: es-PE,es;q=0.9,en;q=0.8',
            'Cache-Control: no-cache'
        ]
    ]);
    $resp = curl_exec($ch);
    $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err  = curl_error($ch);
    curl_close($ch);
    return ['body' => $resp, 'code' => $code, 'error' => $err];
}

function parseJson($body) {
    if (!is_string($body) || $body === '') return null;
    $json = json_decode($body, true);
    return is_array($json) ? $json : null;
}

function normalizarPersona($nombres, $apPaterno = '', $apMaterno = '', $apellidos = '') {
    $n = limpiarTexto($nombres);
    $ap = limpiarTexto(trim($apPaterno . ' ' . $apMaterno));
    if ($ap === '') $ap = limpiarTexto($apellidos);
    // si viene todo junto en nombres, intentar no duplicar
    if ($n === '' && $ap !== '') { $n = ''; }
    return ['nombres' => $n, 'apellidos' => $ap];
}

$dni = trim($_GET['dni'] ?? '');
$debug = isset($_GET['debug']) && $_GET['debug'] == '1';

if (!preg_match('/^\d{8}$/', $dni)) {
    responder(false, $dni, '', '', 'El DNI debe tener exactamente 8 dígitos numéricos.');
}

$intentos = [];

// 0) Base de datos de PrimeLab y API interna de usuarios.
// Aquí se resuelven tanto los DNI de la API como las 7 cuentas nuevas.
$persona = buscarPersonaPorDni($dni);
$intentos['sistema'] = ['fuente' => $persona['fuente'] ?? 'sin coincidencia'];
if ($persona && ($persona['nombres'] !== '' || $persona['apellidos'] !== '')) {
    responder(
        true,
        $dni,
        $persona['nombres'],
        $persona['apellidos'],
        'Datos encontrados en ' . $persona['fuente'] . '.',
        $debug ? $intentos : []
    );
}

if (!function_exists('curl_init')) {
    responder(false, $dni, '', '', 'El servidor no tiene habilitada la extensión cURL de PHP. Activa php_curl en XAMPP/Apache para consultar el DNI.', ['curl' => false]);
}

// 1) SUNAT (consulta pública usada en algunos servicios)
$r = fetchRaw('https://ww1.sunat.gob.pe/ol-ti-itelav455nameregsunat/api/registro/solicitud?nrodocumento=' . $dni, 12);
$j = parseJson($r['body']);
$intentos['sunat'] = ['http' => $r['code'], 'error' => $r['error']];
if ($j) {
    $nombreCompleto = trim($j['nombre'] ?? '');
    if ($nombreCompleto !== '' && $nombreCompleto !== ',') {
        $partes = explode(',', $nombreCompleto, 2);
        $apellidos = limpiarTexto($partes[0] ?? '');
        $nombres = limpiarTexto($partes[1] ?? '');
        if ($nombres !== '' || $apellidos !== '') {
            responder(true, $dni, $nombres, $apellidos, 'Datos encontrados (SUNAT).', $debug ? $intentos : []);
        }
    }
}

// 2) apis.net.pe v1 (pública sin token en algunos casos, puede requerir token)
$r = fetchRaw('https://api.apis.net.pe/v1/dni?numero=' . $dni, 10);
$j = parseJson($r['body']);
$intentos['apis.net.pe.v1'] = ['http' => $r['code'], 'error' => $r['error']];
if ($j && empty($j['message']) && (isset($j['nombres']) || isset($j['apellidoPaterno']) || isset($j['nombre']))) {
    $p = normalizarPersona($j['nombres'] ?? ($j['nombre'] ?? ''), $j['apellidoPaterno'] ?? '', $j['apellidoMaterno'] ?? '', $j['apellidos'] ?? '');
    if ($p['nombres'] !== '' || $p['apellidos'] !== '') {
        responder(true, $dni, $p['nombres'], $p['apellidos'], 'Datos encontrados.', $debug ? $intentos : []);
    }
}

// 3) apis.net.pe v2
$r = fetchRaw('https://api.apis.net.pe/v2/reniec/dni?numero=' . $dni, 10);
$j = parseJson($r['body']);
$intentos['apis.net.pe.v2'] = ['http' => $r['code'], 'error' => $r['error']];
if ($j && empty($j['message']) && (isset($j['nombres']) || isset($j['apellidoPaterno']))) {
    $p = normalizarPersona($j['nombres'] ?? '', $j['apellidoPaterno'] ?? '', $j['apellidoMaterno'] ?? '');
    if ($p['nombres'] !== '' || $p['apellidos'] !== '') {
        responder(true, $dni, $p['nombres'], $p['apellidos'], 'Datos encontrados.', $debug ? $intentos : []);
    }
}

// 4) miapi.cloud
$r = fetchRaw('https://miapi.cloud/v1/dni/' . $dni, 10);
$j = parseJson($r['body']);
$intentos['miapi.cloud'] = ['http' => $r['code'], 'error' => $r['error']];
if ($j && (isset($j['nombres']) || isset($j['datos']['nombres']) || isset($j['name']))) {
    $src = isset($j['datos']) && is_array($j['datos']) ? $j['datos'] : $j;
    $p = normalizarPersona($src['nombres'] ?? ($src['name'] ?? ''), $src['apellido_paterno'] ?? ($src['apellidoPaterno'] ?? ''), $src['apellido_materno'] ?? ($src['apellidoMaterno'] ?? ''), $src['apellidos'] ?? ($src['apellido'] ?? ''));
    if ($p['nombres'] !== '' || $p['apellidos'] !== '') {
        responder(true, $dni, $p['nombres'], $p['apellidos'], 'Datos encontrados.', $debug ? $intentos : []);
    }
}

// 5) apiperu.dev (público - puede cambiar)
$r = fetchRaw('https://apiperu.dev/api/dni/' . $dni, 10);
$j = parseJson($r['body']);
$intentos['apiperu.dev'] = ['http' => $r['code'], 'error' => $r['error']];
if ($j && (isset($j['data']) || isset($j['nombres']))) {
    $src = isset($j['data']) && is_array($j['data']) ? $j['data'] : $j;
    $p = normalizarPersona($src['nombres'] ?? '', $src['apellido_paterno'] ?? ($src['apellidoPaterno'] ?? ''), $src['apellido_materno'] ?? ($src['apellidoMaterno'] ?? ''), $src['nombre_completo'] ?? '');
    if ($p['nombres'] === '' && $p['apellidos'] === '' && !empty($src['nombre_completo'])) {
        // intentar split simple
        $nc = limpiarTexto($src['nombre_completo']);
        $p['nombres'] = $nc;
    }
    if ($p['nombres'] !== '' || $p['apellidos'] !== '') {
        responder(true, $dni, $p['nombres'], $p['apellidos'], 'Datos encontrados.', $debug ? $intentos : []);
    }
}

// 6) api.perudevs.com (demo público a veces)
$r = fetchRaw('https://api.perudevs.com/api/v1/dni/simple?document=' . $dni . '&key=demo', 10);
$j = parseJson($r['body']);
$intentos['perudevs.demo'] = ['http' => $r['code'], 'error' => $r['error']];
if ($j && (isset($j['nombres']) || isset($j['resultado']))) {
    $src = isset($j['resultado']) && is_array($j['resultado']) ? $j['resultado'] : $j;
    $p = normalizarPersona($src['nombres'] ?? '', $src['apellido_paterno'] ?? ($src['apellidoPaterno'] ?? ''), $src['apellido_materno'] ?? ($src['apellidoMaterno'] ?? ''), $src['apellidos'] ?? '');
    if ($p['nombres'] !== '' || $p['apellidos'] !== '') {
        responder(true, $dni, $p['nombres'], $p['apellidos'], 'Datos encontrados.', $debug ? $intentos : []);
    }
}

// 7) reniec.cloud (servicio alternativo de consulta RENIEC)
$r = fetchRaw('https://api.reniec.cloud/dni/' . $dni, 10);
$j = parseJson($r['body']);
$intentos['reniec.cloud'] = ['http' => $r['code'], 'error' => $r['error']];
if ($j && (isset($j['nombres']) || isset($j['apellidoPaterno']) || isset($j['nombres_completos']))) {
    $p = normalizarPersona(
        $j['nombres'] ?? ($j['nombres_completos'] ?? ''),
        $j['apellidoPaterno'] ?? ($j['apellido_paterno'] ?? ''),
        $j['apellidoMaterno'] ?? ($j['apellido_materno'] ?? '')
    );
    if ($p['nombres'] !== '' || $p['apellidos'] !== '') {
        responder(true, $dni, $p['nombres'], $p['apellidos'], 'Datos encontrados (RENIEC).', $debug ? $intentos : []);
    }
}

$mensaje = 'No se pudo obtener los nombres y apellidos para este DNI en el directorio interno ni en las fuentes RENIEC consultadas. ';
$mensaje .= 'RENIEC no ofrece una API pública oficial gratuita y directa; los servicios alternativos pueden estar caídos, limitados o requerir token. ';
$mensaje .= 'Verifica que tengas internet y que la extensión cURL esté habilitada en PHP. ';
$mensaje .= 'También puedes escribir tus nombres y apellidos manualmente (el sistema lo permite como respaldo).';

responder(false, $dni, '', '', $mensaje, $debug ? $intentos : []);
?>
