<?php
declare(strict_types=1);

require_once __DIR__ . '/../../controller/EmpresaController.php';

use Residencia\Controller\EmpresaController;

$search = isset($_GET['search']) ? trim((string) $_GET['search']) : null;

$empresaController = new EmpresaController();
$empresas = $empresaController->listEmpresas($search);

function renderBadgeClass(?string $estatus): string {
    $value = trim((string) $estatus);

    if ($value !== '' && function_exists('mb_strtolower')) {
        $value = mb_strtolower($value, 'UTF-8');
    } else {
        $value = strtolower($value);
    }

    switch ($value) {
        case 'activa':
            return 'badge ok';
        case 'en revisión':
        case 'en revision':
            return 'badge secondary';
        case 'inactiva':
            return 'badge warn';
        case 'suspendida':
            return 'badge err';
        default:
            return 'badge secondary';
    }
}

function renderBadgeLabel(?string $estatus): string {
    $estatus = trim((string) $estatus);

    return $estatus !== '' ? $estatus : 'Sin especificar';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Empresas · Residencia Profesional</title>

  <!-- Estilos globales del módulo de Residencias -->
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
    <link rel="stylesheet" href="../../assets/css/empresas/empresalist.css">

</head>
<body>
  <div class="app">
    <!-- Sidebar (usa el que ya configuraste con tus rutas) -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <div>
          <h2>Empresas · Residencia Profesional</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>›</span>
            <span>Empresas</span>
          </nav>
        </div>
        <a href="empresa_add.php" class="btn primary">➕ Registrar Nueva Empresa</a>
      </header>

      <section class="card">
        <header>📂 Lista de Empresas Registradas</header>
        <div class="content">
          <!-- BUSCADOR -->
          <form class="form" style="margin: 0 0 16px 0;">
            <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
              <div class="field" style="min-width:260px; max-width:360px; flex:1;">
                <label for="search">Buscar empresa:</label>
                <input type="text" id="search" name="search" placeholder="Nombre, contacto o RFC..." value="<?php echo htmlspecialchars((string) $search, ENT_QUOTES, 'UTF-8'); ?>" />
              </div>
              <div class="actions" style="margin:0;">
                <button type="submit" class="btn primary">🔍 Buscar</button>
                <a href="empresa_list.php" class="btn">Limpiar</a>
              </div>
            </div>
          </form>

          <!-- TABLA DE EMPRESAS -->
          <div class="table-wrapper" style="overflow:auto;">
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nombre</th>
                  <th>RFC</th>
                  <th>Contacto</th>
                  <th>Email</th>
                  <th>Teléfono</th>
                  <th>Estatus</th>
                  <th style="width:280px;">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($empresas === []) : ?>
                <tr>
                  <td colspan="8" style="text-align:center;">No se encontraron empresas registradas.</td>
                </tr>
                <?php else : ?>
                  <?php foreach ($empresas as $empresa) : ?>
                <tr>
                  <td><?php echo htmlspecialchars((string) ($empresa['id'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars((string) ($empresa['nombre'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars((string) ($empresa['rfc'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars((string) ($empresa['contacto_nombre'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars((string) ($empresa['contacto_email'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><?php echo htmlspecialchars((string) ($empresa['telefono'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                  <td><span class="<?php echo renderBadgeClass($empresa['estatus'] ?? null); ?>"><?php echo htmlspecialchars(renderBadgeLabel($empresa['estatus'] ?? null), ENT_QUOTES, 'UTF-8'); ?></span></td>
                  <td class="actions" style="display:flex; gap:8px; flex-wrap:wrap;">
                    <a href="empresa_view.php?id=<?php echo urlencode((string) ($empresa['id'] ?? '')); ?>" class="btn">👁️ Ver</a>
                    <a href="empresa_edit.php?id=<?php echo urlencode((string) ($empresa['id'] ?? '')); ?>" class="btn">✏️ Editar</a>
                    <a href="empresa_delete.php?id=<?php echo urlencode((string) ($empresa['id'] ?? '')); ?>" class="btn">🗑️ Eliminar</a>
                  </td>
                </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
