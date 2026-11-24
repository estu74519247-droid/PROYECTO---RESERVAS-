<?php
// admin_medicos.php — Gestión de médicos.
require_once __DIR__ . '/../core/file_conexion.php';
requerirAdmin();
$mysqli = Conectarse();

$mensajeError = '';
$medicoEditar = null;

// Guardar (crear/editar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    if ($accion === 'guardar') {
        $codm   = trim($_POST['codm'] ?? '');
        $nomm   = trim($_POST['nomm'] ?? '');
        $cmpm   = trim($_POST['cmpm'] ?? '');
        $codesp = trim($_POST['codesp'] ?? '');
        $esEdicion = ($_POST['edicion'] ?? '') === '1';

        if ($codm === '' || $nomm === '' || $cmpm === '' || $codesp === '') {
            $mensajeError = 'Completa todos los campos.';
            $medicoEditar = ['codm'=>$codm,'nomm'=>$nomm,'cmpm'=>$cmpm,'codesp'=>$codesp];
        } elseif (!preg_match("/^[\p{L}\s'’.,\/()\-]+$/u", $nomm)) {
            $mensajeError = 'El nombre del médico solo debe contener letras (sin números).';
            $medicoEditar = ['codm'=>$codm,'nomm'=>$nomm,'cmpm'=>$cmpm,'codesp'=>$codesp];
        } elseif (!preg_match('/^\d{2,10}$/', $cmpm)) {
            $mensajeError = 'El CMP debe contener solo números (2 a 10 dígitos).';
            $medicoEditar = ['codm'=>$codm,'nomm'=>$nomm,'cmpm'=>$cmpm,'codesp'=>$codesp];
        } else {
            if ($esEdicion) {
                $stmt = $mysqli->prepare("UPDATE medico SET nomm=?, cmpm=?, codesp=? WHERE codm=?");
                $stmt->bind_param("ssss", $nomm, $cmpm, $codesp, $codm);
            } else {
                $stmt = $mysqli->prepare("INSERT INTO medico (codm, nomm, cmpm, codesp) VALUES (?,?,?,?)");
                $stmt->bind_param("ssss", $codm, $nomm, $cmpm, $codesp);
            }
            $ok = @$stmt->execute();
            $stmt->close();
            if ($ok) {
                flash('exito', $esEdicion ? 'Médico actualizado.' : 'Médico registrado.');
                redirigir("admin_medicos.php");
            } else {
                $mensajeError = 'No se pudo guardar. Verifica que el código no esté duplicado.';
                $medicoEditar = ['codm'=>$codm,'nomm'=>$nomm,'cmpm'=>$cmpm,'codesp'=>$codesp];
            }
        }
    }
    if ($accion === 'eliminar') {
        $codm = trim($_POST['codm'] ?? '');
        $stmt = $mysqli->prepare("DELETE FROM medico WHERE codm = ?");
        $stmt->bind_param("s", $codm);
        @$stmt->execute();
        $stmt->close();
        flash('exito', 'Médico eliminado (si no tenía citas asociadas).');
        redirigir("admin_medicos.php");
    }
}

if (isset($_GET['editar']) && !$medicoEditar) {
    $cod = trim($_GET['editar']);
    $stmt = $mysqli->prepare("SELECT codm, nomm, cmpm, codesp FROM medico WHERE codm = ? LIMIT 1");
    $stmt->bind_param("s", $cod);
    $stmt->execute();
    $medicoEditar = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

$especialidades = [];
$res = $mysqli->query("SELECT codesp, nomesp FROM especialidad ORDER BY nomesp");
while ($r = $res->fetch_assoc()) $especialidades[] = $r;

$medicos = $mysqli->query("
    SELECT m.codm, m.nomm, m.cmpm, m.codesp, e.nomesp
    FROM medico m JOIN especialidad e ON e.codesp = m.codesp
    ORDER BY m.codm
");

$mysqli->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Médicos — PrimeLab Admin</title>
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
    <div class="topbar-right"><div class="section-tag"><span>Sección actual</span><b>ADMIN · MÉDICOS</b></div></div>
</header>

<div class="app-layout">
    <aside class="sidebar">
        <div class="user-chip"><div class="avatar">A</div><div><strong>Administrador</strong><small>Panel de control</small></div></div>
        <nav>
            <p class="nav-label">Administración</p>
            <a href="admin_citas.php" class="nav-btn"><span>Citas</span></a>
            <a href="admin_consultas.php" class="nav-btn"><span>Consultas</span></a>
            <a href="admin_medicos.php" class="nav-btn active"><span>Médicos</span></a>
            <a href="admin_centros.php" class="nav-btn"><span>Centros y especialidades</span></a>
        </nav>
        <div class="account-actions"><a href="logout.php" class="nav-btn nav-btn-danger"><span>Cerrar sesión</span></a></div>
    </aside>

    <main class="content">
        <?php mostrarFlash(); ?>
        <?php if ($mensajeError): ?><div class="alert show error"><?= e($mensajeError) ?></div><?php endif; ?>

        <div class="page-heading">
            <p class="eyebrow">Administración</p>
            <h1>Médicos</h1>
            <p class="muted">Registra y actualiza los médicos del sistema.</p>
        </div>

        <section class="panel">
            <h2 class="panel-title"><?= $medicoEditar ? 'Editar médico' : 'Registrar médico' ?></h2>
            <form method="POST" action="admin_medicos.php" class="form-stack">
                <input type="hidden" name="accion" value="guardar">
                <input type="hidden" name="edicion" value="<?= $medicoEditar ? '1' : '0' ?>">
                <div class="form-grid-2">
                    <label class="field">
                        <span>Código</span>
                        <input type="text" name="codm" maxlength="6" value="<?= e($medicoEditar['codm'] ?? '') ?>" <?= $medicoEditar ? 'readonly' : '' ?> required placeholder="Ej. MED008">
                    </label>
                    <label class="field">
                        <span>CMP</span>
                        <input type="text" name="cmpm" maxlength="10" inputmode="numeric"
                               data-numerico="true" data-max="10" data-label="CMP"
                               value="<?= e($medicoEditar['cmpm'] ?? '') ?>" required placeholder="N° CMP">
                    </label>
                </div>
                <label class="field">
                    <span>Nombre completo</span>
                    <input type="text" name="nomm" maxlength="60" data-texto="true" data-label="Nombre del médico" value="<?= e($medicoEditar['nomm'] ?? '') ?>" required placeholder="Ej. Dra. María López">
                </label>
                <label class="field">
                    <span>Especialidad</span>
                    <select name="codesp" required>
                        <option value="">Seleccione especialidad</option>
                        <?php foreach ($especialidades as $x): ?>
                            <option value="<?= e($x['codesp']) ?>" <?= ($medicoEditar['codesp'] ?? '') === $x['codesp'] ? 'selected' : '' ?>>
                                <?= e($x['nomesp']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <div class="ticket-actions">
                    <button type="submit" class="button primary"><?= $medicoEditar ? 'Actualizar' : 'Registrar' ?></button>
                    <?php if ($medicoEditar): ?><a href="admin_medicos.php" class="button secondary">Cancelar edición</a><?php endif; ?>
                </div>
            </form>
        </section>

        <section class="panel" style="margin-top:18px;">
            <h2 class="panel-title">Listado de médicos</h2>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Código</th><th>Nombre</th><th>CMP</th><th>Especialidad</th><th>Acciones</th></tr></thead>
                    <tbody>
                        <?php while ($m = $medicos->fetch_assoc()): ?>
                        <tr>
                            <td><span class="code"><?= e($m['codm']) ?></span></td>
                            <td><?= e($m['nomm']) ?></td>
                            <td><?= e($m['cmpm']) ?></td>
                            <td><?= e($m['nomesp']) ?></td>
                            <td class="actions-cell">
                                <a href="admin_medicos.php?editar=<?= e($m['codm']) ?>" class="button secondary small">Editar</a>
                                <form method="POST" action="admin_medicos.php" style="display:inline;">
                                    <input type="hidden" name="accion" value="eliminar">
                                    <input type="hidden" name="codm" value="<?= e($m['codm']) ?>">
                                    <button type="submit" class="button danger small">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
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
