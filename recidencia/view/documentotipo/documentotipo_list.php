<?php
declare(strict_types=1);

/**
 * @var array{
 *     q: string,
 *     tipo_empresa: string,
 *     tipos: array<int, array<string, mixed>>,
 *     tipoEmpresaOptions: array<string, string>,
 *     errorMessage: ?string,
 *     statusMessage: ?string
 * } $handlerResult
 */
$handlerResult = require __DIR__ . '/../../handler/documentotipo/documentotipo_list_handler.php';

$searchValue = $handlerResult['q'];
$selectedTipoEmpresa = $handlerResult['tipo_empresa'];
$tipos = $handlerResult['tipos'];
$tipoEmpresaOptions = $handlerResult['tipoEmpresaOptions'];
$errorMessage = $handlerResult['errorMessage'];
$statusMessage = $handlerResult['statusMessage'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tipos de Documento - Residencias Profesionales</title>

  <link rel="stylesheet" href="../../assets/css/dashboard.css" />
  <link rel="stylesheet" href="../../assets/css/modules/documentotipo.css" />


</head>

<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div>
          <h2>Tipos de Documento</h2>
          <p class="subtitle">Catalogo de documentos requeridos por Vinculacion para empresas fisicas y morales.</p>
        </div>
        <a href="documentotipo_add.php" class="btn primary">Nuevo tipo</a>
      </header>

      <section class="card">
        <header>Filtros de busqueda</header>
        <div class="content">
          <form class="form" method="get" action="documentotipo_list.php">
            <div class="filters">
              <div class="field">
                <label for="q">Buscar</label>
                <input
                  id="q"
                  name="q"
                  type="text"
                  placeholder="Nombre o descripcion del documento"
                  value="<?php echo htmlspecialchars($searchValue, ENT_QUOTES, 'UTF-8'); ?>"
                />
              </div>

              <div class="field">
                <label for="tipo_empresa">Tipo de empresa</label>
                <select id="tipo_empresa" name="tipo_empresa">
                  <option value="">Todas</option>
                  <?php foreach ($tipoEmpresaOptions as $value => $label): ?>
                    <option
                      value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>"
                      <?php echo $selectedTipoEmpresa === $value ? 'selected' : ''; ?>
                    >
                      <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="actions">
                <button class="btn primary" type="submit">Buscar</button>
                <a class="btn" href="documentotipo_list.php">Limpiar</a>
              </div>
            </div>
          </form>
        </div>
      </section>

      <?php if ($statusMessage !== null): ?>
        <section class="card">
          <div class="content">
            <div class="alert alert-success">
              <?php echo htmlspecialchars($statusMessage, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          </div>
        </section>
      <?php endif; ?>

      <?php if ($errorMessage !== null): ?>
        <section class="card">
          <div class="content">
            <div class="alert alert-danger">
              <?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          </div>
        </section>
      <?php endif; ?>

      <section class="card">
        <header>Listado de tipos de documento</header>
        <div class="content">
          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nombre del documento</th>
                  <th>Descripcion</th>
                  <th>Tipo de empresa</th>
                  <th>Obligatorio</th>
                  <th>Estado</th>
                  <th style="min-width:200px;">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($tipos === []): ?>
                  <tr>
                    <td colspan="7" style="text-align:center;">No se encontraron tipos de documento.</td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($tipos as $tipo): ?>
                    <?php
                      $id = isset($tipo['id']) ? (int) $tipo['id'] : null;
                      $nombre = documentoTipoValueOrDefault($tipo['nombre'] ?? null, 'Sin nombre');
                      $descripcion = documentoTipoValueOrDefault($tipo['descripcion'] ?? null, 'Sin descripcion');
                      $tipoEmpresaLabel = documentoTipoRenderEmpresaLabel($tipo['tipo_empresa'] ?? null);
                      $obligatorioClass = documentoTipoRenderObligatorioClass($tipo['obligatorio'] ?? null);
                      $obligatorioLabel = documentoTipoRenderObligatorioLabel($tipo['obligatorio'] ?? null);
                      $estaActivo = documentoTipoCastBool($tipo['activo'] ?? null);
                      $estadoClass = $estaActivo ? 'badge status-active' : 'badge status-inactive';
                      $estadoLabel = $estaActivo ? 'Activo' : 'Inactivo';
                    ?>
                    <tr>
                      <td><?php echo $id !== null ? htmlspecialchars((string) $id, ENT_QUOTES, 'UTF-8') : 'N/A'; ?></td>
                      <td><?php echo htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8'); ?></td>
                      <td>
                        <span class="badge secondary">
                          <?php echo htmlspecialchars($tipoEmpresaLabel, ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                      </td>
                      <td>
                        <span class="<?php echo htmlspecialchars($obligatorioClass, ENT_QUOTES, 'UTF-8'); ?>">
                          <?php echo htmlspecialchars($obligatorioLabel, ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                      </td>
                      <td>
                        <span class="<?php echo htmlspecialchars($estadoClass, ENT_QUOTES, 'UTF-8'); ?>">
                          <?php echo htmlspecialchars($estadoLabel, ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                      </td>
                      <td class="actions">
                        <?php if ($id !== null): ?>
                          <a class="btn small" href="documentotipo_edit.php?id=<?php echo urlencode((string) $id); ?>">Editar</a>
                          <a class="btn small danger" href="documentotipo_delete.php?id=<?php echo urlencode((string) $id); ?>">Eliminar</a>
                        <?php else: ?>
                          <span class="text-muted">Acciones no disponibles</span>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <div class="legend">
            <strong>Leyenda:</strong>
            <span class="badge ok">Si (Obligatorio)</span>
            <span class="badge warn">No (Opcional)</span>
          </div>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
