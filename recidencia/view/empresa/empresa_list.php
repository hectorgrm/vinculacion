<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Empresas - Residencia Profesional</title>
  <link rel="stylesheet" href="../../assets/css/empresas/empresaglobalstyles.css" />
</head>
<body>

  <!-- HEADER -->
  <header>
    <h1>Empresas · Residencia Profesional</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <span>Empresas</span>
    </nav>
  </header>

  <!-- MAIN -->
  <main>
    <div class="card">
      <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>📂 Lista de Empresas Registradas</h2>
        <a href="empresa_add.php" class="btn btn-primary">➕ Registrar Nueva Empresa</a>
      </div>

      <!-- BUSCADOR -->
      <form class="form" style="margin: 20px 0;">
        <div class="field" style="max-width: 350px;">
          <label for="search">Buscar empresa:</label>
          <input type="text" id="search" name="search" placeholder="Nombre, contacto o RFC..." />
        </div>
        <div class="actions">
          <button type="submit" class="btn btn-primary">🔍 Buscar</button>
          <a href="empresa_list.php" class="btn btn-secondary">Limpiar</a>
        </div>
      </form>

      <!-- TABLA DE EMPRESAS -->
      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>RFC</th>
              <th>Contacto</th>
              <th>Email</th>
              <th>Teléfono</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <!-- 🔄 Estos registros luego se generarán dinámicamente con PHP -->
            <tr>
              <td>1</td>
              <td>Casa del Barrio</td>
              <td>CDB810101AA1</td>
              <td>José Manuel Velador</td>
              <td>contacto@casadelbarrio.mx</td>
              <td>(33) 1234 5678</td>
              <td><span class="status activo">Activo</span></td>
              <td class="actions">
                <a href="empresa_edit.php?id=1" class="btn btn-primary">✏️ Editar</a>
                <a href="empresa_delete.php?id=1" class="btn btn-danger">🗑️ Eliminar</a>
              </td>
            </tr>
            <tr>
              <td>2</td>
              <td>Tequila ECT</td>
              <td>TEC920202BB2</td>
              <td>María González</td>
              <td>legal@tequilaect.com</td>
              <td>(33) 2345 6789</td>
              <td><span class="status activo">Activo</span></td>
              <td class="actions">
                <a href="empresa_edit.php?id=2" class="btn btn-primary">✏️ Editar</a>
                <a href="empresa_delete.php?id=2" class="btn btn-danger">🗑️ Eliminar</a>
              </td>
            </tr>
            <tr>
              <td>3</td>
              <td>Industrias Yakumo</td>
              <td>IYA930303CC3</td>
              <td>Luis Pérez</td>
              <td>vinculacion@yakumo.com</td>
              <td>(55) 3456 7890</td>
              <td><span class="status activo">Activo</span></td>
              <td class="actions">
                <a href="empresa_edit.php?id=3" class="btn btn-primary">✏️ Editar</a>
                <a href="empresa_delete.php?id=3" class="btn btn-danger">🗑️ Eliminar</a>
              </td>
            </tr>
            <!-- Fin registros de ejemplo -->
          </tbody>
        </table>
      </div>
    </div>
  </main>

</body>
</html>
