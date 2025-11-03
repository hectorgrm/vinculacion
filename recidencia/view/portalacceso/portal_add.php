<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Crear Acceso · Residencias Profesionales</title>

  <!-- Estilos globales -->
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css"/>

  <!-- (Opcional) Mini estilos locales -->
  <style>
    .hint{ color:#64748b; font-size:13px; margin-top:6px }
    .grid{ display:grid; grid-template-columns:1fr 1fr; gap:16px }
    .col-span-2{ grid-column:1/3 }
    .field label{ display:block; font-weight:700; color:#334155; margin-bottom:6px }
    .field label.required::after{ content:" *"; color:#e44848; font-weight:800 }
    .actions{ display:flex; gap:10px; justify-content:flex-end; margin-top:12px }
    .inline{ display:flex; gap:10px; align-items:center; flex-wrap:wrap }
    @media (max-width: 860px){ .grid{ grid-template-columns:1fr } .col-span-2{ grid-column:1/2 } }
  </style>
</head>
<body>
 <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div>
          <h2>➕ Crear acceso para empresa</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>›</span>
            <a href="portal_list.php">Portal de Acceso</a>
            <span>›</span>
            <span>Nuevo</span>
          </nav>
        </div>
        <a class="btn" href="portal_list.php">⬅ Volver</a>
      </header>

      <section class="card">
        <header>🧾 Datos del acceso</header>
        <div class="content">
          <form class="form" action="portal_add_action.php" method="post" autocomplete="off">
            <div class="grid">
              
              <!-- Empresa -->
              <div class="field">
                <label for="empresa_id" class="required">Empresa *</label>
                <select id="empresa_id" name="empresa_id" required>
                  <option value="">— Selecciona una empresa —</option>
                  <!-- 🔁 Poblado dinámicamente desde la BD -->
                </select>
              </div>

              <!-- Token -->
              <div class="field">
                <label for="token" class="required">Token *</label>
                <div style="display:flex; gap:10px;">
                  <input id="token" name="token" type="text" placeholder="Generar token" readonly required style="flex:1;">
                  <button type="button" class="btn" onclick="genToken()">🔑 Generar</button>
                </div>
                <div class="hint">Identificador único que se usará en la URL de acceso.</div>
              </div>

              <!-- NIP -->
              <div class="field">
                <label for="nip" class="required">NIP *</label>
                <input id="nip" name="nip" type="text" maxlength="6" placeholder="Ej: 4567" required pattern="[0-9]{4,6}">
                <div class="hint">Código corto (4–6 dígitos) que la empresa deberá ingresar.</div>
              </div>

              <!-- Activo -->
              <div class="field">
                <label for="activo" class="required">Estatus *</label>
                <select id="activo" name="activo" required>
                  <option value="1">Activo</option>
                  <option value="0">Inactivo</option>
                </select>
              </div>

              <!-- Expiración -->
              <div class="field">
                <label for="expiracion">Expiración (opcional)</label>
                <input id="expiracion" name="expiracion" type="datetime-local">
                <div class="hint">Define una fecha de vencimiento del acceso si se desea limitar su duración.</div>
              </div>

            </div>

            <div class="actions">
              <a class="btn" href="portal_list.php">Cancelar</a>
              <button class="btn primary" type="submit">💾 Crear acceso</button>
            </div>
          </form>
        </div>
      </section>

      <section class="card">
        <header>Accesos rápidos</header>
        <div class="content" style="display:flex; gap:8px; flex-wrap:wrap;">
          <a class="btn" href="../empresa/empresa_list.php">🏢 Empresas</a>
          <a class="btn" href="portal_list.php">🔐 Portal de Acceso</a>
        </div>
      </section>
    </main>
  </div>
  <script>
    function genPwd(){
      const chars = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%&*?";
      let out = "";
      for(let i=0;i<14;i++) out += chars.charAt(Math.floor(Math.random()*chars.length));
      const el = document.getElementById('password');
      const el2 = document.getElementById('password2');
      el.value = out;
      el2.value = out;
    }
    function togglePwd(){
      const p = document.getElementById('password');
      const p2 = document.getElementById('password2');
      p.type = p.type === 'password' ? 'text' : 'password';
      p2.type = p2.type === 'password' ? 'text' : 'password';
    }
  </script>
</body>
</html>
