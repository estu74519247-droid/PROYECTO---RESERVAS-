<?php
// ticket.php — Comprobante de una cita del paciente.
require_once __DIR__ . '/../core/file_conexion.php';
requerirPaciente();

$mysqli = Conectarse();
$paciente = pacienteActual($mysqli);
if (!$paciente) { $mysqli->close(); redirigir("index.html?msg=sesion"); }

$codigo = trim($_GET['codigo'] ?? '');
$stmt = $mysqli->prepare("
    SELECT c.codci, c.fechaci, c.horaci, c.estado, c.motivo, c.costo, c.fechareg, c.observaciones,
           c.diagnostico, c.indicaciones, c.reprogramada_de, c.motivo_reprogramacion,
           ce.nomce, ce.dirc, ce.distrito, ce.provincia, ce.departamento, ce.telefono AS telce, ce.horario_atencion, ce.tipo AS tipoce,
           e.nomesp, m.codm, m.nomm, m.cmpm,
           p.nomp, p.apep, p.dni, p.telp, p.correo, p.seguro
    FROM cita c
    JOIN centro ce ON ce.codce = c.codce
    JOIN medico m ON m.codm = c.codm
    JOIN especialidad e ON e.codesp = m.codesp
    JOIN paciente p ON p.codp = c.codp
    WHERE c.codci = ? AND c.codp = ?
    LIMIT 1
");
$stmt->bind_param("ss", $codigo, $paciente['codp']);
$stmt->execute();
$cita = $stmt->get_result()->fetch_assoc();
$stmt->close();
$mysqli->close();

if (!$cita) {
    flash('error', 'No se encontró la cita solicitada.');
    redirigir("mis_citas.php");
}

$nombreCompleto = $cita['nomp'] . ' ' . $cita['apep'];
$inicial = mb_strtoupper(mb_substr($cita['nomp'], 0, 1, 'UTF-8'), 'UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket <?= e($cita['codci']) ?> — PrimeLab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body data-page="ticket">
<header class="topbar">
    <a href="panel.php" class="brand">
        <img src="img/PrimeLab%20Logo.png" alt="PrimeLab" class="brand-logo">
        <span class="brand-copy"><strong>PrimeLab</strong><small>Centros de salud públicos del Perú</small></span>
    </a>
    <div class="topbar-right"><div class="section-tag"><span>Sección actual</span><b>TICKET</b></div></div>
</header>

<div class="app-layout">
    <aside class="sidebar">
        <div class="user-chip">
            <div class="avatar"><?= e($inicial) ?></div>
            <div><strong><?= e($nombreCompleto) ?></strong><small>DNI <?= e($cita['dni']) ?></small></div>
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
        <div id="alertBox" class="alert" aria-live="polite"></div>
        <?php mostrarFlash(); ?>

        <div class="page-heading">
            <p class="eyebrow">Comprobante</p>
            <h1>Ticket de cita</h1>
            <p class="muted">Presenta este comprobante el día de tu atención.</p>
        </div>

        <section class="ticket-card">
            <div class="ticket-code">
                <span>Código de cita</span>
                <strong><?= e($cita['codci']) ?></strong>
            </div>
            <hr class="rule">
            <div class="summary-list">
                <div><span>Paciente</span><strong><?= e($nombreCompleto) ?> (DNI <?= e($cita['dni']) ?>)</strong></div>
                <div><span>Seguro</span><strong><?= e(textoSeguro($cita['seguro'] ?? 'SIS')) ?></strong></div>
                <div><span>Costo de la cita</span><strong><?= e(costoTexto($cita['seguro'] ?? 'SIS', $cita['costo'])) ?></strong></div>
                <div><span>Especialidad</span><strong><?= e($cita['nomesp']) ?></strong></div>
                <div><span>Médico</span><strong class="medico-inline"><?= fotoMedico($cita['codm'], $cita['nomm'], 40) ?><span><?= e($cita['nomm']) ?> · CMP <?= e($cita['cmpm']) ?></span></strong></div>
                <div><span>Centro</span><strong><?= e($cita['nomce']) ?> (<?= e($cita['tipoce'] ?? 'Público') ?>)</strong></div>
                <div><span>Dirección</span><strong><?= e($cita['dirc']) ?>, <?= e($cita['distrito']) ?>, <?= e($cita['provincia'] ?? '') ?></strong></div>
                <div><span>Horario centro</span><strong><?= e($cita['horario_atencion'] ?? '-') ?></strong></div>
                <div><span>Teléfono centro</span><strong><?= e($cita['telce']) ?></strong></div>
                <div><span>Fecha</span><strong><?= e(fechaTexto($cita['fechaci'])) ?></strong></div>
                <div><span>Hora</span><strong><?= e(horaTexto($cita['horaci'])) ?></strong></div>
                <div><span>Estado</span><strong><?= e(ucfirst($cita['estado'])) ?></strong></div>
                <div><span>Motivo</span><strong><?= e($cita['motivo']) ?></strong></div>
                <?php if (!empty($cita['diagnostico'])): ?>
                <div><span>Diagnóstico / Lo observado</span><strong><?= nl2br(e($cita['diagnostico'])) ?></strong></div>
                <?php endif; ?>
                <?php if (!empty($cita['indicaciones'])): ?>
                <div><span>Indicaciones / Medicamentos</span><strong><?= nl2br(e($cita['indicaciones'])) ?></strong></div>
                <?php endif; ?>
                <?php if (!empty($cita['motivo_reprogramacion'])): ?>
                <div><span>Motivo de reprogramación</span><strong><?= e($cita['motivo_reprogramacion']) ?></strong></div>
                <?php endif; ?>
                <?php if (!empty($cita['reprogramada_de'])): ?>
                <div><span>Reprogramada desde cita</span><strong><?= e($cita['reprogramada_de']) ?></strong></div>
                <?php endif; ?>
                <?php if (!empty($cita['observaciones'])): ?>
                <div><span>Observaciones</span><strong><?= e($cita['observaciones']) ?></strong></div>
                <?php endif; ?>
                <div><span>Registrada</span><strong><?= e($cita['fechareg']) ?></strong></div>
            </div>
            <div class="ticket-actions">
                <button type="button" class="button primary" onclick="window.print()">Imprimir ticket</button>
                <a href="mis_citas.php" class="button secondary">Volver a mis citas</a>
            </div>
        </section>
    </main>
</div>

<footer class="site-footer"><p>PrimeLab — Sistema de reserva de citas en centros de salud públicos</p></footer>
<script src="js/main.js"></script>
</body>
</html>
