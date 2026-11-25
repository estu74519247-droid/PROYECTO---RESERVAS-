<?php
// admin_consultas.php — Consultas médicas registradas en el sistema.
require_once __DIR__ . '/../core/file_conexion.php';
requerirAdmin();
$mysqli = Conectarse();

$q       = trim($_GET['q'] ?? '');
$fEsp    = trim($_GET['esp'] ?? '');
$fEstado = trim($_GET['estado'] ?? '');

$especialidades = [];
$res = $mysqli->query("SELECT codesp, nomesp FROM especialidad ORDER BY nomesp");
while ($r = $res->fetch_assoc()) $especialidades[] = $r;

$sql = "
    SELECT c.codci, c.fechaci, c.horaci, c.estado, c.motivo, c.costo, c.fechareg,
           c.observaciones, c.diagnostico, c.indicaciones, c.motivo_reprogramacion, c.reprogramada_de,
           p.nomp, p.apep, p.dni, p.seguro,
           ce.nomce, ce.distrito,
           e.codesp, e.nomesp,
           m.codm, m.nomm, m.cmpm
    FROM cita c
    JOIN paciente p ON p.codp = c.codp
    JOIN centro ce ON ce.codce = c.codce
    JOIN medico m ON m.codm = c.codm
    JOIN especialidad e ON e.codesp = m.codesp
    WHERE 1=1
";
$params = []; $types = '';

if ($q !== '') {
    $like = '%' . $q . '%';
    $sql .= " AND (c.codci LIKE ? OR p.nomp LIKE ? OR p.apep LIKE ? OR p.dni LIKE ?
                   OR m.nomm LIKE ? OR e.nomesp LIKE ? OR ce.nomce LIKE ?
                   OR c.diagnostico LIKE ? OR c.indicaciones LIKE ?)";
    for ($i = 0; $i < 9; $i++) { $params[] = $like; $types .= 's'; }
}
if ($fEsp !== '') {
    $sql .= " AND e.codesp = ?";
    $params[] = $fEsp; $types .= 's';
}
if ($fEstado !== '') {
    $sql .= " AND c.estado = ?";
    $params[] = $fEstado; $types .= 's';
}
$sql .= " ORDER BY c.fechaci DESC, c.horaci DESC";

$stmt = $mysqli->prepare($sql);
if ($types !== '') $stmt->bind_param($types, ...$params);
$stmt->execute();
$resultado = $stmt->get_result();
$stmt->close();

$consultas = [];
while ($r = $resultado->fetch_assoc()) $consultas[] = $r;

$total = count($consultas);
$atendidas = 0;
$pendientes = 0;
$conDetalle = 0;
foreach ($consultas as $c) {
    if ($c['estado'] === 'atendida') $atendidas++;
    if ($c['estado'] === 'pendiente') $pendientes++;
    if (!empty($c['diagnostico']) || !empty($c['indicaciones'])) $conDetalle++;
}

$mysqli->close();

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
    <title>Consultas — PrimeLab Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css?v=celeste-azul">
</head>
<body>
<header class="topbar">
    <a href="admin_citas.php" class="brand">
        <img src="img/PrimeLab%20Logo.png" alt="PrimeLab" class="brand-logo">
        <span class="brand-copy"><strong>PrimeLab</strong><small>Administración</small></span>
    </a>
    <div class="topbar-right"><div class="section-tag"><span>Sección actual</span><b>ADMIN · CONSULTAS</b></div></div>
</header>

<div class="app-layout">
    <aside class="sidebar">
        <div class="user-chip"><div class="avatar">A</div><div><strong>Administrador</strong><small>Panel de control</small></div></div>
        <nav>
            <p class="nav-label">Administración</p>
            <a href="admin_citas.php" class="nav-btn"><span>Citas</span></a>
            <a href="admin_consultas.php" class="nav-btn active"><span>Consultas</span></a>
            <a href="admin_medicos.php" class="nav-btn"><span>Médicos</span></a>
            <a href="admin_centros.php" class="nav-btn"><span>Centros y especialidades</span></a>
        </nav>
        <div class="account-actions"><a href="logout.php" class="nav-btn nav-btn-danger"><span>Cerrar sesión</span></a></div>
    </aside>

    <main class="content">
        <?php mostrarFlash(); ?>

        <div class="page-heading">
            <p class="eyebrow">Panel administrativo</p>
            <h1>Consultas médicas</h1>
            <p class="muted">Revisa las atenciones, diagnósticos e indicaciones registradas por los médicos.</p>
        </div>

        <section class="dashboard-overview">
            <article class="summary-card"><span class="summary-label">Consultas</span><strong class="summary-count"><?= $total ?></strong><span class="summary-details">con los filtros actuales</span></article>
            <article class="summary-card"><span class="summary-label">Atendidas</span><strong class="summary-count"><?= $atendidas ?></strong><span class="summary-details">completadas</span></article>
            <article class="summary-card"><span class="summary-label">Pendientes</span><strong class="summary-count"><?= $pendientes ?></strong><span class="summary-details">por atender</span></article>
            <article class="summary-card"><span class="summary-label">Con detalle clínico</span><strong class="summary-count"><?= $conDetalle ?></strong><span class="summary-details">diagnóstico o indicaciones</span></article>
        </section>

        <form method="GET" action="admin_consultas.php" class="filter-bar">
            <label class="field">
                <span>Buscar</span>
                <input type="text" name="q" value="<?= e($q) ?>" placeholder="Paciente, DNI, médico, código o diagnóstico">
            </label>
            <label class="field">
                <span>Especialidad</span>
                <select name="esp">
                    <option value="">Todas</option>
                    <?php foreach ($especialidades as $esp): ?>
                        <option value="<?= e($esp['codesp']) ?>" <?= $fEsp === $esp['codesp'] ? 'selected' : '' ?>><?= e($esp['nomesp']) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label class="field">
                <span>Estado</span>
                <select name="estado">
                    <option value="">Todos</option>
                    <?php foreach (['pendiente','atendida','extendida','reprogramada','cancelada'] as $est): ?>
                        <option value="<?= e($est) ?>" <?= $fEstado === $est ? 'selected' : '' ?>><?= e(ucfirst($est)) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <button type="submit" class="button primary">Buscar</button>
        </form>

        <section class="panel">
            <div class="panel-head">
                <h2 class="panel-title">Listado de consultas</h2>
                <a href="admin_citas.php" class="button secondary">Ir a citas</a>
            </div>

            <?php if (!$consultas): ?>
                <p class="table-empty">No se encontraron consultas con los filtros seleccionados.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Fecha y hora</th>
                                <th>Paciente</th>
                                <th>Especialidad / Médico</th>
                                <th>Centro</th>
                                <th>Estado</th>
                                <th>Detalle clínico</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($consultas as $c): ?>
                            <tr>
                                <td><span class="code"><?= e($c['codci']) ?></span></td>
                                <td><?= e(fechaTexto($c['fechaci'])) ?><br><small class="muted"><?= e(horaTexto($c['horaci'])) ?></small></td>
                                <td><strong><?= e($c['nomp'] . ' ' . $c['apep']) ?></strong><br><small class="muted">DNI <?= e($c['dni']) ?> · <?= e(textoSeguro($c['seguro'])) ?></small></td>
                                <td><?= e($c['nomesp']) ?><br><small class="muted"><?= e($c['nomm']) ?> · CMP <?= e($c['cmpm']) ?></small></td>
                                <td><?= e($c['nomce']) ?><br><small class="muted"><?= e($c['distrito']) ?></small></td>
                                <td><?= badgeEstado($c['estado']) ?></td>
                                <td>
                                    <?php if (!empty($c['diagnostico']) || !empty($c['indicaciones'])): ?>
                                        <details class="consulta-detalle">
                                            <summary>Ver detalle clínico</summary>
                                            <?php if (!empty($c['diagnostico'])): ?>
                                                <p><strong>Diagnóstico:</strong><br><?= nl2br(e($c['diagnostico'])) ?></p>
                                            <?php endif; ?>
                                            <?php if (!empty($c['indicaciones'])): ?>
                                                <p><strong>Indicaciones:</strong><br><?= nl2br(e($c['indicaciones'])) ?></p>
                                            <?php endif; ?>
                                            <?php if (!empty($c['observaciones'])): ?>
                                                <p><strong>Observaciones:</strong><br><?= nl2br(e($c['observaciones'])) ?></p>
                                            <?php endif; ?>
                                        </details>
                                    <?php else: ?>
                                        <small class="muted">Sin detalle clínico registrado.</small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </main>
</div>

<footer class="site-footer"><p>PrimeLab — Sistema de reserva de citas en centros de salud públicos</p></footer>
<script src="js/main.js"></script>
</body>
</html>
