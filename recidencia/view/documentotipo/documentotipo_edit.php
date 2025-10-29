<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Tipo de Documento · Residencias Profesionales</title>

  <!-- Estilos globales -->
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
  <link rel="stylesheet" href="../../assets/css/documentotipo/documentotipo.css" />


</head>

<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <!-- 🔝 Header -->
      <header class="topbar">
        <div>
          <h2>✏️ Editar Tipo de Documento</h2>
          <p class="subtitle">Modifica la información del documento institucional solicitado por Vinculación.</p>
        </div>
        <a href="documentotipo_list.php" class="btn secondary">⬅ Volver al listado</a>
      </header>

      <!-- 🧾 Formulario -->
      <section class="card">
        <header>📄 Datos del Documento</header>
        <div class="content">
          <form action="" method="POST">
            <div class="form-grid">
              <div class="field">
                <label for="nombre">Nombre del documento</label>
                <input type="text" id="nombre" name="nombre" placeholder="Ejemplo: INE del representante legal" value="INE del representante legal" required>
              </div>

              <div class="field">
                <label for="tipo_empresa">Tipo de empresa</label>
                <select id="tipo_empresa" name="tipo_empresa" required>
                  <option value="fisica">Persona Física</option>
                  <option value="moral" selected>Persona Moral</option>
                  <option value="ambas">Ambas</option>
                </select>
              </div>

              <div class="field">
                <label for="obligatorio">¿Es obligatorio?</label>
                <select id="obligatorio" name="obligatorio">
                  <option value="1" selected>Sí</option>
                  <option value="0">No</option>
                </select>
              </div>

              <div class="field full">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" placeholder="Breve descripción del documento...">Identificación oficial vigente del representante legal de la empresa.</textarea>
              </div>
            </div>

            <div class="actions">
              <a href="documentotipo_list.php" class="btn secondary">Cancelar</a>
              <button type="submit" class="btn primary">💾 Guardar Cambios</button>
            </div>
          </form>
        </div>
      </section>

      <!-- ℹ️ Nota -->
      <section class="card" style="margin-top: 20px;">
        <header>ℹ️ Información</header>
        <div class="content">
          <p>
            Los <strong>tipos de documento</strong> representan los requisitos oficiales definidos por el Departamento de Vinculación.
            Solo el personal autorizado puede modificarlos.
          </p>
          <p>
            Estos cambios impactarán en los requerimientos visibles para las empresas al subir su documentación.
          </p>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
