<?php
/**
 * config.php — Configuración de conexiones de PrimeLab.
 *
 * El sistema es PORTABLE: funciona en cualquier IP y puerto de XAMPP.
 *  - El puerto de MySQL se detecta automáticamente (3306, 3307, 3308, 3309 o 3009).
 *  - La URL de la API interna se arma con el mismo host desde el que se abre el sitio.
 *  - Si necesitas fijar valores manualmente, crea un archivo `config.local.php`
 *    o define variables de entorno:
 *      PRIMELAB_DB_HOST, PRIMELAB_DB_PORT, PRIMELAB_DB_USER,
 *      PRIMELAB_DB_PASS, PRIMELAB_DB_NAME, PRIMELAB_API_URL
 */

/* -------------------------------------------------------------------------
 * 1) Detección automática del puerto de MySQL/MariaDB de XAMPP
 * ---------------------------------------------------------------------- */
if (!function_exists('primelab_detectar_puerto_mysql')) {
    /**
     * Prueba los puertos habituales de XAMPP y devuelve el primero abierto.
     * Si ninguno responde, devuelve 3306 (valor estándar de MySQL).
     */
    function primelab_detectar_puerto_mysql()
    {
        static $puerto = null;
        if ($puerto !== null) {
            return $puerto;
        }

        $host = defined('PRIMELAB_DB_HOST') ? PRIMELAB_DB_HOST : (getenv('PRIMELAB_DB_HOST') ?: '127.0.0.1');
        $candidatos = [3306, 3307, 3308, 3309, 3009];

        foreach ($candidatos as $p) {
            $socket = @fsockopen($host, $p, $errno, $errstr, 0.4);
            if ($socket) {
                fclose($socket);
                return $puerto = $p;
            }
        }

        return $puerto = 3306;
    }
}

/* -------------------------------------------------------------------------
 * 2) Valores opcionales definidos por el usuario (config.local.php)
 * ---------------------------------------------------------------------- */
$primelab_config_local = dirname(__DIR__, 2) . '/config.local.php';
if (is_file($primelab_config_local)) {
    require_once $primelab_config_local;
}

/* -------------------------------------------------------------------------
 * 3) Constantes de conexión
 * ---------------------------------------------------------------------- */
if (!defined('PRIMELAB_DB_HOST')) {
    define('PRIMELAB_DB_HOST', getenv('PRIMELAB_DB_HOST') ?: '127.0.0.1');
}
if (!defined('PRIMELAB_DB_USER')) {
    define('PRIMELAB_DB_USER', getenv('PRIMELAB_DB_USER') ?: 'root');
}
if (!defined('PRIMELAB_DB_PASS')) {
    define('PRIMELAB_DB_PASS', getenv('PRIMELAB_DB_PASS') ?: '');
}
if (!defined('PRIMELAB_DB_NAME')) {
    define('PRIMELAB_DB_NAME', getenv('PRIMELAB_DB_NAME') ?: 'bd_primelab');
}
if (!defined('PRIMELAB_DB_PORT')) {
    $primelab_puerto_env = getenv('PRIMELAB_DB_PORT');
    if ($primelab_puerto_env !== false && $primelab_puerto_env !== '') {
        define('PRIMELAB_DB_PORT', (int) $primelab_puerto_env);
    } else {
        define('PRIMELAB_DB_PORT', primelab_detectar_puerto_mysql());
    }
}

/* -------------------------------------------------------------------------
 * 4) URL de la API interna (Node.js + Express)
 *    Se calcula con el mismo host de la petición para que funcione en LAN.
 * ---------------------------------------------------------------------- */
if (!defined('PRIMELAB_API_URL')) {
    $primelab_api_env = getenv('PRIMELAB_API_URL');
    if ($primelab_api_env) {
        define('PRIMELAB_API_URL', rtrim($primelab_api_env, '/'));
    } else {
        $primelab_host = '127.0.0.1';
        if (!empty($_SERVER['HTTP_HOST'])) {
            $primelab_host = preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST']);
        } elseif (!empty($_SERVER['SERVER_NAME'])) {
            $primelab_host = $_SERVER['SERVER_NAME'];
        }
        if ($primelab_host === '' || $primelab_host === 'localhost') {
            $primelab_host = '127.0.0.1';
        }
        define('PRIMELAB_API_URL', 'http://' . $primelab_host . ':3000');
    }
}
