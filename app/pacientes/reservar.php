<?php
// reservar.php — Reserva de cita en 3 pasos.
require_once __DIR__ . '/../core/file_conexion.php';
requerirPaciente();

$mysqli = Conectarse();
$paciente = pacienteActual($mysqli);
if (!$paciente) { $mysqli->close(); redirigir("index.html?msg=sesion"); }

$codp = $paciente['codp'];
$paso = isset($_GET['paso']) ? (int)$_GET['paso'] : 1;
if ($paso < 1 || $paso > 3) $paso = 1;

// Horarios disponibles (bloques fijos)
$HORARIOS = ['08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','14:00','14:30','15:00','15:30','16:00','16:30'];

function fechaReservable(string $fecha): bool
{
    if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/D', $fecha, $partes)) return false;
    return checkdate((int)$partes[2], (int)$partes[3], (int)$partes[1]) && $fecha >= date('Y-m-d');
}

// Confirmación (POST desde paso 3)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'confirmar') {
    $centro = trim($_POST['centro'] ?? '');
    $esp    = trim($_POST['esp'] ?? '');
    $med    = trim($_POST['med'] ?? '');
    $fecha  = trim($_POST['fecha'] ?? '');
    $hora   = trim($_POST['hora'] ?? '');
    $motivo = trim($_POST['motivo'] ?? '');

    $base = "reservar.php?paso=3&centro=" . urlencode($centro) . "&esp=" . urlencode($esp) . "&med=" . urlencode($med) . "&fecha=" . urlencode($fecha) . "&hora=" . urlencode($hora);

    if ($centro === '' || $esp === '' || $med === '' || $fecha === '' || $hora === '' || $motivo === '') {
        $mysqli->close();
        redirigir($base . "&error=campos");
    }
    if (!fechaReservable($fecha) || !in_array($hora, $HORARIOS, true)) {
        $mysqli->close();
        redirigir("reservar.php?paso=2&centro=" . urlencode($centro) . "&esp=" . urlencode($esp) . "&med=" . urlencode($med) . "&error=horario");
    }
    if (mb_strlen($motivo, 'UTF-8') > 255) {
        $mysqli->close();
        redirigir($base . "&error=motivo");
    }

    // Comprueba que la especialidad se ofrece en el centro y coincide con la del médico.
    $stmt = $mysqli->prepare("SELECT 1 FROM centro_especialidad ce JOIN medico m ON m.codesp = ce.codesp WHERE ce.codce = ? AND ce.codesp = ? AND m.codm = ? LIMIT 1");
    if (!$stmt) { $mysqli->close(); redirigir($base . "&error=sistema"); }
    $stmt->bind_param("sss", $centro, $esp, $med);
    $okSeleccion = $stmt->execute() && $stmt->get_result()->num_rows > 0;
    $stmt->close();
    if (!$okSeleccion) {
        $mysqli->close();
        redirigir("reservar.php?paso=2&centro=" . urlencode($centro) . "&error=seleccion");
    }

    // Validar que el horario siga libre para el médico/fecha
    $stmt = $mysqli->prepare("SELECT COUNT(*) AS n FROM cita WHERE codm = ? AND fechaci = ? AND horaci = ? AND estado <> 'cancelada'");
    if (!$stmt) { $mysqli->close(); redirigir($base . "&error=sistema"); }
    $horaDb = $hora . ':00';
    $stmt->bind_param("sss", $med, $fecha, $horaDb);
    if (!$stmt->execute()) { $stmt->close(); $mysqli->close(); redirigir($base . "&error=sistema"); }
    $ocupado = (int)$stmt->get_result()->fetch_assoc()['n'] > 0;
    $stmt->close();

    if ($ocupado) {
        $mysqli->close();
        redirigir("reservar.php?paso=2&centro=" . urlencode($centro) . "&esp=" . urlencode($esp) . "&error=nohorario");
    }

    $codci = generarCodigo($mysqli, 'cita', 'codci', 'CIT');
    $fechareg = date('Y-m-d H:i:s');
    $estado = 'pendiente';
    $seguroPaciente = $paciente['seguro'] ?? 'SIS';
    $costo = calcularCosto($seguroPaciente);

    $stmt = $mysqli->prepare("INSERT INTO cita (codci, fechaci, horaci, motivo, estado, costo, codp, codm, codce, fechareg) VALUES (?,?,?,?,?,?,?,?,?,?)");
    if (!$stmt) { $mysqli->close(); redirigir($base . "&error=sistema"); }
    $stmt->bind_param("sssssdssss", $codci, $fecha, $horaDb, $motivo, $estado, $costo, $codp, $med, $centro, $fechareg);
    $ok = $stmt->execute();
    $stmt->close();
    $mysqli->close();

    if (!$ok) {
        redirigir($base . "&error=sistema");
    }
    redirigir("ticket.php?codigo=" . urlencode($codci) . "&ok=creada");
}

// Datos comunes
$nombreCompleto = $paciente['nomp'] . ' ' . $paciente['apep'];
$inicial = mb_strtoupper(mb_substr($paciente['nomp'], 0, 1, 'UTF-8'), 'UTF-8');

// Paso 1: filtros de centros
$q = trim($_GET['q'] ?? '');
$fDistrito = trim($_GET['distrito'] ?? '');
$fAtencion = trim($_GET['atencion'] ?? ''); // especialidad

// Listas para filtros
$distritos = [];
$resD = $mysqli->query("SELECT DISTINCT distrito FROM centro ORDER BY distrito");
while ($r = $resD->fetch_assoc()) $distritos[] = $r['distrito'];

$atenciones = [];
$resA = $mysqli->query("SELECT codesp, nomesp FROM especialidad ORDER BY nomesp");
while ($r = $resA->fetch_assoc()) $atenciones[] = $r;

// Consulta de centros (paso 1)
$centros = null;
if ($paso === 1) {
    $sql = "SELECT c.codce, c.nomce, c.dirc, c.distrito, c.provincia, c.departamento, c.telefono, c.horario_atencion, c.tipo,
                   GROUP_CONCAT(DISTINCT e.nomesp ORDER BY e.nomesp SEPARATOR ', ') AS especialidades
            FROM centro c
            LEFT JOIN centro_especialidad ce ON ce.codce = c.codce
            LEFT JOIN especialidad e ON e.codesp = ce.codesp
            WHERE 1=1";
    $params = []; $types = '';
    if ($q !== '') {
        $sql .= " AND (c.nomce LIKE ? OR c.distrito LIKE ? OR c.dirc LIKE ?)";
        $like = '%' . $q . '%';
        $params[] = $like; $params[] = $like; $params[] = $like; $types .= 'sss';
    }
    if ($fDistrito !== '') {
        $sql .= " AND c.distrito = ?";
        $params[] = $fDistrito; $types .= 's';
    }
    if ($fAtencion !== '') {
        $sql .= " AND EXISTS (SELECT 1 FROM centro_especialidad ce2 WHERE ce2.codce = c.codce AND ce2.codesp = ?)";
        $params[] = $fAtencion; $types .= 's';
    }
    $sql .= " GROUP BY c.codce, c.nomce, c.dirc, c.distrito, c.provincia, c.departamento, c.telefono, c.horario_atencion, c.tipo ORDER BY c.nomce";
    $stmt = $mysqli->prepare($sql);
    if ($types !== '') $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $centros = $stmt->get_result();
    $stmt->close();
}

// Paso 2 y 3: datos seleccionados
$centroSel = trim($_GET['centro'] ?? '');
$espSel    = trim($_GET['esp'] ?? '');
$medSel    = trim($_GET['med'] ?? '');
$fechaSel  = trim($_GET['fecha'] ?? date('Y-m-d'));
$horaSel   = trim($_GET['hora'] ?? '');
if (!fechaReservable($fechaSel)) {
    $fechaSel = date('Y-m-d');
    $horaSel = '';
}

$centroInfo = null; $espsCentro = null; $medicos = null; $espInfo = null; $medInfo = null;

if ($paso >= 2 && $centroSel !== '') {
    $stmt = $mysqli->prepare("SELECT codce, nomce, dirc, distrito, provincia, departamento, telefono, horario_atencion, tipo FROM centro WHERE codce = ? LIMIT 1");
    $stmt->bind_param("s", $centroSel);
    $stmt->execute();
    $centroInfo = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($centroInfo) {
        $stmt = $mysqli->prepare("
            SELECT e.codesp, e.nomesp,
                   (SELECT COUNT(*) FROM medico m WHERE m.codesp = e.codesp) AS medicos
            FROM especialidad e
            JOIN centro_especialidad ce ON ce.codesp = e.codesp
            WHERE ce.codce = ?
            ORDER BY e.nomesp
        ");
        $stmt->bind_param("s", $centroSel);
        $stmt->execute();
        $espsCentro = $stmt->get_result();
        $stmt->close();
    }
}

if ($paso >= 2 && $centroInfo && $espSel !== '') {
    $stmt = $mysqli->prepare("SELECT e.codesp, e.nomesp FROM especialidad e JOIN centro_especialidad ce ON ce.codesp = e.codesp WHERE e.codesp = ? AND ce.codce = ? LIMIT 1");
    $stmt->bind_param("ss", $espSel, $centroSel);
    $stmt->execute();
    $espInfo = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($espInfo) {
        $stmt = $mysqli->prepare("SELECT codm, nomm, cmpm FROM medico WHERE codesp = ? ORDER BY nomm");
        $stmt->bind_param("s", $espSel);
        $stmt->execute();
        $medicos = $stmt->get_result();
        $stmt->close();
    }
}

if ($espInfo && $medSel !== '') {
    $stmt = $mysqli->prepare("SELECT codm, nomm, cmpm FROM medico WHERE codm = ? AND codesp = ? LIMIT 1");
    $stmt->bind_param("ss", $medSel, $espSel);
    $stmt->execute();
    $medInfo = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Horarios ocupados para médico+fecha seleccionados (paso 2/3)
$ocupados = [];
if ($medInfo) {
    $stmt = $mysqli->prepare("SELECT horaci FROM cita WHERE codm = ? AND fechaci = ? AND estado <> 'cancelada'");
    $stmt->bind_param("ss", $medSel, $fechaSel);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($r = $res->fetch_assoc()) $ocupados[] = substr($r['horaci'], 0, 5);
    $stmt->close();
}

$mysqli->close();

if ($paso >= 2 && !$centroInfo) {
    redirigir('reservar.php?paso=1&error=centro');
}
if ($paso === 3 && (!$espInfo || !$medInfo || !in_array($horaSel, $HORARIOS, true) || in_array($horaSel, $ocupados, true))) {
    $paso = 2;
}

function pasoClase($n, $actual) {
    if ($n < $actual) return 'step done';
    if ($n === $actual) return 'step active';
    return 'step';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar cita — PrimeLab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body data-page="reservar">
<header class="topbar">
    <a href="panel.php" class="brand">
        <img src="img/PrimeLab%20Logo.png" alt="PrimeLab" class="brand-logo">
        <span class="brand-copy"><strong>PrimeLab</strong><small>Centros de salud públicos del Perú</small></span>
    </a>
    <div class="topbar-right"><div class="section-tag"><span>Sección actual</span><b>RESERVAR</b></div></div>
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
            <a href="reservar.php" class="nav-btn active"><span class="ico">📅</span><span>Reservar cita</span></a>
            <a href="mis_citas.php" class="nav-btn"><span class="ico">🗂</span><span>Mis citas</span></a>
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
            <p class="eyebrow">Reserva</p>
            <h1>Reservar una cita</h1>
            <p class="muted">Selecciona el centro, la especialidad, el médico y el horario.</p>
        </div>

        <div class="steps">
            <div class="<?= pasoClase(1,$paso) ?>"><span class="step-dot">1</span><span>Centro</span></div>
            <div class="<?= pasoClase(2,$paso) ?>"><span class="step-dot">2</span><span>Especialidad y horario</span></div>
            <div class="<?= pasoClase(3,$paso) ?>"><span class="step-dot">3</span><span>Confirmación</span></div>
        </div>

        <?php if ($paso === 1): ?>
            <form method="GET" class="filter-bar" action="reservar.php">
                <input type="hidden" name="paso" value="1">
                <label class="field">
                    <span>Nombre o distrito</span>
                    <input type="text" name="q" value="<?= e($q) ?>" placeholder="Ejemplo: San Martín o Cercado de Lima">
                </label>
                <label class="field">
                    <span>Distrito</span>
                    <select name="distrito">
                        <option value="">Todos los distritos</option>
                        <?php foreach ($distritos as $d): ?>
                            <option value="<?= e($d) ?>" <?= $fDistrito === $d ? 'selected' : '' ?>><?= e($d) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label class="field">
                    <span>Tipo de atención</span>
                    <select name="atencion">
                        <option value="">Todas las atenciones</option>
                        <?php foreach ($atenciones as $a): ?>
                            <option value="<?= e($a['codesp']) ?>" <?= $fAtencion === $a['codesp'] ? 'selected' : '' ?>><?= e($a['nomesp']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <button type="submit" class="button primary">Buscar</button>
            </form>

            <div class="center-grid">
                <?php if ($centros && $centros->num_rows > 0): ?>
                    <?php while ($c = $centros->fetch_assoc()): ?>
                        <article class="center-card">
                            <div class="center-icon">🏥</div>
                            <h3><?= e($c['nomce']) ?></h3>
                            <p class="muted"><?= e($c['dirc']) ?></p>
                            <p class="muted"><?= e($c['distrito']) ?>, <?= e($c['provincia']) ?> - <?= e($c['departamento']) ?></p>
                            <p class="muted"><strong>Tipo:</strong> <?= e($c['tipo']) ?> · <strong>Tel:</strong> <?= e($c['telefono']) ?></p>
                            <p class="muted"><strong>Horario:</strong> <?= e($c['horario_atencion']) ?></p>
                            <p><small><strong>Especialidades:</strong> <?= e($c['especialidades'] ?: 'Sin especialidades registradas') ?></small></p>
                            <a class="button secondary full" href="reservar.php?paso=2&centro=<?= e($c['codce']) ?>">Elegir este centro</a>
                        </article>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="muted">No se encontraron centros con los filtros seleccionados.</p>
                <?php endif; ?>
            </div>

        <?php elseif ($paso === 2 && $centroInfo): ?>
            <div class="selection-box">
                <strong>Centro seleccionado:</strong> <?= e($centroInfo['nomce']) ?> — <?= e($centroInfo['distrito']) ?> (<?= e($centroInfo['provincia']) ?>)<br>
                <span class="muted"><?= e($centroInfo['dirc']) ?> · <?= e($centroInfo['tipo']) ?> · Horario: <?= e($centroInfo['horario_atencion']) ?> · Tel: <?= e($centroInfo['telefono']) ?></span>
            </div>

            <div class="two-col">
                <section class="panel">
                    <h2 class="panel-title">Especialidad</h2>
                    <form method="GET" action="reservar.php">
                        <input type="hidden" name="paso" value="2">
                        <input type="hidden" name="centro" value="<?= e($centroSel) ?>">
                        <label class="field compact-field">
                            <span>Selecciona una especialidad</span>
                            <select name="esp" onchange="this.form.submit()">
                                <option value="">Selecciona una especialidad</option>
                                <?php if ($espsCentro): while ($x = $espsCentro->fetch_assoc()): ?>
                                    <option value="<?= e($x['codesp']) ?>" <?= $espSel === $x['codesp'] ? 'selected' : '' ?>>
                                        <?= e($x['nomesp']) ?> — <?= (int)$x['medicos'] > 0 ? 'Disponible' : 'No disponible' ?>
                                    </option>
                                <?php endwhile; endif; ?>
                            </select>
                        </label>
                    </form>

                    <?php if ($espSel !== '' && $medicos): ?>
                        <h3 class="list-h">Médicos disponibles</h3>
                        <form method="GET" action="reservar.php" class="form-stack">
                            <input type="hidden" name="paso" value="2">
                            <input type="hidden" name="centro" value="<?= e($centroSel) ?>">
                            <input type="hidden" name="esp" value="<?= e($espSel) ?>">
                            <div class="doctor-list">
                                <?php $hay = false; while ($m = $medicos->fetch_assoc()): $hay = true; ?>
                                    <label class="doctor-card <?= $medSel === $m['codm'] ? 'selected' : '' ?>">
                                        <input type="radio" name="med" value="<?= e($m['codm']) ?>" <?= $medSel === $m['codm'] ? 'checked' : '' ?> onchange="this.form.submit()">
                                        <?= fotoMedico($m['codm'], $m['nomm'], 56) ?>
                                        <div class="doctor-info">
                                            <strong><?= e($m['nomm']) ?></strong>
                                            <small>CMP <?= e($m['cmpm']) ?></small>
                                        </div>
                                    </label>
                                <?php endwhile; if (!$hay): ?>
                                    <p class="muted">No hay médicos registrados para esta especialidad.</p>
                                <?php endif; ?>
                            </div>
                            <label class="field">
                                <span>Fecha de la cita</span>
                                <input type="date" name="fecha" value="<?= e($fechaSel) ?>" min="<?= date('Y-m-d') ?>" onchange="this.form.submit()">
                            </label>
                        </form>
                    <?php else: ?>
                        <p class="muted" style="margin-top:12px;">Selecciona una especialidad para ver los médicos disponibles.</p>
                    <?php endif; ?>
                </section>

                <section class="panel">
                    <h2 class="panel-title">Horarios</h2>
                    <?php if ($medSel !== '' && $medInfo): ?>
                        <div class="medico-resumen">
                            <?= fotoMedico($medInfo['codm'], $medInfo['nomm'], 48) ?>
                            <p class="muted" style="margin:0;"><strong><?= e($medInfo['nomm']) ?></strong><br><?= e(fechaTexto($fechaSel)) ?></p>
                        </div>
                        <form method="GET" action="reservar.php" class="form-stack">
                            <input type="hidden" name="paso" value="3">
                            <input type="hidden" name="centro" value="<?= e($centroSel) ?>">
                            <input type="hidden" name="esp" value="<?= e($espSel) ?>">
                            <input type="hidden" name="med" value="<?= e($medSel) ?>">
                            <input type="hidden" name="fecha" value="<?= e($fechaSel) ?>">
                            <div class="slot-grid">
                                <?php foreach ($HORARIOS as $h): $libre = !in_array($h, $ocupados, true); ?>
                                    <label class="slot <?= $libre ? '' : 'disabled' ?> <?= ($horaSel === $h) ? 'selected' : '' ?>">
                                        <input type="radio" name="hora" value="<?= e($h) ?>" <?= $libre ? '' : 'disabled' ?> <?= ($horaSel === $h) ? 'checked' : '' ?> required>
                                        <span><?= e($h) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                            <div class="slot-legend">
                                <span><i class="dot dot-free"></i> Disponible</span>
                                <span><i class="dot dot-off"></i> Ocupado</span>
                            </div>
                            <p id="mensajeHorario" class="muted" aria-live="polite">
                                    Selecciona un horario para continuar.
                            </p>

                            <button type="submit" class="button primary full"
                                    id="btnContinuar" disabled>
                                Continuar
                            </button>
                        </form>
                    <?php else: ?>
                        <p class="muted">Selecciona especialidad, médico y fecha para ver los horarios.</p>
                    <?php endif; ?>
                </section>
            </div>

            <p style="margin-top:14px;"><a href="reservar.php?paso=1" class="link-muted">← Volver a elegir centro</a></p>

        <?php elseif ($paso === 3 && $centroInfo && $espInfo && $medInfo): ?>
            <section class="panel">
                <h2 class="panel-title">Confirma tu cita</h2>
                <p class="muted">Revisa los datos antes de registrar la reserva.</p>

                <h3 class="list-h">Datos del paciente</h3>
                <div class="data-grid">
                    <div><span>DNI</span><strong><?= e($paciente['dni']) ?></strong></div>
                    <div><span>Paciente</span><strong><?= e($nombreCompleto) ?></strong></div>
                    <div><span>Teléfono</span><strong><?= $paciente['telp'] ? e($paciente['telp']) : 'No registrado' ?></strong></div>
                    <div><span>Correo</span><strong><?= $paciente['correo'] ? e($paciente['correo']) : 'Sin correo registrado' ?></strong></div>
                    <div><span>Seguro</span><strong><?= e(textoSeguro($paciente['seguro'] ?? 'SIS')) ?></strong></div>
                    <div><span>Costo de la cita</span><strong><?= e(costoTexto($paciente['seguro'] ?? 'SIS')) ?></strong></div>
                </div>

                <h3 class="list-h">Detalle de la cita</h3>
                <div class="summary-list">
                    <div><span>Centro</span><strong><?= e($centroInfo['nomce']) ?> — <?= e($centroInfo['distrito']) ?></strong></div>
                    <div><span>Especialidad</span><strong><?= e($espInfo['nomesp']) ?></strong></div>
                    <div><span>Médico</span><strong class="medico-inline"><?= fotoMedico($medInfo['codm'], $medInfo['nomm'], 40) ?><span><?= e($medInfo['nomm']) ?> (CMP <?= e($medInfo['cmpm']) ?>)</span></strong></div>
                    <div><span>Fecha</span><strong><?= e(fechaTexto($fechaSel)) ?></strong></div>
                    <div><span>Hora</span><strong><?= e($horaSel) ?></strong></div>
                    <div><span>Seguro / Costo</span><strong><?= e(textoSeguro($paciente['seguro'] ?? 'SIS')) ?> — <?= e(costoTexto($paciente['seguro'] ?? 'SIS')) ?></strong></div>
                </div>

                <form method="POST" action="reservar.php" class="form-stack" style="margin-top:18px;">
                    <input type="hidden" name="accion" value="confirmar">
                    <input type="hidden" name="centro" value="<?= e($centroSel) ?>">
                    <input type="hidden" name="esp" value="<?= e($espSel) ?>">
                    <input type="hidden" name="med" value="<?= e($medSel) ?>">
                    <input type="hidden" name="fecha" value="<?= e($fechaSel) ?>">
                    <input type="hidden" name="hora" value="<?= e($horaSel) ?>">
                    <label class="field">
                        <span>Motivo de la consulta</span>
                        <textarea name="motivo" rows="3" maxlength="255" required placeholder="Describe brevemente el motivo de tu cita"></textarea>
                    </label>
                    <div class="ticket-actions">
                        <button type="submit" class="button primary">Confirmar reserva</button>
                        <a href="reservar.php?paso=2&centro=<?= e($centroSel) ?>&esp=<?= e($espSel) ?>&med=<?= e($medSel) ?>&fecha=<?= e($fechaSel) ?>" class="button secondary">Volver</a>
                    </div>
                </form>
            </section>
        <?php endif; ?>
    </main>
</div>

<footer class="site-footer"><p>PrimeLab — Sistema de reserva de citas en centros de salud públicos</p></footer>
<script src="js/main.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const boton = document.getElementById('btnContinuar');
    const mensaje = document.getElementById('mensajeHorario');

    if (!boton || !mensaje) return;

    const formulario = boton.closest('form');

    function actualizarHorario() {
        const seleccionado = formulario.querySelector(
            'input[name="hora"]:checked:not(:disabled)'
        );

        boton.disabled = !seleccionado;

        mensaje.textContent = seleccionado
            ? 'Horario seleccionado: ' + seleccionado.value
            : 'Selecciona un horario para continuar.';
    }

    formulario.addEventListener('change', actualizarHorario);

    actualizarHorario();
});
</script>
</body>
</html>
