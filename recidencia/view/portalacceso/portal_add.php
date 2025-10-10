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
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
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
                  <option value="45">Casa del Barrio</option>
                  <option value="22">Tequila ECT</option>
                  <option value="31">Industrias Yakumo</option>
                  <!-- 🔁 Poblado dinámicamente desde BD -->
                </select>
              </div>

              <!-- Correo / usuario -->
              <div class="field">
                <label for="email" class="required">Correo (usuario) *</label>
                <input id="email" name="email" type="email" placeholder="ej: admin@empresa.com" required>
                <div class="hint">Se usará como usuario de acceso al portal.</div>
              </div>

              <!-- Rol -->
              <div class="field">
                <label for="rol" class="required">Rol *</label>
                <select id="rol" name="rol" required>
                  <option value="empresa_admin">empresa_admin</option>
                  <option value="empresa_user">empresa_user</option>
                </select>
              </div>

              <!-- Estatus -->
              <div class="field">
                <label for="estatus" class="required">Estatus *</label>
                <select id="estatus" name="estatus" required>
                  <option value="activo">Activo</option>
                  <option value="inactivo">Inactivo</option>
                  <option value="bloqueado">Bloqueado</option>
                </select>
              </div>

              <!-- 2FA -->
              <div class="field">
                <label for="tfa" class="required">Autenticación 2FA *</label>
                <select id="tfa" name="tfa" required>
                  <option value="0">Deshabilitado</option>
                  <option value="1">Habilitado</option>
                </select>
              </div>

              <!-- Vencimiento de contraseña -->
              <div class="field">
                <label for="pwd_expires_at">Vencimiento de contraseña</label>
                <input id="pwd_expires_at" name="pwd_expires_at" type="date">
                <div class="hint">Opcional. Si se establece, el sistema forzará cambio al llegar la fecha.</div>
              </div>

              <!-- Contraseña temporal -->
              <div class="field">
                <label for="password" class="required">Contraseña temporal *</label>
                <div class="inline">
                  <input id="password" name="password" type="password" placeholder="Mín. 10 caracteres" required style="flex:1;">
                  <button type="button" class="btn" onclick="genPwd()">🔐 Generar</button>
                  <button type="button" class="btn" onclick="togglePwd()">👁️ Mostrar</button>
                </div>
                <div class="hint">Se recomienda compartirla por un canal seguro. El usuario podrá cambiarla al iniciar sesión.</div>
              </div>

              <!-- Confirmación -->
              <div class="field">
                <label for="password2" class="required">Confirmar contraseña *</label>
                <input id="password2" name="password2" type="password" required>
              </div>

              <!-- Notas -->
              <div class="field col-span-2">
                <label for="notas">Notas</label>
                <textarea id="notas" name="notas" rows="3" placeholder="Restricciones, alcance, observaciones internas…"></textarea>
              </div>

              <!-- Envío de instrucciones -->
              <div class="field col-span-2">
                <label style="display:flex; gap:10px; align-items:flex-start;">
                  <input type="checkbox" name="enviar_instrucciones" value="1" checked>
                  <span>Enviar instrucciones de acceso por correo al usuario.</span>
                </label>
              </div>
            </div>

            <div class="actions">
              <a class="btn" href="portal_list.php">Cancelar</a>
              <button class="btn primary" type="submit">💾 Crear acceso</button>
            </div>
          </form>
        </div>
      </section>

      <!-- Accesos rápidos -->
      <section class="card">
        <header>Accesos rápidos</header>
        <div class="content" style="display:flex; gap:8px; flex-wrap:wrap;">
          <a class="btn" href="../empresa/empresa_list.php">🏢 Empresas</a>
          <a class="btn" href="../empresa/empresa_view.php?id=45">🏢 Ver empresa #45 (ej.)</a>
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
