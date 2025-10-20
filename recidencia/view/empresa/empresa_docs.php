<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Documentación de Empresa · Residencias Profesionales</title>

  <!-- Estilos base -->
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
  <link rel="stylesheet" href="../../assets/css/empresas/empresadocs.css" />

  <style>
    /* ===== ESTILOS LOCALES (puedes mover a empresadocs.css) ===== */

    .docs-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 10px;
    }

    .docs-summary {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 20px;
      background: #f7f8fa;
      border: 1px solid #ddd;
      border-radius: 10px;
      padding: 15px 20px;
      margin-bottom: 20px;
    }

    .docs-summary strong {
      color: #333;
    }

    .progress-bar {
      width: 200px;
      height: 10px;
      background: #eee;
      border-radius: 5px;
      overflow: hidden;
    }

    .progress-fill {
      height: 10px;
      background: #4caf50;
      width: 60%; /* valor simulado */
      transition: width 0.4s ease;
    }

    .docs-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    .docs-table th, .docs-table td {
      padding: 12px 10px;
      border-bottom: 1px solid #e0e0e0;
      text-align: left;
    }

    .docs-table th {
      background: #f1f1f1;
      font-weight: 600;
    }

    .docs-table td span.badge {
      padding: 3px 8px;
      border-radius: 6px;
      font-size: 13px;
      font-weight: 600;
    }

    .badge.ok { background: #c8f7c5; color: #2e7d32; }
    .badge.pendiente { background: #fff3cd; color: #856404; }
    .badge.rechazado { background: #f8d7da; color: #721c24; }

    .actions {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin-top: 20px;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #ddd;
      padding: 8px 14px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 600;
      color: #222;
      transition: 0.2s;
    }

    .btn:hover { background: #ccc; }

    .btn.primary { background: #007bff; color: #fff; }
    .btn.primary:hover { background: #0069d9; }

    .btn.small { padding: 6px 10px; font-size: 14px; }

    .upload-cell {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .upload-cell input[type="file"] {
      display: none;
    }

    .upload-label {
      background: #007bff;
      color: #fff;
      padding: 6px 10px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 13px;
      transition: 0.2s;
    }

    .upload-label:hover {
      background: #0069d9;
    }

    .file-name {
      font-size: 13px;
      color: #555;
    }
  </style>
</head>

<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <div class="docs-header">
          <div>
            <h2>📂 Documentación de Empresa</h2>
            <p>Consulta y gestiona los documentos legales requeridos por Vinculación.</p>
          </div>
          <div>
            <a href="empresa_view.php?id=45" class="btn secondary">⬅ Volver al detalle</a>
          </div>
        </div>
      </header>

      <!-- 🧾 Resumen -->
      <section class="card">
        <div class="content">
          <div class="docs-summary">
            <div>
              <strong>Empresa:</strong> Casa del Barrio<br>
              <strong>RFC:</strong> CDB810101AA1
            </div>

            <div>
              <strong>Documentos subidos:</strong> 3 / 5<br>
              <div class="progress-bar">
                <div class="progress-fill" style="width:60%;"></div>
              </div>
              <small>60% completado</small>
            </div>
          </div>

          <!-- 📑 Tabla de documentos -->
          <table class="docs-table">
            <thead>
              <tr>
                <th>Documento requerido</th>
                <th>Estado</th>
                <th>Archivo</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Constancia de situación fiscal (SAT)</td>
                <td><span class="badge ok">Aprobado</span></td>
                <td><a href="../../uploads/empresa_45/sat.pdf" target="_blank">📄 sat.pdf</a></td>
                <td>
                  <a href="#" class="btn small">📥 Descargar</a>
                </td>
              </tr>

              <tr>
                <td>Comprobante de domicilio</td>
                <td><span class="badge pendiente">Pendiente</span></td>
                <td>—</td>
                <td class="upload-cell">
                  <label for="upload-domicilio" class="upload-label">⬆ Subir</label>
                  <input id="upload-domicilio" type="file" accept=".pdf,.jpg,.png">
                  <span class="file-name"></span>
                </td>
              </tr>

              <tr>
                <td>INE del representante legal</td>
                <td><span class="badge ok">Aprobado</span></td>
                <td><a href="../../uploads/empresa_45/ine_josevelador.pdf" target="_blank">📄 ine_josevelador.pdf</a></td>
                <td>
                  <a href="#" class="btn small">📥 Descargar</a>
                </td>
              </tr>

              <tr>
                <td>Acta constitutiva</td>
                <td><span class="badge pendiente">Pendiente</span></td>
                <td>—</td>
                <td class="upload-cell">
                  <label for="upload-acta" class="upload-label">⬆ Subir</label>
                  <input id="upload-acta" type="file" accept=".pdf,.jpg,.png">
                  <span class="file-name"></span>
                </td>
              </tr>

              <tr>
                <td>Logotipo del negocio</td>
                <td><span class="badge rechazado">Rechazado</span></td>
                <td><a href="../../uploads/empresa_45/logo_antiguo.png" target="_blank">🖼 logo_antiguo.png</a></td>
                <td class="upload-cell">
                  <label for="upload-logo" class="upload-label">⬆ Reemplazar</label>
                  <input id="upload-logo" type="file" accept=".png,.jpg">
                  <span class="file-name"></span>
                </td>
              </tr>
            </tbody>
          </table>

          <!-- 🔘 Botones finales -->
          <div class="actions">
            <a href="empresa_view.php?id=45" class="btn secondary">⬅ Volver</a>
            <a href="#" class="btn primary">💾 Guardar Cambios</a>
          </div>
        </div>
      </section>
    </main>
  </div>

  <script>
    // Vista previa del nombre del archivo al seleccionar
    document.querySelectorAll('input[type="file"]').forEach(input => {
      input.addEventListener('change', (e) => {
        const fileName = e.target.files.length ? e.target.files[0].name : '';
        e.target.closest('.upload-cell').querySelector('.file-name').textContent = fileName;
      });
    });
  </script>
</body>
</html>
