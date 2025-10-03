<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Portal de Acceso - Residencia Profesional</title>
  <link rel="stylesheet" href="../../assets/css/portal/portalglobalstyles.css">
</head>
<body>

  <header>
    <h1>Portal de Acceso - Residencia Profesional</h1>
    <nav class="breadcrumb">
      <a href="../dashboard.php">Inicio</a> <span>›</span>
      <a href="../empresas/empresa_list.php">Empresas</a> <span>›</span>
      <a href="../convenios/convenio_list.php">Convenios</a> <span>›</span>
      <span>Portal de Acceso</span>
    </nav>
  </header>

  <main>
    <div class="card">
      <h2>Listado de Portales</h2>
      <p>Administra los accesos que se otorgan a las empresas para revisar y subir documentos.</p>

      <!-- 🔍 Filtros -->
      <form class="filters" style="display: flex; gap: 15px; margin-bottom: 20px;">
        <select name="empresa" style="flex: 1; padding: 10px;">
          <option value="">-- Filtrar por empresa --</option>
          <option value="1">Casa del Barrio</option>
          <option value="2">Tequila ECT</option>
          <option value="3">Industrias Yakumo</option>
        </select>

        <select name="estatus" style="flex: 1; padding: 10px;">
          <option value="">-- Filtrar por estatus --</option>
          <option value="activo">Activo</option>
          <option value="inactivo">Inactivo</option>
          <option value="expirado">Expirado</option>
        </select>

        <button type="submit" class="btn btn-primary">Aplicar filtros</button>
      </form>

      <!-- ➕ Nuevo acceso -->
      <div style="display: flex; justify-content: flex-end; margin-bottom: 15px;">
        <a href="portal_add.php" class="btn btn-success">+ Nuevo Acceso</a>
      </div>

      <!-- 📊 Tabla -->
      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Empresa</th>
              <th>Convenio</th>
              <th>Token</th>
              <th>NIP</th>
              <th>Estatus</th>
              <th>Expiración</th>
              <th>Creado en</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <!-- 🔁 Ejemplos estáticos (luego se reemplazan con datos de la BD) -->
            <tr>
              <td>1</td>
              <td>Casa del Barrio</td>
              <td>#1 - v1.2</td>
              <td><span class="token-box">11111111-1111-1111-1111-111111111111</span></td>
              <td>1234</td>
              <td><span class="status activo">Activo</span></td>
              <td>2025-12-31</td>
              <td>2025-09-09</td>
              <td>
                <a href="portal_edit.php?id=1" class="btn btn-primary">Editar</a>
                <a href="portal_delete.php?id=1" class="btn btn-danger">Eliminar</a>
              </td>
            </tr>

            <tr>
              <td>2</td>
              <td>Tequila ECT</td>
              <td>#2 - v1.0</td>
              <td><span class="token-box">22222222-2222-2222-2222-222222222222</span></td>
              <td>5678</td>
              <td><span class="status inactivo">Inactivo</span></td>
              <td>2025-11-15</td>
              <td>2025-09-09</td>
              <td>
                <a href="portal_edit.php?id=2" class="btn btn-primary">Editar</a>
                <a href="portal_delete.php?id=2" class="btn btn-danger">Eliminar</a>
              </td>
            </tr>

            <tr>
              <td>3</td>
              <td>Industrias Yakumo</td>
              <td>#3 - pendiente</td>
              <td><span class="token-box">33333333-3333-3333-3333-333333333333</span></td>
              <td>0000</td>
              <td><span class="status expirado">Expirado</span></td>
              <td>2025-05-10</td>
              <td>2025-09-09</td>
              <td>
                <a href="portal_edit.php?id=3" class="btn btn-primary">Editar</a>
                <a href="portal_delete.php?id=3" class="btn btn-danger">Eliminar</a>
              </td>
            </tr>

          </tbody>
        </table>
      </div>
    </div>
  </main>

</body>
</html>
