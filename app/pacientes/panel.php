<?php
// panel.php — Panel principal del paciente.
require_once __DIR__ . '/../core/file_conexion.php';
requerirPaciente();

$mysqli = Conectarse();
$paciente = pacienteActual($mysqli);
if (!$paciente) { $mysqli->close(); redirigir("index.html?msg=sesion"); }

$codp = $paciente['codp'];

// Próxima cita pendiente
$stmt = $mysqli->prepare("
    SELECT c.codci, c.fechaci, c.horaci, c.estado, c.motivo,
           ce.nomce, ce.dirc, ce.distrito,
           e.nomesp, m.codm, m.nomm
    FROM cita c
    JOIN centro ce ON ce.codce = c.codce
    JOIN medico m ON m.codm = c.codm
    JOIN especialidad e ON e.codesp = m.codesp
    WHERE c.codp = ? AND c.estado = 'pendiente' AND c.fechaci >= CURDATE()
    ORDER BY c.fechaci ASC, c.horaci ASC
    LIMIT 1
");
$stmt->bind_param("s", $codp);
$stmt->execute();
$proxima = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Conteos
function contarCitas($mysqli, $codp, $estado = null) {
    if ($estado) {
        $stmt = $mysqli->prepare("SELECT COUNT(*) AS n FROM cita WHERE codp = ? AND estado = ?");
        $stmt->bind_param("ss", $codp, $estado);
    } else {
        $stmt = $mysqli->prepare("SELECT COUNT(*) AS n FROM cita WHERE codp = ?");
        $stmt->bind_param("s", $codp);
    }
    $stmt->execute();
    $n = (int)$stmt->get_result()->fetch_assoc()['n'];
    $stmt->close();
    return $n;
}
$total = contarCitas($mysqli, $codp);
$pendientes = contarCitas($mysqli, $codp, 'pendiente');
$atendidas = contarCitas($mysqli, $codp, 'atendida');

$mysqli->close();
$nombreCompleto = $paciente['nomp'] . ' ' . $paciente['apep'];
$inicial = mb_strtoupper(mb_substr($paciente['nomp'], 0, 1, 'UTF-8'), 'UTF-8');

// ---- Datos para la presentación (saludo, fecha y cuenta regresiva) ----
$ahora = new DateTime('now', new DateTimeZone('America/Lima'));
$hora = (int)$ahora->format('G');
$saludo = ($hora < 12) ? 'Buenos días' : (($hora < 19) ? 'Buenas tardes' : 'Buenas noches');

$partesNombre = preg_split('/\s+/', trim($paciente['nomp']));
$primerNombre = $partesNombre[0] ?? $paciente['nomp'];

$diasSemana = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
$mesesAnio  = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
$hoyTexto = ucfirst($diasSemana[(int)$ahora->format('w')]) . ', ' . $ahora->format('j') . ' de ' . $mesesAnio[(int)$ahora->format('n') - 1];

$cuenta = null;
if ($proxima) {
    $hoyFecha = new DateTime($ahora->format('Y-m-d'));
    $fechaCita = new DateTime($proxima['fechaci']);
    $dias = (int)$hoyFecha->diff($fechaCita)->format('%r%a');
    if ($dias <= 0)      $cuenta = 'Hoy';
    elseif ($dias === 1) $cuenta = 'Mañana';
    else                 $cuenta = 'En ' . $dias . ' días';
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del paciente — PrimeLab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Source+Sans+3:wght@400;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header class="topbar">
        <a href="panel.php" class="brand">
            <img src="img/PrimeLab%20Logo.png" alt="PrimeLab" class="brand-logo">
            <span class="brand-copy"><strong>PrimeLab</strong><small>Centros de salud públicos del Perú</small></span>
        </a>
        <div class="topbar-right">
            <div class="section-tag"><span>Sección actual</span><b>INICIO</b></div>
        </div>
    </header>

    <div class="app-layout">
        <aside class="sidebar">
            <div class="user-chip">
                <div class="avatar"><?= e($inicial) ?></div>
                <div>
                    <strong><?= e($nombreCompleto) ?></strong>
                    <small>DNI <?= e($paciente['dni']) ?></small>
                </div>
            </div>
            <nav>
                <p class="nav-label">Paciente</p>
                <a href="panel.php" class="nav-btn active"><span class="ico">🏠</span><span>Inicio</span></a>
                <a href="reservar.php" class="nav-btn"><span class="ico">📅</span><span>Reservar cita</span></a>
                <a href="mis_citas.php" class="nav-btn"><span class="ico">🗂</span><span>Mis citas</span></a>
                <a href="consultas.php" class="nav-btn"><span class="ico">🩺</span><span>Consultas</span></a>
                <a href="perfil.php" class="nav-btn"><span class="ico">👤</span><span>Mi perfil</span></a>
            </nav>
            <div class="account-actions">
                <a href="logout.php" class="nav-btn nav-btn-danger"><span class="ico">⎋</span><span>Cerrar
                        sesión</span></a>
            </div>
        </aside>

        <main class="content">
            <?php mostrarFlash(); ?>

            <div class="page-heading">
                <p class="eyebrow"><?= e($hoyTexto) ?></p>
                <h1><?= e($saludo) ?>, <?= e($primerNombre) ?></h1>
                <p class="muted">Aquí tienes el resumen de tus reservas en centros de salud públicos.</p>
            </div>

            <section class="dashboard-banner">
                <div class="banner-copy">
                    <h2>Reserva tu cita de forma rápida y segura</h2>
                    <p>Elige el centro, la especialidad, el médico y el horario que mejor se adapte a ti.</p>
                    <div class="banner-actions">
                        <a href="reservar.php" class="button primary">Reservar nueva cita</a>
                        <a href="consultas.php" class="button secondary">Ver consultas</a>
                    </div>
                </div>
                <div class="banner-visual">
                    <?php if ($proxima): ?>
                    <div class="mini-ticket">
                        <span class="mini-ticket-cuenta"><?= e($cuenta) ?></span>
                        <strong>Próxima cita</strong>
                        <small><?= e($proxima['nomesp']) ?></small>
                        <small><?= e(fechaTexto($proxima['fechaci'])) ?> ·
                            <?= e(horaTexto($proxima['horaci'])) ?></small>
                        <small><?= e($proxima['nomce']) ?></small>
                    </div>
                    <?php endif; ?>
                </div>
            </section>

            <section class="dashboard-overview" aria-label="Resumen de tus citas">
                <a href="mis_citas.php" class="summary-card summary-total">
                    <span class="summary-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <rect width="18" height="18" x="3" y="4" rx="2" />
                            <path d="M16 2v4M8 2v4M3 10h18M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01" />
                        </svg>
                    </span>
                    <span class="summary-label">Citas totales</span>
                    <strong class="summary-count" data-contar="<?= (int)$total ?>"><?= (int)$total ?></strong>
                    <span class="summary-details">registradas</span>
                </a>
                <a href="mis_citas.php" class="summary-card summary-pend">
                    <span class="summary-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M12 6v6l4 2" />
                        </svg>
                    </span>
                    <span class="summary-label">Pendientes</span>
                    <strong class="summary-count" data-contar="<?= (int)$pendientes ?>"><?= (int)$pendientes ?></strong>
                    <span class="summary-details">por atender</span>
                </a>
                <a href="mis_citas.php" class="summary-card summary-ok">
                    <span class="summary-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <path d="m9 11 3 3L22 4" />
                        </svg>
                    </span>
                    <span class="summary-label">Atendidas</span>
                    <strong class="summary-count" data-contar="<?= (int)$atendidas ?>"><?= (int)$atendidas ?></strong>
                    <span class="summary-details">completadas</span>
                </a>
            </section>

            <?php if ($proxima): ?>
            <section class="panel">
                <div class="panel-head">
                    <h2 class="panel-title">Próxima cita pendiente</h2>
                    <div class="panel-head-right">
                        <span class="countdown-badge"><?= e($cuenta) ?></span>
                        <a href="ticket.php?codigo=<?= e($proxima['codci']) ?>" class="link-inline">Ver ticket</a>
                    </div>
                </div>
                <div class="data-grid">
                    <div><span>Código</span><strong><?= e($proxima['codci']) ?></strong></div>
                    <div><span>Especialidad</span><strong><?= e($proxima['nomesp']) ?></strong></div>
                    <div><span>Médico</span><strong
                            class="medico-inline"><?= fotoMedico($proxima['codm'], $proxima['nomm'], 40) ?><span><?= e($proxima['nomm']) ?></span></strong>
                    </div>
                    <div><span>Centro</span><strong><?= e($proxima['nomce']) ?></strong></div>
                    <div><span>Fecha</span><strong><?= e(fechaTexto($proxima['fechaci'])) ?></strong></div>
                    <div><span>Hora</span><strong><?= e(horaTexto($proxima['horaci'])) ?></strong></div>
                </div>
                <ul class="tips-list">
                    <li>Llega con 15 minutos de anticipación.</li>
                    <li>Lleva tu DNI para identificarte.</li>
                    <li>Si no puedes asistir, avisa con tiempo en el centro de salud.</li>
                </ul>
            </section>
            <?php else: ?>
            <section class="panel empty-state">
                <svg class="empty-illustration" viewBox="0 0 120 120" aria-hidden="true">
                    <defs>
                        <linearGradient id="egrad" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0" stop-color="#38a395" />
                            <stop offset="1" stop-color="#14504b" />
                        </linearGradient>
                    </defs>
                    <circle cx="60" cy="60" r="56" fill="#e6f4f1" />
                    <rect x="26" y="32" width="68" height="62" rx="10" fill="#fff" stroke="url(#egrad)"
                        stroke-width="4" />
                    <path d="M26 50h68" stroke="url(#egrad)" stroke-width="4" />
                    <path d="M44 26v14M76 26v14" stroke="url(#egrad)" stroke-width="5" stroke-linecap="round" />
                    <circle cx="60" cy="72" r="13" fill="url(#egrad)" />
                    <path d="M60 66v12M54 72h12" stroke="#fff" stroke-width="4" stroke-linecap="round" />
                </svg>
                <div class="empty-copy">
                    <h2 class="panel-title">No tienes citas pendientes</h2>
                    <p class="muted">Cuando reserves una cita aparecerá aquí con su información.</p>
                    <a href="reservar.php" class="button primary">Reservar ahora</a>
                </div>
            </section>
            <?php endif; ?>
        </main>
    </div>

    <footer class="site-footer">
        <p>PrimeLab — Sistema de reserva de citas en centros de salud públicos</p>
    </footer>
    <script src="js/main.js"></script>
    <script>
    // Los números del resumen suben desde 0 al cargar (se omite si la persona prefiere menos movimiento).
    (function() {
        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        document.querySelectorAll('[data-contar]').forEach(function(el) {
            var fin = parseInt(el.getAttribute('data-contar'), 10) || 0;
            if (fin <= 0) return;
            var inicio = null,
                duracion = 700;
            el.textContent = '0';

            function paso(t) {
                if (inicio === null) inicio = t;
                var p = Math.min((t - inicio) / duracion, 1);
                el.textContent = Math.round(fin * (1 - Math.pow(1 - p, 3)));
                if (p < 1) requestAnimationFrame(paso);
            }
            requestAnimationFrame(paso);
        });
    })();
    </script>
</body>

</html>