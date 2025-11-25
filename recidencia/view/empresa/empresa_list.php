<?php
declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/empresafunction.php';
require __DIR__ . '/../../handler/empresa/empresa_list_handler.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Empresas · Residencia Profesional</title>

  <!-- Estilos específicos para este listado -->
  <link rel="stylesheet" href="../../assets/css/modules/empresa/empresalist.css" />



  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.4.0/css/fixedHeader.dataTables.min.css">

  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/fixedheader/3.4.0/js/dataTables.fixedHeader.min.js"></script>

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
          <?php if ($errorMessage !== null): ?>
            <div class="alert error" role="alert">
              <?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          <?php endif; ?>
          <!-- BUSCADOR -->
          <form class="form search-form">
            <div class="search-row">
              <div class="field search-field">
                <label for="search">Buscar empresa:</label>
                <input type="text" id="search" name="search" placeholder="Nombre, contacto o RFC..."
                  value="<?php echo htmlspecialchars((string) $search, ENT_QUOTES, 'UTF-8'); ?>" />
              </div>
              <div class="actions search-actions">
                <button type="submit" class="btn primary">🔍 Buscar</button>
                <a href="empresa_list.php" class="btn">Limpiar</a>
              </div>
            </div>
          </form>

          <!-- TABLA DE EMPRESAS -->
          <div class="table-wrapper">
            <table id="empresasTable" class="display nowrap">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>No. Control</th>
                  <th>Nombre</th>
                  <th>RFC</th>
                  <th>Contacto</th>
                  <th>Email</th>
                  <th>Teléfono</th>
                  <th>Estatus</th>
                  <th class="actions-column">Acciones</th>
                </tr>

                <tr class="filter-row">
                  <th><input type="text" placeholder="ID"></th>
                  <th><input type="text" placeholder="Control"></th>
                  <th><input type="text" placeholder="Nombre"></th>
                  <th><input type="text" placeholder="RFC"></th>
                  <th><input type="text" placeholder="Contacto"></th>
                  <th><input type="text" placeholder="Email"></th>
                  <th><input type="text" placeholder="Teléfono"></th>
                  <th><input type="text" placeholder="Estatus"></th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php if ($empresas === []): ?>
                  <tr>
                    <td colspan="9" class="empty-state">No se encontraron empresas registradas.</td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($empresas as $empresa): ?>
                    <?php
                    $empresaId = $empresa['id'] ?? '';
                    $empresaNombre = $empresa['nombre'] ?? '';
                    $empresaEstatus = (string) ($empresa['estatus'] ?? '');
                    $empresaPuedeDesactivar = $empresaEstatus === 'Activa' || $empresaEstatus === 'En revisión';
                    $empresaPuedeReactivar = $empresaEstatus === 'Inactiva';
                    ?>
                    <tr>
                      <td><?php echo htmlspecialchars((string) $empresaId, ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars((string) ($empresa['numero_control'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
                      </td>
                      <td><?php echo htmlspecialchars((string) $empresaNombre, ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars((string) ($empresa['rfc'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars((string) ($empresa['contacto_nombre'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                      </td>
                      <td><?php echo htmlspecialchars((string) ($empresa['contacto_email'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                      </td>
                      <td><?php echo htmlspecialchars((string) ($empresa['telefono'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                      <td>
                        <span
                          class="<?php echo renderBadgeClass($empresaEstatus); ?>"><?php echo htmlspecialchars(renderBadgeLabel($empresaEstatus), ENT_QUOTES, 'UTF-8'); ?></span>
                      </td>
                      <td class="actions">
                        <a href="empresa_view.php?id=<?php echo urlencode((string) $empresaId); ?>" class="btn">👁️ Ver</a>
                        <a href="empresa_edit.php?id=<?php echo urlencode((string) $empresaId); ?>" class="btn">✏️
                          Editar</a>

                        <?php if ($empresaPuedeDesactivar): ?>
                          <form id="disableForm-<?php echo htmlspecialchars((string) $empresaId, ENT_QUOTES, 'UTF-8'); ?>"
                            action="../../handler/empresa/empresa_disable.php" method="post">
                            <input type="hidden" name="id"
                              value="<?php echo htmlspecialchars((string) $empresaId, ENT_QUOTES, 'UTF-8'); ?>">
                            <button type="button" class="btn warn"
                              onclick="confirmDisable(<?php echo htmlspecialchars((string) $empresaId, ENT_QUOTES, 'UTF-8'); ?>, '<?php echo addslashes((string) $empresaNombre); ?>')">
                              🚫 Desactivar
                            </button>
                          </form>
                        <?php elseif ($empresaPuedeReactivar): ?>
                          <form id="reactivateForm-<?php echo htmlspecialchars((string) $empresaId, ENT_QUOTES, 'UTF-8'); ?>"
                            action="../../handler/empresa/empresa_reactivate.php" method="post">
                            <input type="hidden" name="id"
                              value="<?php echo htmlspecialchars((string) $empresaId, ENT_QUOTES, 'UTF-8'); ?>">
                            <button type="button" class="btn success"
                              onclick="confirmReactivate(<?php echo htmlspecialchars((string) $empresaId, ENT_QUOTES, 'UTF-8'); ?>, '<?php echo addslashes((string) $empresaNombre); ?>')">
                              ✅ Reactivar
                            </button>
                          </form>
                        <?php endif; ?>
                        <!-- <a href="empresa_delete.php?id=<?php echo urlencode((string) $empresaId); ?>" class="btn">🗑️ Eliminar</a> -->
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
  <script>
    $(document).ready(function () {

      // =======================
      //  DataTable con filtros
      // =======================
      const table = $('#empresasTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        orderCellsTop: true,
        fixedHeader: true,
        language: {
          search: "Buscar en tabla:",
          lengthMenu: "Mostrar _MENU_ registros",
          zeroRecords: "No se encontraron resultados",
          info: "Mostrando _START_ a _END_ de _TOTAL_ empresas",
          infoEmpty: "No hay datos disponibles",
          infoFiltered: "(filtrado de _MAX_ registros totales)",
          paginate: {
            first: "Primero",
            last: "Último",
            next: "Siguiente",
            previous: "Anterior"
          }
        },
        initComplete: function () {
          const api = this.api();
          api.columns().every(function (index) {
            const input = $('#empresasTable thead tr.filter-row th').eq(index).find('input');
            if (input.length === 0) {
              return;
            }

            input.on('keyup change', function () {
              const value = this.value;
              if (api.column(index).search() !== value) {
                api.column(index).search(value).draw();
              }
            });
          });
        }
      });

    });
  </script>
  <script src="../../assets/js/empresa-actions.js"></script>
</body>

</html>
