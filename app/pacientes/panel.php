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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del paciente — PrimeLab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
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
            <a href="logout.php" class="nav-btn nav-btn-danger"><span class="ico">⎋</span><span>Cerrar sesión</span></a>
        </div>
    </aside>

    <main class="content">
        <?php mostrarFlash(); ?>

        <div class="page-heading">
            <p class="eyebrow">Bienvenido</p>
            <h1>Bienvenido, <?= e($nombreCompleto) ?></h1>
            <p class="muted">Gestiona tus reservas y consulta tus citas en centros de salud públicos.</p>
        </div>

        <section class="dashboard-banner">
            <div class="banner-copy">
                <h2>Reserva tu cita de forma rápida y segura</h2>
                <p>Elige el centro, la especialidad, el médico y el horario que mejor se adapte a ti.</p>
                <a href="reservar.php" class="button primary">Reservar nueva cita</a>
                <a href="consultas.php" class="button secondary">Ver consultas</a>
            </div>
            <div class="banner-visual">
                <?php if ($proxima): ?>
                <div class="mini-ticket">
                    <strong>Próxima cita</strong>
                    <small><?= e($proxima['nomesp']) ?></small>
                    <small><?= e(fechaTexto($proxima['fechaci'])) ?> · <?= e(horaTexto($proxima['horaci'])) ?></small>
                    <small><?= e($proxima['nomce']) ?></small>
                </div>
                <?php endif; ?>
            </div>
        </section>

        <section class="dashboard-overview">
            <article class="summary-card">
                <span class="summary-label">Citas totales</span>
                <strong class="summary-count"><?= $total ?></strong>
                <span class="summary-details">registradas</span>
            </article>
            <article class="summary-card">
                <span class="summary-label">Pendientes</span>
                <strong class="summary-count"><?= $pendientes ?></strong>
                <span class="summary-details">por atender</span>
            </article>
            <article class="summary-card">
                <span class="summary-label">Atendidas</span>
                <strong class="summary-count"><?= $atendidas ?></strong>
                <span class="summary-details">completadas</span>
            </article>
        </section>

        <?php if ($proxima): ?>
        <section class="panel">
            <div class="panel-head">
                <h2 class="panel-title">Próxima cita pendiente</h2>
                <a href="ticket.php?codigo=<?= e($proxima['codci']) ?>" class="link-inline">Ver ticket</a>
            </div>
            <div class="data-grid">
                <div><span>Código</span><strong><?= e($proxima['codci']) ?></strong></div>
                <div><span>Especialidad</span><strong><?= e($proxima['nomesp']) ?></strong></div>
                <div><span>Médico</span><strong class="medico-inline"><?= fotoMedico($proxima['codm'], $proxima['nomm'], 40) ?><span><?= e($proxima['nomm']) ?></span></strong></div>
                <div><span>Centro</span><strong><?= e($proxima['nomce']) ?></strong></div>
                <div><span>Fecha</span><strong><?= e(fechaTexto($proxima['fechaci'])) ?></strong></div>
                <div><span>Hora</span><strong><?= e(horaTexto($proxima['horaci'])) ?></strong></div>
            </div>
        </section>
        <?php else: ?>
        <section class="panel">
            <h2 class="panel-title">No tienes citas pendientes</h2>
            <p class="muted">Cuando reserves una cita aparecerá aquí con su información.</p>
            <a href="reservar.php" class="button secondary">Reservar ahora</a>
        </section>
        <?php endif; ?>
    </main>
</div>

<footer class="site-footer">
    <p>PrimeLab — Sistema de reserva de citas en centros de salud públicos</p>
</footer>
<script src="js/main.js"></script>
</body>
</html>
