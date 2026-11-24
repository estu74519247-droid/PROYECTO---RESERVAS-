<?php
// panel_medico.php — Panel del médico: perfil, gestión de citas, atención y reprogramación.
require_once __DIR__ . '/../core/file_conexion.php';
requerirMedico();

$mysqli = Conectarse();
$medico = medicoActual($mysqli);
if (!$medico) { $mysqli->close(); redirigir("medico_login.html?msg=sesion"); }

$codm = $medico['codm'];

function badgeEstadoMed($estado) {
    $e = strtolower(trim((string)$estado));
    $clase = 'status-pending';
    if ($e === 'atendida') $clase = 'status-done';
    if ($e === 'cancelada' || $e === 'reprogramada') $clase = 'status-cancel';
    if ($e === 'extendida') $clase = 'status-pending'; // se estiliza como en curso
    $label = ucfirst($e);
    return '<span class="status-badge ' . $clase . '">' . e($label) . '</span>';
}

// Procesar acciones del médico
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $codci  = trim($_POST['codci'] ?? '');

    // Verificar que la cita pertenezca al médico
    $cita = null;
    if ($codci !== '') {
        $stmt = $mysqli->prepare("SELECT * FROM cita WHERE codci = ? AND codm = ? LIMIT 1");
        $stmt->bind_param("ss", $codci, $codm);
        $stmt->execute();
        $cita = $stmt->get_result()->fetch_assoc();
        $stmt->close();
    }

    if (!$cita && in_array($accion, ['guardar_atencion','reprogramar'], true)) {
        flash('error', 'No se encontró la cita o no tienes permisos sobre ella.');
        $mysqli->close();
        redirigir('panel_medico.php');
    }

    if ($accion === 'guardar_atencion' && $cita) {
        $estadoNuevo  = trim($_POST['estado'] ?? $cita['estado']);
        $diagnostico  = trim($_POST['diagnostico'] ?? '');
        $indicaciones = trim($_POST['indicaciones'] ?? '');
        $observaciones = trim($_POST['observaciones'] ?? '');

        $estadosPermitidos = ['pendiente','atendida','cancelada','extendida','reprogramada'];
        if (!in_array($estadoNuevo, $estadosPermitidos, true)) $estadoNuevo = $cita['estado'];

        $stmt = $mysqli->prepare("UPDATE cita SET estado=?, diagnostico=?, indicaciones=?, observaciones=? WHERE codci=? AND codm=?");
        $diagDb = $diagnostico !== '' ? $diagnostico : null;
        $indDb  = $indicaciones !== '' ? $indicaciones : null;
        $obsDb  = $observaciones !== '' ? $observaciones : null;
        $stmt->bind_param("ssssss", $estadoNuevo, $diagDb, $indDb, $obsDb, $codci, $codm);
        $stmt->execute();
        $stmt->close();

        $stmt = $mysqli->prepare("INSERT INTO cita_historial (codci, accion, motivo, fecha, usuario_accion) VALUES (?, 'atencion', ?, NOW(), ?)");
        $motHist = 'Estado: ' . $estadoNuevo . ($diagnostico ? ' | Dx: ' . mb_substr($diagnostico,0,120) : '');
        $stmt->bind_param("sss", $codci, $motHist, $codm);
        $stmt->execute();
        $stmt->close();

        flash('exito', 'Atención de la cita ' . $codci . ' actualizada correctamente.');
        $mysqli->close();
        redirigir('panel_medico.php');
    }

    if ($accion === 'reprogramar' && $cita) {
        $nuevaFecha = trim($_POST['nueva_fecha'] ?? '');
        $nuevaHora  = trim($_POST['nueva_hora'] ?? '');
        $motivoReprog = trim($_POST['motivo_reprogramacion'] ?? '');
        $motivoCitaNueva = trim($_POST['motivo_nueva_cita'] ?? $cita['motivo']);
        $diagnostico  = trim($_POST['diagnostico'] ?? '');
        $indicaciones = trim($_POST['indicaciones'] ?? '');

        $motivosValidos = [
            'Inasistencia del paciente',
            'Cancelación',
            'Seguimiento del tratamiento',
            'Evaluación de resultados de exámenes',
            'Error administrativo del sistema',
            'Otro'
        ];
        if (!in_array($motivoReprog, $motivosValidos, true)) {
            $motivoReprog = 'Reprogramación solicitada';
        }

        $hoy = date('Y-m-d');
        if ($nuevaFecha === '' || $nuevaHora === '' || $nuevaFecha < $hoy) {
            flash('error', 'Indica una fecha y hora válidas para la nueva cita.');
            $mysqli->close();
            redirigir('panel_medico.php');
        }
        $horaDb = $nuevaHora;
        if (preg_match('/^\d{2}:\d{2}$/', $nuevaHora)) $horaDb = $nuevaHora . ':00';

        // Verificar disponibilidad del mismo médico
        $stmt = $mysqli->prepare("SELECT COUNT(*) AS n FROM cita WHERE codm=? AND fechaci=? AND horaci=? AND estado NOT IN ('cancelada','reprogramada')");
        $stmt->bind_param("sss", $codm, $nuevaFecha, $horaDb);
        $stmt->execute();
        $ocupado = (int)$stmt->get_result()->fetch_assoc()['n'] > 0;
        $stmt->close();
        if ($ocupado) {
            flash('error', 'El horario seleccionado no está disponible para el médico. Elige otro.');
            $mysqli->close();
            redirigir('panel_medico.php');
        }

        // Obtener seguro del paciente para calcular costo
        $stmt = $mysqli->prepare("SELECT seguro FROM paciente WHERE codp=? LIMIT 1");
        $stmt->bind_param("s", $cita['codp']);
        $stmt->execute();
        $pac = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $seguroPac = $pac['seguro'] ?? 'SIS';
        $costoNuevo = calcularCosto($seguroPac);

        // Marcar cita original como reprogramada y guardar notas/motivo
        $diagDb = $diagnostico !== '' ? $diagnostico : null;
        $indDb  = $indicaciones !== '' ? $indicaciones : null;
        $obsDb  = 'Reprogramada: ' . $motivoReprog . ' (nueva cita pendiente de generar código)';
        $estadoOrig = 'reprogramada';
        $stmt = $mysqli->prepare("UPDATE cita SET estado=?, diagnostico=?, indicaciones=?, observaciones=?, motivo_reprogramacion=? WHERE codci=? AND codm=?");
        $stmt->bind_param("sssssss", $estadoOrig, $diagDb, $indDb, $obsDb, $motivoReprog, $codci, $codm);
        $stmt->execute();
        $stmt->close();

        // Crear nueva cita con mismo médico, paciente y centro
        $nuevoCodci = generarCodigo($mysqli, 'cita', 'codci', 'CIT');
        $fechareg = date('Y-m-d H:i:s');
        $estadoNueva = 'pendiente';
        $stmt = $mysqli->prepare("INSERT INTO cita (codci, fechaci, horaci, motivo, estado, costo, codp, codm, codce, fechareg, observaciones, reprogramada_de) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        $obsNueva = 'Reprogramada desde ' . $codci . ' (' . $motivoReprog . ')';
        $stmt->bind_param("sssssdssssss", $nuevoCodci, $nuevaFecha, $horaDb, $motivoCitaNueva, $estadoNueva, $costoNuevo, $cita['codp'], $codm, $cita['codce'], $fechareg, $obsNueva, $codci);
        $okInsert = $stmt->execute();
        $stmt->close();

        // Actualizar observación original con código nuevo
        $obsFinal = 'Reprogramada a ' . $nuevoCodci . ' (' . $motivoReprog . ')';
        $stmt = $mysqli->prepare("UPDATE cita SET observaciones=? WHERE codci=?");
        $stmt->bind_param("ss", $obsFinal, $codci);
        $stmt->execute();
        $stmt->close();

        // Historial
        $stmt = $mysqli->prepare("INSERT INTO cita_historial (codci, accion, motivo, fecha, usuario_accion) VALUES (?, 'reprogramacion', ?, NOW(), ?)");
        $mh = 'Reprogramada a ' . $nuevoCodci . ' por: ' . $motivoReprog;
        $stmt->bind_param("sss", $codci, $mh, $codm);
        $stmt->execute();
        $stmt->close();
        $stmt = $mysqli->prepare("INSERT INTO cita_historial (codci, accion, motivo, fecha, usuario_accion) VALUES (?, 'creacion', ?, NOW(), ?)");
        $mh2 = 'Nueva cita creada desde ' . $codci . ' por: ' . $motivoReprog;
        $stmt->bind_param("sss", $nuevoCodci, $mh2, $codm);
        $stmt->execute();
        $stmt->close();

        if ($okInsert) {
            flash('exito', 'Cita ' . $codci . ' reprogramada correctamente. Nueva cita: ' . $nuevoCodci . ' (' . fechaTexto($nuevaFecha) . ' ' . horaTexto($horaDb) . ').');
        } else {
            flash('error', 'Se marcó la reprogramación, pero hubo un problema al crear la nueva cita.');
        }
        $mysqli->close();
        redirigir('panel_medico.php');
    }
}

// Listado de citas del médico
$stmt = $mysqli->prepare("
    SELECT c.codci, c.fechaci, c.horaci, c.estado, c.motivo, c.costo, c.observaciones,
           c.diagnostico, c.indicaciones, c.motivo_reprogramacion, c.reprogramada_de,
           p.codp, p.nomp, p.apep, p.dni, p.telp, p.seguro,
           ce.codce, ce.nomce, ce.distrito
    FROM cita c
    JOIN paciente p ON p.codp = c.codp
    JOIN centro ce ON ce.codce = c.codce
    WHERE c.codm = ?
    ORDER BY c.fechaci DESC, c.horaci DESC
");
$stmt->bind_param("s", $codm);
$stmt->execute();
$citas = $stmt->get_result();
$stmt->close();

$total = 0; $pendientes = 0; $atendidas = 0; $canceladas = 0; $extendidas = 0; $reprogramadas = 0;
$resCount = $mysqli->query("SELECT estado, COUNT(*) n FROM cita WHERE codm = '" . $mysqli->real_escape_string($codm) . "' GROUP BY estado");
while ($r = $resCount->fetch_assoc()) {
    $total += (int)$r['n'];
    $st = strtolower($r['estado']);
    if ($st === 'pendiente') $pendientes = (int)$r['n'];
    if ($st === 'atendida') $atendidas = (int)$r['n'];
    if ($st === 'cancelada') $canceladas = (int)$r['n'];
    if ($st === 'extendida') $extendidas = (int)$r['n'];
    if ($st === 'reprogramada') $reprogramadas = (int)$r['n'];
}

$mysqli->close();

$motivosReprog = [
    'Inasistencia del paciente',
    'Cancelación',
    'Seguimiento del tratamiento',
    'Evaluación de resultados de exámenes',
    'Error administrativo del sistema',
    'Otro'
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del médico — PrimeLab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="topbar">
    <a href="panel_medico.php" class="brand">
        <img src="img/PrimeLab%20Logo.png" alt="PrimeLab" class="brand-logo">
        <span class="brand-copy"><strong>PrimeLab</strong><small>Panel de médicos</small></span>
    </a>
    <div class="topbar-right"><div class="section-tag"><span>Sección actual</span><b>MÉDICO</b></div></div>
</header>

<div class="app-layout">
    <aside class="sidebar">
        <div class="user-chip">
            <div style="flex-shrink:0;"><?= fotoMedico($medico['codm'], $medico['nomm'], 44) ?></div>
            <div><strong><?= e($medico['nomm']) ?></strong><small>CMP <?= e($medico['cmpm']) ?></small></div>
        </div>
        <nav>
            <p class="nav-label">Médico</p>
            <a href="panel_medico.php" class="nav-btn active"><span class="ico">👨‍⚕️</span><span>Mi panel</span></a>
            <a href="panel_medico.php#consultas" class="nav-btn"><span class="ico">🩺</span><span>Consultas</span></a>
        </nav>
        <div class="account-actions">
            <a href="logout.php" class="nav-btn nav-btn-danger"><span class="ico">⎋</span><span>Cerrar sesión</span></a>
        </div>
    </aside>

    <main class="content">
        <?php mostrarFlash(); ?>

        <div class="page-heading">
            <p class="eyebrow">Perfil profesional</p>
            <h1>Bienvenido(a), <?= e($medico['nomm']) ?></h1>
            <p class="muted">Gestiona tus citas: registra atención, estado (atendida, cancelada o extendida), diagnóstico, indicaciones y reprogramaciones con el mismo médico.</p>
        </div>

        <section class="panel" style="margin-bottom:18px;">
            <h2 class="panel-title">Mi perfil</h2>
            <div class="medico-resumen" style="margin:12px 0 16px; max-width:720px;">
                <?= fotoMedico($medico['codm'], $medico['nomm'], 80) ?>
                <div>
                    <strong style="font-size:20px; display:block;"><?= e($medico['nomm']) ?></strong>
                    <span class="muted">Especialidad: <?= e($medico['nomesp']) ?></span>
                    <?php if (!empty($medico['formacion'])): ?>
                        <div class="muted" style="margin-top:4px;"><?= e($medico['formacion']) ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="data-grid">
                <div><span>Código</span><strong><?= e($medico['codm']) ?></strong></div>
                <div><span>CMP</span><strong><?= e($medico['cmpm']) ?></strong></div>
                <div><span>DNI</span><strong><?= e($medico['dni'] ?? '-') ?></strong></div>
                <div><span>Especialidad</span><strong><?= e($medico['nomesp']) ?></strong></div>
                <div><span>Experiencia</span><strong><?= isset($medico['anios_experiencia']) ? e($medico['anios_experiencia'] . ' años') : '-' ?></strong></div>
                <div><span>Teléfono</span><strong><?= e($medico['telefono'] ?? '-') ?></strong></div>
                <div><span>Correo</span><strong><?= e($medico['correo'] ?? '-') ?></strong></div>
            </div>
        </section>

        <section class="dashboard-overview">
            <article class="summary-card"><span class="summary-label">Citas totales</span><strong class="summary-count"><?= $total ?></strong><span class="summary-details">asignadas</span></article>
            <article class="summary-card"><span class="summary-label">Pendientes</span><strong class="summary-count"><?= $pendientes ?></strong><span class="summary-details">por atender</span></article>
            <article class="summary-card"><span class="summary-label">Atendidas</span><strong class="summary-count"><?= $atendidas ?></strong><span class="summary-details">completadas</span></article>
            <article class="summary-card"><span class="summary-label">Extendidas</span><strong class="summary-count"><?= $extendidas ?></strong><span class="summary-details">en seguimiento</span></article>
            <article class="summary-card"><span class="summary-label">Reprogramadas</span><strong class="summary-count"><?= $reprogramadas ?></strong><span class="summary-details">con nueva cita</span></article>
            <article class="summary-card"><span class="summary-label">Canceladas</span><strong class="summary-count"><?= $canceladas ?></strong><span class="summary-details">anuladas</span></article>
        </section>

        <section class="panel" id="consultas">
            <h2 class="panel-title">Gestión de citas y consultas</h2>
            <p class="muted" style="margin-bottom:14px;">Registra el estado, lo observado en el paciente, indicaciones/medicamentos y, si corresponde, reprograma una nueva cita con el mismo médico.</p>

            <?php if ($citas->num_rows === 0): ?>
                <p class="table-empty">No tienes citas asignadas.</p>
            <?php else: while ($c = $citas->fetch_assoc()): ?>
                <details class="cita-gestion-card" style="border:1px solid var(--line,#d8e2e0); border-radius:14px; padding:14px 16px; margin-bottom:12px; background:#fff;">
                    <summary style="cursor:pointer; list-style:none; display:flex; flex-wrap:wrap; gap:10px 18px; align-items:center; justify-content:space-between;">
                        <div>
                            <span class="code"><?= e($c['codci']) ?></span>
                            <strong style="margin-left:8px;"><?= e($c['nomp'] . ' ' . $c['apep']) ?></strong>
                            <span class="muted"> · DNI <?= e($c['dni']) ?> · <?= e(textoSeguro($c['seguro'] ?? 'SIS')) ?></span>
                        </div>
                        <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                            <span class="muted"><?= e(fechaTexto($c['fechaci'])) ?> <?= e(horaTexto($c['horaci'])) ?></span>
                            <?= badgeEstadoMed($c['estado']) ?>
                        </div>
                    </summary>

                    <div style="margin-top:14px; display:grid; gap:14px;">
                        <div class="data-grid">
                            <div><span>Paciente</span><strong><?= e($c['nomp'] . ' ' . $c['apep']) ?></strong></div>
                            <div><span>Teléfono</span><strong><?= $c['telp'] ? e($c['telp']) : 'No registrado' ?></strong></div>
                            <div><span>Seguro / Costo</span><strong><?= e(textoSeguro($c['seguro'] ?? 'SIS')) ?> — S/ <?= number_format((float)$c['costo'], 2) ?></strong></div>
                            <div><span>Centro</span><strong><?= e($c['nomce']) ?> (<?= e($c['distrito']) ?>)</strong></div>
                            <div style="grid-column:1/-1;"><span>Motivo de la cita</span><strong><?= e($c['motivo']) ?></strong></div>
                            <?php if (!empty($c['reprogramada_de'])): ?>
                                <div><span>Reprogramada desde</span><strong><?= e($c['reprogramada_de']) ?></strong></div>
                            <?php endif; ?>
                            <?php if (!empty($c['motivo_reprogramacion'])): ?>
                                <div><span>Motivo de reprogramación</span><strong><?= e($c['motivo_reprogramacion']) ?></strong></div>
                            <?php endif; ?>
                        </div>

                        <form method="POST" action="panel_medico.php" class="form-stack" style="border-top:1px solid var(--line,#e6eeee); padding-top:12px;">
                            <input type="hidden" name="accion" value="guardar_atencion">
                            <input type="hidden" name="codci" value="<?= e($c['codci']) ?>">
                            <h3 class="list-h" style="margin:0;">Registro de atención</h3>
                            <div class="form-grid-2">
                                <label class="field">
                                    <span>Estado de la cita</span>
                                    <select name="estado" required>
                                        <?php foreach (['pendiente','atendida','cancelada','extendida'] as $st): ?>
                                            <option value="<?= $st ?>" <?= strtolower($c['estado']) === $st ? 'selected' : '' ?>><?= ucfirst($st) ?></option>
                                        <?php endforeach; ?>
                                        <?php if (strtolower($c['estado']) === 'reprogramada'): ?>
                                            <option value="reprogramada" selected>Reprogramada</option>
                                        <?php endif; ?>
                                    </select>
                                </label>
                                <label class="field">
                                    <span>Observaciones generales</span>
                                    <input type="text" name="observaciones" maxlength="255" value="<?= e($c['observaciones'] ?? '') ?>" placeholder="Notas adicionales (opcional)">
                                </label>
                            </div>
                            <label class="field">
                                <span>Descripción de lo observado al paciente (diagnóstico/evaluación)</span>
                                <textarea name="diagnostico" rows="3" placeholder="Ej.: síntomas, hallazgos clínicos, evaluación..."><?= e($c['diagnostico'] ?? '') ?></textarea>
                            </label>
                            <label class="field">
                                <span>Indicaciones y medicamentos entregados/recetados</span>
                                <textarea name="indicaciones" rows="3" placeholder="Ej.: medicamentos, dosis, indicaciones, reposo, exámenes solicitados..."><?= e($c['indicaciones'] ?? '') ?></textarea>
                            </label>
                            <div class="ticket-actions">
                                <button type="submit" class="button primary">Guardar atención</button>
                            </div>
                        </form>

                        <form method="POST" action="panel_medico.php" class="form-stack" style="border-top:1px dashed var(--line,#d8e2e0); padding-top:12px; background:#f8fbfa; border-radius:10px; padding:12px;">
                            <input type="hidden" name="accion" value="reprogramar">
                            <input type="hidden" name="codci" value="<?= e($c['codci']) ?>">
                            <h3 class="list-h" style="margin:0;">Reprogramar cita (misma paciente y mismo médico)</h3>
                            <p class="muted" style="margin:0;">Crea una nueva cita con el mismo doctor. La cita actual se marcará como reprogramada.</p>
                            <div class="form-grid-2">
                                <label class="field">
                                    <span>Motivo de reprogramación</span>
                                    <select name="motivo_reprogramacion" required>
                                        <option value="">Selecciona un motivo</option>
                                        <?php foreach ($motivosReprog as $mr): ?>
                                            <option value="<?= e($mr) ?>"><?= e($mr) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>
                                <label class="field">
                                    <span>Motivo de la nueva cita</span>
                                    <input type="text" name="motivo_nueva_cita" maxlength="255" value="<?= e($c['motivo']) ?>">
                                </label>
                                <label class="field">
                                    <span>Nueva fecha</span>
                                    <input type="date" name="nueva_fecha" min="<?= date('Y-m-d') ?>" required>
                                </label>
                                <label class="field">
                                    <span>Nueva hora</span>
                                    <input type="time" name="nueva_hora" required>
                                </label>
                            </div>
                            <div class="form-grid-2">
                                <label class="field">
                                    <span>Diagnóstico/observación (opcional)</span>
                                    <input type="text" name="diagnostico" maxlength="255" value="<?= e(mb_substr((string)($c['diagnostico'] ?? ''),0,255)) ?>" placeholder="Resumen breve">
                                </label>
                                <label class="field">
                                    <span>Indicaciones/medicamentos (opcional)</span>
                                    <input type="text" name="indicaciones" maxlength="255" value="<?= e(mb_substr((string)($c['indicaciones'] ?? ''),0,255)) ?>" placeholder="Resumen breve">
                                </label>
                            </div>
                            <div class="ticket-actions">
                                <button type="submit" class="button secondary" onclick="return confirm('¿Reprogramar esta cita y crear una nueva con el mismo médico?');">Reprogramar y crear nueva cita</button>
                            </div>
                        </form>
                    </div>
                </details>
            <?php endwhile; endif; ?>
        </section>
    </main>
</div>

<footer class="site-footer"><p>PrimeLab — Panel de médicos</p></footer>
<script src="js/main.js"></script>
</body>
</html>
