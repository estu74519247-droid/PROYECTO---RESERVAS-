<?php
// admin_citas.php — Administración de citas.
require_once __DIR__ . '/../core/file_conexion.php';
requerirAdmin();
$mysqli = Conectarse();

// Acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $codci  = trim($_POST['codci'] ?? '');
    if ($accion === 'cancelar' && $codci !== '') {
        $motivo = trim($_POST['motivo'] ?? 'Cancelada por administración.');
        $stmt = $mysqli->prepare("UPDATE cita SET estado = 'cancelada' WHERE codci = ?");
        $stmt->bind_param("s", $codci);
        $stmt->execute();
        $stmt->close();
        $stmt = $mysqli->prepare("INSERT INTO cita_historial (codci, accion, motivo, fecha) VALUES (?, 'cancelacion', ?, NOW())");
        $stmt->bind_param("ss", $codci, $motivo);
        $stmt->execute();
        $stmt->close();
        flash('exito', 'La cita ' . $codci . ' fue cancelada.');
        redirigir("admin_citas.php");
    }
    if ($accion === 'atender' && $codci !== '') {
        $stmt = $mysqli->prepare("UPDATE cita SET estado = 'atendida' WHERE codci = ?");
        $stmt->bind_param("s", $codci);
        $stmt->execute();
        $stmt->close();
        flash('exito', 'La cita ' . $codci . ' fue marcada como atendida.');
        redirigir("admin_citas.php");
    }
}

$fEstado = trim($_GET['estado'] ?? '');
$fCentro = trim($_GET['centro'] ?? '');

$centros = [];
$res = $mysqli->query("SELECT codce, nomce FROM centro ORDER BY nomce");
while ($r = $res->fetch_assoc()) $centros[] = $r;

$sql = "SELECT c.codci, c.fechaci, c.horaci, c.estado, c.motivo, c.costo,
               p.nomp, p.apep, p.dni, p.seguro,
               ce.nomce, e.nomesp, m.codm, m.nomm
        FROM cita c
        JOIN paciente p ON p.codp = c.codp
        JOIN centro ce ON ce.codce = c.codce
        JOIN medico m ON m.codm = c.codm
        JOIN especialidad e ON e.codesp = m.codesp
        WHERE 1=1";
$params = []; $types = '';
if ($fEstado !== '') { $sql .= " AND c.estado = ?"; $params[] = $fEstado; $types .= 's'; }
if ($fCentro !== '') { $sql .= " AND c.codce = ?"; $params[] = $fCentro; $types .= 's'; }
$sql .= " ORDER BY c.fechaci DESC, c.horaci DESC";
$stmt = $mysqli->prepare($sql);
if ($types !== '') $stmt->bind_param($types, ...$params);
$stmt->execute();
$citas = $stmt->get_result();
$stmt->close();

$statTotal = (int)$mysqli->query("SELECT COUNT(*) n FROM cita")->fetch_assoc()['n'];
$statPend  = (int)$mysqli->query("SELECT COUNT(*) n FROM cita WHERE estado='pendiente'")->fetch_assoc()['n'];
$statAtend = (int)$mysqli->query("SELECT COUNT(*) n FROM cita WHERE estado='atendida'")->fetch_assoc()['n'];
$statExt   = (int)$mysqli->query("SELECT COUNT(*) n FROM cita WHERE estado='extendida'")->fetch_assoc()['n'];
$statRepr  = (int)$mysqli->query("SELECT COUNT(*) n FROM cita WHERE estado='reprogramada'")->fetch_assoc()['n'];
$statCanc  = (int)$mysqli->query("SELECT COUNT(*) n FROM cita WHERE estado='cancelada'")->fetch_assoc()['n'];

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
    <title>Panel de citas — PrimeLab Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="topbar">
    <a href="admin_citas.php" class="brand">
        <img src="img/PrimeLab%20Logo.png" alt="PrimeLab" class="brand-logo">
        <span class="brand-copy"><strong>PrimeLab</strong><small>Administración</small></span>
    </a>
    <div class="topbar-right"><div class="section-tag"><span>Sección actual</span><b>ADMIN · CITAS</b></div></div>
</header>

<div class="app-layout">
    <aside class="sidebar">
        <div class="user-chip">
            <div class="avatar">A</div>
            <div><strong>Administrador</strong><small>Panel de control</small></div>
        </div>
        <nav>
            <p class="nav-label">Administración</p>
            <a href="admin_citas.php" class="nav-btn active"><span>Citas</span></a>
            <a href="admin_consultas.php" class="nav-btn"><span>Consultas</span></a>
            <a href="admin_medicos.php" class="nav-btn"><span>Médicos</span></a>
            <a href="admin_centros.php" class="nav-btn"><span>Centros y especialidades</span></a>
        </nav>
        <div class="account-actions">
            <a href="logout.php" class="nav-btn nav-btn-danger"><span>Cerrar sesión</span></a>
        </div>
    </aside>

    <main class="content">
        <?php mostrarFlash(); ?>

        <div class="page-heading">
            <p class="eyebrow">Panel administrativo</p>
            <h1>Administración de citas</h1>
            <p class="muted">Supervisa, filtra y gestiona las reservas registradas en bd_primelab.</p>
        </div>

        <section class="dashboard-overview">
            <article class="summary-card"><span class="summary-label">Citas totales</span><strong class="summary-count"><?= $statTotal ?></strong><span class="summary-details">registradas</span></article>
            <article class="summary-card"><span class="summary-label">Pendientes</span><strong class="summary-count"><?= $statPend ?></strong><span class="summary-details">por atender</span></article>
            <article class="summary-card"><span class="summary-label">Atendidas</span><strong class="summary-count"><?= $statAtend ?></strong><span class="summary-details">completadas</span></article>
            <article class="summary-card"><span class="summary-label">Extendidas</span><strong class="summary-count"><?= $statExt ?></strong><span class="summary-details">en seguimiento</span></article>
            <article class="summary-card"><span class="summary-label">Reprogramadas</span><strong class="summary-count"><?= $statRepr ?></strong><span class="summary-details">reagendadas</span></article>
            <article class="summary-card"><span class="summary-label">Canceladas</span><strong class="summary-count"><?= $statCanc ?></strong><span class="summary-details">anuladas</span></article>
        </section>

        <form method="GET" class="filter-bar compact" action="admin_citas.php">
            <label class="field">
                <span>Estado</span>
                <select name="estado">
                    <option value="">Todos</option>
                    <option value="pendiente" <?= $fEstado === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                    <option value="atendida" <?= $fEstado === 'atendida' ? 'selected' : '' ?>>Atendida</option>
                    <option value="extendida" <?= $fEstado === 'extendida' ? 'selected' : '' ?>>Extendida</option>
                    <option value="reprogramada" <?= $fEstado === 'reprogramada' ? 'selected' : '' ?>>Reprogramada</option>
                    <option value="cancelada" <?= $fEstado === 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                </select>
            </label>
            <label class="field">
                <span>Centro</span>
                <select name="centro">
                    <option value="">Todos</option>
                    <?php foreach ($centros as $c): ?>
                        <option value="<?= e($c['codce']) ?>" <?= $fCentro === $c['codce'] ? 'selected' : '' ?>><?= e($c['nomce']) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <div class="field"><span>&nbsp;</span><button type="submit" class="button primary">Filtrar</button></div>
        </form>

        <section class="panel">
            <div class="panel-head">
                <h2 class="panel-title">Historial de transacciones</h2>
                <input type="text" id="adminSearchInput" class="table-search" placeholder="Filtrar en la tabla..." onkeyup="filtrarTablaAdmin()">
            </div>
            <div class="table-responsive">
                <table class="table" id="tablaAdmin">
                    <thead>
                        <tr>
                            <th>Código</th><th>Fecha / Hora</th><th>Paciente</th><th>DNI</th><th>Seguro</th>
                            <th>Centro</th><th>Especialidad</th><th>Médico</th><th>Costo</th><th>Estado</th><th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($citas->num_rows === 0): ?>
                            <tr><td colspan="11" class="table-empty">No hay citas con los filtros seleccionados.</td></tr>
                        <?php else: while ($c = $citas->fetch_assoc()): ?>
                            <tr>
                                <td><span class="code"><?= e($c['codci']) ?></span></td>
                                <td><?= e(fechaTexto($c['fechaci'])) ?><br><small class="muted"><?= e(horaTexto($c['horaci'])) ?></small></td>
                                <td><?= e($c['nomp'] . ' ' . $c['apep']) ?></td>
                                <td><?= e($c['dni']) ?></td>
                                <td><?= e(textoSeguro($c['seguro'] ?? 'SIS')) ?></td>
                                <td><?= e($c['nomce']) ?></td>
                                <td><?= e($c['nomesp']) ?></td>
                                <td><span class="medico-inline"><?= fotoMedico($c['codm'], $c['nomm'], 34) ?><span><?= e($c['nomm']) ?></span></span></td>
                                <td>S/ <?= number_format((float)$c['costo'], 2) ?></td>
                                <td><?= badgeEstado($c['estado']) ?></td>
                                <td class="actions-cell">
                                    <?php if ($c['estado'] === 'pendiente'): ?>
                                        <form method="POST" action="admin_citas.php" style="display:inline;">
                                            <input type="hidden" name="accion" value="atender">
                                            <input type="hidden" name="codci" value="<?= e($c['codci']) ?>">
                                            <button type="submit" class="button secondary small">Atendida</button>
                                        </form>
                                        <form method="POST" action="admin_citas.php" style="display:inline;">
                                            <input type="hidden" name="accion" value="cancelar">
                                            <input type="hidden" name="codci" value="<?= e($c['codci']) ?>">
                                            <input type="hidden" name="motivo" value="Cancelada por administración.">
                                            <button type="submit" class="button danger small">Cancelar</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="muted">—</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>

<footer class="site-footer"><p>PrimeLab — Administración</p></footer>
<script src="js/main.js"></script>
</body>
</html>
