<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Documento Individual · Residencias Profesionales</title>

  <!-- Estilos globales -->
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
  <link rel="stylesheet" href="../../assets/css/documentotipo/documentotipo.css" />

  <style>
    /* 🎨 Estilos locales */
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }

    .form-grid .full {
      grid-column: 1 / -1;
    }

    label {
      display: block;
      margin-bottom: 6px;
      font-weight: 600;
      color: #333;
    }

    input, select, textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 15px;
      transition: border-color 0.2s;
    }

    input:focus, select:focus, textarea:focus {
      border-color: #007bff;
      outline: none;
    }

    textarea {
      min-height: 100px;
      resize: vertical;
    }

    .actions {
      display: flex;
      justify-content: flex-end;
      gap: 12px;
      margin-top: 20px;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 10px 16px;
      border-radius: 6px;
      font-weight: 600;
      text-decoration: none;
      cursor: pointer;
      transition: background 0.2s;
    }

    .btn.primary {
      background: #007bff;
      color: #fff;
    }
    .btn.primary:hover { background: #0069d9; }

    .btn.secondary {
      background: #e0e0e0;
      color: #222;
    }
    .btn.secondary:hover { background: #d5d5d5; }

    .btn.activar {
      background: #43a047;
      color: #fff;
    }
    .btn.activar:hover { background: #388e3c; }

    .btn.desactivar {
      background: #f44336;
      color: #fff;
    }
    .btn.desactivar:hover { background: #d32f2f; }

    .badge.inactivo {
      background: #f8d7da;
      color: #721c24;
      padding: 3px 8px;
      border-radius: 6px;
      font-size: 13px;
      font-weight: 600;
    }

    .subtitle {
      font-size: 15px;
      color: #555;
      margin-top: 4px;
    }
  </style>
</head>

<body>
  <?php
    // Variables simuladas (estas vendrán del Controller)
    $empresa_id = $_GET['id_empresa'] ?? 0;
    $doc_id = $_GET['id'] ?? 0;
    $doc = [
      'nombre' => 'Logotipo del negocio',
      'descripcion' => 'Archivo PNG o JPG del logotipo oficial de la empresa.',
      'obligatorio' => 1,
      'tipo_empresa' => 'moral',
      'activo' => 0
    ];
  ?>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <!-- 🔝 Header -->
      <header class="topbar">
        <div>
          <h2>✏️ Editar Documento Individual</h2>
          <p class="subtitle">Modifica la información del requisito documental asignado a esta empresa.</p>
        </div>
        <a href="empresa_documentotipo_list.php?id_empresa=<?= htmlspecialchars($empresa_id) ?>" class="btn secondary">⬅ Volver</a>
      </header>

      <!-- 🧾 Formulario -->
      <section class="card">
        <header>📄 Datos del Documento</header>
        <div class="content">
          <form action="empresa_documentotipo_edit_action.php" method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($doc_id) ?>">
            <input type="hidden" name="empresa_id" value="<?= htmlspecialchars($empresa_id) ?>">

            <div class="form-grid">
              <div class="field full">
                <label for="nombre">Nombre del documento *</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($doc['nombre']) ?>" required <?= $doc['activo'] ? '' : 'disabled' ?>>
              </div>

              <div class="field full">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" <?= $doc['activo'] ? '' : 'disabled' ?>><?= htmlspecialchars($doc['descripcion']) ?></textarea>
              </div>

              <div class="field">
                <label for="obligatorio">¿Obligatorio?</label>
                <select id="obligatorio" name="obligatorio" <?= $doc['activo'] ? '' : 'disabled' ?>>
                  <option value="1" <?= $doc['obligatorio'] ? 'selected' : '' ?>>Sí</option>
                  <option value="0" <?= !$doc['obligatorio'] ? 'selected' : '' ?>>No</option>
                </select>
              </div>

              <div class="field">
                <label for="tipo_empresa">Tipo de empresa</label>
                <select id="tipo_empresa" name="tipo_empresa" <?= $doc['activo'] ? '' : 'disabled' ?>>
                  <option value="ambas" <?= $doc['tipo_empresa'] === 'ambas' ? 'selected' : '' ?>>Ambas</option>
                  <option value="fisica" <?= $doc['tipo_empresa'] === 'fisica' ? 'selected' : '' ?>>Física</option>
                  <option value="moral" <?= $doc['tipo_empresa'] === 'moral' ? 'selected' : '' ?>>Moral</option>
                </select>
              </div>
            </div>

            <!-- Botones dinámicos -->
            <div class="actions">
              <a href="empresa_documentotipo_list.php?id_empresa=<?= htmlspecialchars($empresa_id) ?>" class="btn secondary">Cancelar</a>

              <?php if ($doc['activo']): ?>
                <button type="submit" class="btn primary">💾 Guardar Cambios</button>
                <a href="empresa_documentotipo_disable_action.php?id=<?= $doc_id ?>&empresa_id=<?= $empresa_id ?>" class="btn desactivar">🚫 Desactivar</a>
              <?php else: ?>
                <a href="empresa_documentotipo_reactivar_action.php?id=<?= $doc_id ?>&empresa_id=<?= $empresa_id ?>" class="btn activar">✅ Reactivar</a>
                <span class="badge inactivo">Documento inactivo</span>
              <?php endif; ?>
            </div>
          </form>
        </div>
      </section>

      <!-- ℹ️ Información -->
      <section class="card" style="margin-top: 20px;">
        <header>ℹ️ Información</header>
        <div class="content">
          <p>
            Los <strong>documentos individuales</strong> permiten agregar requisitos personalizados para una empresa específica.
          </p>
          <p>
            Si un documento se <strong>desactiva</strong>, deja de ser visible para la empresa, pero su historial de carga y revisión permanece en el sistema.
          </p>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
