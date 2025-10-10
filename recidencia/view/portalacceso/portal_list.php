<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Portal de Acceso · Residencias Profesionales</title>

  <!-- Estilos globales -->
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
  <link rel="stylesheet" href="../../assets/css/portal/portal_view.css" />

  <!-- (Opcional) Estilos específicos del módulo -->
  <!-- <link rel="stylesheet" href="../../assets/css/residencias/portal_list.css"/> -->
  <style>
    /* Mini estilos para badges y pills (si no usas hoja aparte) */
    .badge {
      display: inline-block;
      padding: 4px 8px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 700;
      color: #fff;
      line-height: 1
    }

    .badge.ok {
      background: #2db980
    }

    .badge.warn {
      background: #ffb400
    }

    .badge.err {
      background: #e44848
    }

    .badge.secondary {
      background: #64748b
    }

    .pill {
      display: inline-block;
      min-width: 22px;
      text-align: center;
      padding: 4px 8px;
      border-radius: 999px;
      font-weight: 800;
      font-size: 12px;
      color: #fff
    }

    .pill.info {
      background: #1f6feb
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
        <div>
          <h2>🔐 Portal de Acceso (Empresas)</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>›</span>
            <span>Portal de Acceso</span>
          </nav>
        </div>
        <div class="actions" style="gap:10px;">
          <a class="btn primary" href="portal_add.php">➕ Crear acceso</a>
        </div>
      </header>

      <!-- Filtros -->
      <section class="card">
        <header>🔎 Filtros</header>
        <div class="content">
          <form class="form" method="get">
            <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
              <div class="field" style="min-width:260px; flex:1;">
                <label for="q">Buscar</label>
                <input id="q" name="q" type="text" placeholder="Empresa, usuario, correo…">
              </div>
              <div class="field">
                <label for="empresa">Empresa</label>
                <select id="empresa" name="empresa">
                  <option value="">Todas</option>
                  <option value="45">Casa del Barrio</option>
                  <option value="22">Tequila ECT</option>
                  <option value="31">Industrias Yakumo</option>
                </select>
              </div>
              <div class="field">
                <label for="estatus">Estatus</label>
                <select id="estatus" name="estatus">
                  <option value="">Todos</option>
                  <option value="activo">Activo</option>
                  <option value="bloqueado">Bloqueado</option>
                  <option value="inactivo">Inactivo</option>
                </select>
              </div>
              <div class="field">
                <label for="tfa">2FA</label>
                <select id="tfa" name="tfa">
                  <option value="">Todos</option>
                  <option value="1">Habilitado</option>
                  <option value="0">Deshabilitado</option>
                </select>
              </div>
              <div class="field">
                <label for="desde">Último acceso desde</label>
                <input id="desde" name="desde" type="date">
              </div>
              <div class="actions" style="margin:0;">
                <button class="btn primary" type="submit">Buscar</button>
                <a class="btn" href="portal_list.php">Limpiar</a>
              </div>
            </div>
          </form>
        </div>
      </section>

      <!-- Listado -->
      <section class="card">
        <header>📋 Accesos Registrados</header>
        <div class="content">
          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Empresa</th>
                  <th>Usuario / Correo</th>
                  <th>Rol</th>
                  <th>Estatus</th>
                  <th>2FA</th>
                  <th>Último acceso</th>
                  <th>Intentos</th>
                  <th style="min-width:300px;">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <!-- Ejemplos estáticos; luego poblar con PHP -->
                <tr>
                  <td>901</td>
                  <td><a class="btn" href="../empresa/empresa_view.php?id=45">Casa del Barrio</a></td>
                  <td>admin@casadelbarrio.mx</td>
                  <td><span class="pill info">empresa_admin</span></td>
                  <td><span class="badge ok">Activo</span></td>
                  <td><span class="badge secondary">No</span></td>
                  <td>2025-10-01 11:23</td>
                  <td>0</td>
                  <td class="actions" style="display:flex; gap:8px; flex-wrap:wrap;">
                    <a class="btn" href="portal_view.php?id=901">👁️ Ver</a>
                    <a class="btn" href="portal_edit.php?id=901">✏️ Editar</a>
                    <a class="btn" href="portal_reset.php?id=901">🔁 Reset contraseña</a>
                    <a class="btn" href="portal_toggle.php?id=901&to=bloquear">🚫 Bloquear</a>
                    <a class="btn danger" href="portal_delete.php?id=901">🗑️ Eliminar</a>
                  </td>
                </tr>
                <tr>
                  <td>902</td>
                  <td><a class="btn" href="../empresa/empresa_view.php?id=22">Tequila ECT</a></td>
                  <td>vinculacion@tequilaect.com</td>
                  <td><span class="pill info">empresa_user</span></td>
                  <td><span class="badge warn">Bloqueado</span></td>
                  <td><span class="badge ok">Sí</span></td>
                  <td>2025-09-21 09:04</td>
                  <td>5</td>
                  <td class="actions" style="display:flex; gap:8px; flex-wrap:wrap;">
                    <a class="btn" href="portal_view.php?id=902">👁️ Ver</a>
                    <a class="btn" href="portal_edit.php?id=902">✏️ Editar</a>
                    <a class="btn" href="portal_reset.php?id=902">🔁 Reset contraseña</a>
                    <a class="btn" href="portal_toggle.php?id=902&to=activar">✅ Activar</a>
                    <a class="btn danger" href="portal_delete.php?id=902">🗑️ Eliminar</a>
                  </td>
                </tr>
                <tr>
                  <td>903</td>
                  <td><a class="btn" href="../empresa/empresa_view.php?id=31">Industrias Yakumo</a></td>
                  <td>accesos@yakumo.com</td>
                  <td><span class="pill info">empresa_admin</span></td>
                  <td><span class="badge secondary">Inactivo</span></td>
                  <td><span class="badge secondary">No</span></td>
                  <td>—</td>
                  <td>0</td>
                  <td class="actions" style="display:flex; gap:8px; flex-wrap:wrap;">
                    <a class="btn" href="portal_view.php?id=903">👁️ Ver</a>
                    <a class="btn" href="portal_edit.php?id=903">✏️ Editar</a>
                    <a class="btn" href="portal_toggle.php?id=903&to=activar">✅ Activar</a>
                    <a class="btn danger" href="portal_delete.php?id=903">🗑️ Eliminar</a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Leyenda -->
          <div class="legend"
            style="margin-top:10px; color:#64748b; font-size:14px; display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <strong>Leyenda:</strong>
            <span class="badge ok">Activo</span>
            <span class="badge warn">Bloqueado</span>
            <span class="badge secondary">Inactivo</span>
          </div>
        </div>
      </section>
    </main>
  </div>
</body>

</html>