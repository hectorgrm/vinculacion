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
            <a href="documentotipo_list.php">Tipos de Documento</a><span>›</span>
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
              <!-- nombre (VARCHAR(100), UNIQUE) -->
              <div class="field">
                <label class="required" for="nombre">Nombre *</label>
                <input id="nombre" name="nombre" type="text" maxlength="100"
                       placeholder="Ej: Constancia de situación fiscal (SAT)" required>
              </div>

              <!-- descripcion (TEXT, opcional) -->
              <div class="field col-span-2">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="3"
                          placeholder="Describe brevemente el propósito del documento."></textarea>
              </div>

              <!-- obligatorio (TINYINT(1)) -->
              <div class="field">
                <label class="required" for="obligatorio">¿Obligatorio? *</label>
                <select id="obligatorio" name="obligatorio" required>
                  <option value="1" selected>Sí</option>
                  <option value="0">No</option>
                </select>
              </div>

              <!-- tipo_empresa (ENUM: fisica|moral|ambas) -->
              <div class="field">
                <label class="required" for="tipo_empresa">Tipo de empresa *</label>
                <select id="tipo_empresa" name="tipo_empresa" required>
                  <option value="ambas" selected>Ambas</option>
                  <option value="fisica">Física</option>
                  <option value="moral">Moral</option>
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
