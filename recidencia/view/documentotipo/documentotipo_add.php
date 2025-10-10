<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Nuevo Tipo de Documento · Residencias</title>

  <link rel="stylesheet" href="../../assets/stylesrecidencia.css"/>
  <link rel="stylesheet" href="../../assets/css/documentotipo/documentotipo.css"/>
</head>
<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div>
          <h2>➕ Nuevo Tipo de Documento</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a><span>›</span>
            <a href="documentotipo_list.php">Documento Tipo</a><span>›</span>
            <span>Nuevo</span>
          </nav>
        </div>
        <a href="documentotipo_list.php" class="btn">⬅ Volver</a>
      </header>

      <section class="card">
        <header>🧾 Datos</header>
        <div class="content">
          <form class="form" method="post" action="documentotipo_add_action.php">
            <div class="grid">
              <div class="field">
                <label class="required" for="clave">Clave *</label>
                <input id="clave" name="clave" type="text" placeholder="Ej: INE, ACTA, ANEXO" required>
              </div>
              <div class="field">
                <label class="required" for="nombre">Nombre *</label>
                <input id="nombre" name="nombre" type="text" placeholder="Nombre descriptivo" required>
              </div>

              <div class="field col-span-2">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="3" placeholder="Uso, contexto, notas internas…"></textarea>
              </div>

              <div class="field">
                <label class="required" for="requiere_convenio">Requiere convenio *</label>
                <select id="requiere_convenio" name="requiere_convenio" required>
                  <option value="0">No</option>
                  <option value="1">Sí</option>
                </select>
              </div>

              <div class="field">
                <label class="required" for="obligatorio">Obligatorio *</label>
                <select id="obligatorio" name="obligatorio" required>
                  <option value="0">No</option>
                  <option value="1">Sí</option>
                </select>
              </div>

              <div class="field">
                <label class="required" for="estatus">Estatus *</label>
                <select id="estatus" name="estatus" required>
                  <option value="activo">Activo</option>
                  <option value="inactivo">Inactivo</option>
                </select>
              </div>
            </div>

            <div class="actions">
              <a class="btn" href="documentotipo_list.php">Cancelar</a>
              <button class="btn primary" type="submit">💾 Guardar</button>
            </div>
          </form>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
