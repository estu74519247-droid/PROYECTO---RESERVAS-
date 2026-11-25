<?php
// admin_centros.php — Gestión de centros y especialidades.
require_once __DIR__ . '/../core/file_conexion.php';
requerirAdmin();
$mysqli = Conectarse();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'guardar_centro') {
        $codce = trim($_POST['codce'] ?? '');
        $nomce = trim($_POST['nomce'] ?? '');
        $dirc  = trim($_POST['dirc'] ?? '');
        $dist  = trim($_POST['distrito'] ?? '');
        $tel   = trim($_POST['telefono'] ?? '');
        $ed    = ($_POST['edicion'] ?? '') === '1';
        $patronNombre = "/^[\p{L}\s'’.,\/()\-]+$/u";
        if ($codce && $nomce && $dirc && $dist && $tel) {
            if (!preg_match($patronNombre, $nomce) || !preg_match('/^\d{6,15}$/', $tel)) {
                flash('error', 'El nombre del centro solo debe contener letras y el teléfono solo números.');
                redirigir("admin_centros.php");
            }
            if ($ed) {
                $stmt = $mysqli->prepare("UPDATE centro SET nomce=?, dirc=?, distrito=?, telefono=? WHERE codce=?");
                $stmt->bind_param("sssss", $nomce, $dirc, $dist, $tel, $codce);
            } else {
                $stmt = $mysqli->prepare("INSERT INTO centro (codce, nomce, dirc, distrito, telefono) VALUES (?,?,?,?,?)");
                $stmt->bind_param("sssss", $codce, $nomce, $dirc, $dist, $tel);
            }
            @$stmt->execute();
            $stmt->close();
            flash('exito', $ed ? 'Centro actualizado.' : 'Centro registrado.');
        }
        redirigir("admin_centros.php");
    }

    if ($accion === 'eliminar_centro') {
        $codce = trim($_POST['codce'] ?? '');
        $stmt = $mysqli->prepare("DELETE FROM centro WHERE codce = ?");
        $stmt->bind_param("s", $codce);
        @$stmt->execute();
        $stmt->close();
        flash('exito', 'Centro eliminado (si no tenía citas asociadas).');
        redirigir("admin_centros.php");
    }

    if ($accion === 'guardar_esp') {
        $codesp = trim($_POST['codesp'] ?? '');
        $nomesp = trim($_POST['nomesp'] ?? '');
        $ed = ($_POST['edicion_esp'] ?? '') === '1';
        if ($codesp && $nomesp) {
            if (!preg_match("/^[\p{L}\s'’.,\/()\-]+$/u", $nomesp)) {
                flash('error', 'El nombre de la especialidad solo debe contener letras.');
                redirigir("admin_centros.php");
            }
            if ($ed) {
                $stmt = $mysqli->prepare("UPDATE especialidad SET nomesp=? WHERE codesp=?");
                $stmt->bind_param("ss", $nomesp, $codesp);
            } else {
                $stmt = $mysqli->prepare("INSERT INTO especialidad (codesp, nomesp) VALUES (?,?)");
                $stmt->bind_param("ss", $codesp, $nomesp);
            }
            @$stmt->execute();
            $stmt->close();
            flash('exito', $ed ? 'Especialidad actualizada.' : 'Especialidad registrada.');
        }
        redirigir("admin_centros.php");
    }

    if ($accion === 'asignar_esp') {
        $codce  = trim($_POST['codce'] ?? '');
        $codesp = trim($_POST['codesp'] ?? '');
        if ($codce && $codesp) {
            $stmt = $mysqli->prepare("INSERT IGNORE INTO centro_especialidad (codce, codesp) VALUES (?,?)");
            $stmt->bind_param("ss", $codce, $codesp);
            @$stmt->execute();
            $stmt->close();
            flash('exito', 'Especialidad asignada al centro.');
        }
        redirigir("admin_centros.php");
    }

    if ($accion === 'quitar_esp') {
        $codce  = trim($_POST['codce'] ?? '');
        $codesp = trim($_POST['codesp'] ?? '');
        $stmt = $mysqli->prepare("DELETE FROM centro_especialidad WHERE codce=? AND codesp=?");
        $stmt->bind_param("ss", $codce, $codesp);
        @$stmt->execute();
        $stmt->close();
        flash('exito', 'Asignación eliminada.');
        redirigir("admin_centros.php");
    }
}

$centroEditar = null; $espEditar = null;
if (isset($_GET['editar_centro'])) {
    $stmt = $mysqli->prepare("SELECT * FROM centro WHERE codce=? LIMIT 1");
    $stmt->bind_param("s", $_GET['editar_centro']);
    $stmt->execute();
    $centroEditar = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
if (isset($_GET['editar_esp'])) {
    $stmt = $mysqli->prepare("SELECT * FROM especialidad WHERE codesp=? LIMIT 1");
    $stmt->bind_param("s", $_GET['editar_esp']);
    $stmt->execute();
    $espEditar = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

$centros = $mysqli->query("SELECT * FROM centro ORDER BY codce");
$especialidades = [];
$res = $mysqli->query("SELECT * FROM especialidad ORDER BY nomesp");
while ($r = $res->fetch_assoc()) $especialidades[] = $r;
$asignaciones = $mysqli->query("
    SELECT ce.codce, ce.codesp, c.nomce, e.nomesp
    FROM centro_especialidad ce
    JOIN centro c ON c.codce = ce.codce
    JOIN especialidad e ON e.codesp = ce.codesp
    ORDER BY c.nomce, e.nomesp
");
$listaCentros = $mysqli->query("SELECT codce, nomce FROM centro ORDER BY nomce");
$listaCentrosArr = [];
while ($r = $listaCentros->fetch_assoc()) $listaCentrosArr[] = $r;

$mysqli->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centros y especialidades — PrimeLab Admin</title>
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
    <div class="topbar-right"><div class="section-tag"><span>Sección actual</span><b>ADMIN · CENTROS</b></div></div>
</header>

<div class="app-layout">
    <aside class="sidebar">
        <div class="user-chip"><div class="avatar">A</div><div><strong>Administrador</strong><small>Panel de control</small></div></div>
        <nav>
            <p class="nav-label">Administración</p>
            <a href="admin_citas.php" class="nav-btn"><span>Citas</span></a>
            <a href="admin_consultas.php" class="nav-btn"><span>Consultas</span></a>
            <a href="admin_medicos.php" class="nav-btn"><span>Médicos</span></a>
            <a href="admin_centros.php" class="nav-btn active"><span>Centros y especialidades</span></a>
        </nav>
        <div class="account-actions"><a href="logout.php" class="nav-btn nav-btn-danger"><span>Cerrar sesión</span></a></div>
    </aside>

    <main class="content">
        <?php mostrarFlash(); ?>

        <div class="page-heading">
            <p class="eyebrow">Administración</p>
            <h1>Centros y especialidades</h1>
            <p class="muted">Gestiona centros de salud, especialidades y sus asignaciones.</p>
        </div>

        <section class="panel">
            <h2 class="panel-title"><?= $centroEditar ? 'Editar centro' : 'Registrar centro' ?></h2>
            <form method="POST" action="admin_centros.php" class="form-stack">
                <input type="hidden" name="accion" value="guardar_centro">
                <input type="hidden" name="edicion" value="<?= $centroEditar ? '1' : '0' ?>">
                <div class="form-grid-2">
                    <label class="field"><span>Código</span><input type="text" name="codce" maxlength="6" value="<?= e($centroEditar['codce'] ?? '') ?>" <?= $centroEditar ? 'readonly' : '' ?> required></label>
                    <label class="field"><span>Teléfono</span><input type="text" name="telefono" maxlength="15" inputmode="numeric" data-numerico="true" data-max="15" data-label="Teléfono" value="<?= e($centroEditar['telefono'] ?? '') ?>" required></label>
                </div>
                <label class="field"><span>Nombre</span><input type="text" name="nomce" maxlength="60" data-texto="true" data-label="Nombre del centro" value="<?= e($centroEditar['nomce'] ?? '') ?>" required></label>
                <div class="form-grid-2">
                    <label class="field"><span>Dirección</span><input type="text" name="dirc" maxlength="80" value="<?= e($centroEditar['dirc'] ?? '') ?>" required></label>
                    <label class="field"><span>Distrito</span><input type="text" name="distrito" maxlength="40" value="<?= e($centroEditar['distrito'] ?? '') ?>" required></label>
                </div>
                <button type="submit" class="button primary"><?= $centroEditar ? 'Actualizar' : 'Registrar' ?></button>
            </form>
            <div class="table-responsive" style="margin-top:14px;">
                <table class="table">
                    <thead><tr><th>Código</th><th>Nombre</th><th>Distrito</th><th>Teléfono</th><th>Acciones</th></tr></thead>
                    <tbody>
                        <?php while ($c = $centros->fetch_assoc()): ?>
                        <tr>
                            <td><span class="code"><?= e($c['codce']) ?></span></td>
                            <td><?= e($c['nomce']) ?></td>
                            <td><?= e($c['distrito']) ?></td>
                            <td><?= e($c['telefono']) ?></td>
                            <td class="actions-cell">
                                <a href="admin_centros.php?editar_centro=<?= e($c['codce']) ?>" class="button secondary small">Editar</a>
                                <form method="POST" style="display:inline;"><input type="hidden" name="accion" value="eliminar_centro"><input type="hidden" name="codce" value="<?= e($c['codce']) ?>"><button class="button danger small">Eliminar</button></form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <div class="two-col" style="margin-top:18px;">
            <section class="panel">
                <h2 class="panel-title"><?= $espEditar ? 'Editar especialidad' : 'Registrar especialidad' ?></h2>
                <form method="POST" action="admin_centros.php" class="form-stack">
                    <input type="hidden" name="accion" value="guardar_esp">
                    <input type="hidden" name="edicion_esp" value="<?= $espEditar ? '1' : '0' ?>">
                    <label class="field"><span>Código</span><input type="text" name="codesp" maxlength="6" value="<?= e($espEditar['codesp'] ?? '') ?>" <?= $espEditar ? 'readonly' : '' ?> required></label>
                    <label class="field"><span>Nombre</span><input type="text" name="nomesp" maxlength="40" data-texto="true" data-label="Nombre de la especialidad" value="<?= e($espEditar['nomesp'] ?? '') ?>" required></label>
                    <button type="submit" class="button primary"><?= $espEditar ? 'Actualizar' : 'Registrar' ?></button>
                </form>
                <ul class="timeline" style="margin-top:12px;">
                    <?php foreach ($especialidades as $e): ?>
                        <li><strong><?= e($e['codesp']) ?></strong> — <?= e($e['nomesp']) ?> <a href="admin_centros.php?editar_esp=<?= e($e['codesp']) ?>" class="link-inline">Editar</a></li>
                    <?php endforeach; ?>
                </ul>
            </section>

            <section class="panel">
                <h2 class="panel-title">Asignar especialidad a centro</h2>
                <form method="POST" action="admin_centros.php" class="form-stack">
                    <input type="hidden" name="accion" value="asignar_esp">
                    <label class="field">
                        <span>Centro</span>
                        <select name="codce" required>
                            <option value="">Seleccione centro</option>
                            <?php foreach ($listaCentrosArr as $c): ?><option value="<?= e($c['codce']) ?>"><?= e($c['nomce']) ?></option><?php endforeach; ?>
                        </select>
                    </label>
                    <label class="field">
                        <span>Especialidad</span>
                        <select name="codesp" required>
                            <option value="">Seleccione especialidad</option>
                            <?php foreach ($especialidades as $e): ?><option value="<?= e($e['codesp']) ?>"><?= e($e['nomesp']) ?></option><?php endforeach; ?>
                        </select>
                    </label>
                    <button type="submit" class="button primary">Asignar</button>
                </form>
                <div class="table-responsive" style="margin-top:12px;">
                    <table class="table">
                        <thead><tr><th>Centro</th><th>Especialidad</th><th></th></tr></thead>
                        <tbody>
                            <?php while ($a = $asignaciones->fetch_assoc()): ?>
                            <tr>
                                <td><?= e($a['nomce']) ?></td>
                                <td><?= e($a['nomesp']) ?></td>
                                <td>
                                    <form method="POST" style="display:inline;"><input type="hidden" name="accion" value="quitar_esp"><input type="hidden" name="codce" value="<?= e($a['codce']) ?>"><input type="hidden" name="codesp" value="<?= e($a['codesp']) ?>"><button class="button danger small">Quitar</button></form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </main>
</div>

<footer class="site-footer"><p>PrimeLab — Administración</p></footer>
<script src="js/main.js"></script>
</body>
</html>
