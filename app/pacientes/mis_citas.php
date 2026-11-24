<?php
// mis_citas.php — Listado de citas del paciente.
require_once __DIR__ . '/../core/file_conexion.php';
requerirPaciente();

$mysqli = Conectarse();
$paciente = pacienteActual($mysqli);
if (!$paciente) { $mysqli->close(); redirigir("index.html?msg=sesion"); }

$codp = $paciente['codp'];
$stmt = $mysqli->prepare("
    SELECT c.codci, c.fechaci, c.horaci, c.estado, c.motivo, c.costo, c.fechareg, c.observaciones,
           c.diagnostico, c.indicaciones, c.reprogramada_de, c.motivo_reprogramacion,
           ce.nomce, ce.distrito,
           e.nomesp, m.codm, m.nomm
    FROM cita c
    JOIN centro ce ON ce.codce = c.codce
    JOIN medico m ON m.codm = c.codm
    JOIN especialidad e ON e.codesp = m.codesp
    WHERE c.codp = ?
    ORDER BY c.fechaci DESC, c.horaci DESC
");
$stmt->bind_param("s", $codp);
$stmt->execute();
$citas = $stmt->get_result();
$mysqli->close();

$nombreCompleto = $paciente['nomp'] . ' ' . $paciente['apep'];
$inicial = mb_strtoupper(mb_substr($paciente['nomp'], 0, 1, 'UTF-8'), 'UTF-8');

function badgeEstado($estado) {
    $e = strtolower($estado);
    $clase = 'status-pending';
    if ($e === 'atendida') $clase = 'status-done';
    if ($e === 'cancelada' || $e === 'reprogramada') $clase = 'status-cancel';
    if ($e === 'extendida') $clase = 'status-pending';
    return '<span class="status-badge ' . $clase . '">' . e(ucfirst($e)) . '</span>';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis citas — PrimeLab</title>
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
    <div class="topbar-right"><div class="section-tag"><span>Sección actual</span><b>MIS CITAS</b></div></div>
</header>

<div class="app-layout">
    <aside class="sidebar">
        <div class="user-chip">
            <div class="avatar"><?= e($inicial) ?></div>
            <div><strong><?= e($nombreCompleto) ?></strong><small>DNI <?= e($paciente['dni']) ?></small></div>
        </div>
        <nav>
            <p class="nav-label">Paciente</p>
            <a href="panel.php" class="nav-btn"><span class="ico">🏠</span><span>Inicio</span></a>
            <a href="reservar.php" class="nav-btn"><span class="ico">📅</span><span>Reservar cita</span></a>
            <a href="mis_citas.php" class="nav-btn active"><span class="ico">🗂</span><span>Mis citas</span></a>
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
            <p class="eyebrow">Historial</p>
            <h1>Mis citas</h1>
            <p class="muted">Consulta el estado y detalle de todas tus reservas.</p>
        </div>

        <section class="panel">
            <div class="panel-head">
                <h2 class="panel-title">Listado de citas</h2>
                <a href="reservar.php" class="button secondary">Nueva cita</a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Fecha y hora</th>
                            <th>Centro</th>
                            <th>Especialidad</th>
                            <th>Médico</th>
                            <th>Costo</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($citas->num_rows === 0): ?>
                            <tr><td colspan="8" class="table-empty">Aún no tienes citas registradas.</td></tr>
                        <?php else: ?>
                            <?php while ($c = $citas->fetch_assoc()): ?>
                            <tr>
                                <td><span class="code"><?= e($c['codci']) ?></span></td>
                                <td><?= e(fechaTexto($c['fechaci'])) ?><br><small class="muted"><?= e(horaTexto($c['horaci'])) ?></small></td>
                                <td><?= e($c['nomce']) ?><br><small class="muted"><?= e($c['distrito']) ?></small></td>
                                <td><?= e($c['nomesp']) ?></td>
                                <td><span class="medico-inline"><?= fotoMedico($c['codm'], $c['nomm'], 36) ?><span><?= e($c['nomm']) ?></span></span></td>
                                <td>S/ <?= number_format((float)$c['costo'], 2) ?></td>
                                <td><?= badgeEstado($c['estado']) ?></td>
                                <td class="actions-cell">
                                    <a href="ticket.php?codigo=<?= e($c['codci']) ?>" class="button secondary small">Ver ticket</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>

<footer class="site-footer"><p>PrimeLab — Sistema de reserva de citas en centros de salud públicos</p></footer>
<script src="js/main.js"></script>
</body>
</html>
