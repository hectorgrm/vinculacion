<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tipos de Documentos - Residencia Profesional</title>
  <link rel="stylesheet" href="../../assets/css/documentotipo/documentotipo_globalstyles.css">
</head>
<body>

  <header>
    <h1>Gestión de Tipos de Documentos</h1>
    <nav class="breadcrumb">
      <a href="../dashboard.php">Inicio</a> <span>›</span>
      <a href="../documentos/documento_list.php">Documentos</a> <span>›</span>
      <span>Tipos de Documentos</span>
    </nav>
  </header>

  <main>
    <div class="card">
      <h2>Listado de Tipos de Documentos</h2>
      <p>En este módulo puedes gestionar los tipos de documentos requeridos para el proceso de Residencia Profesional.</p>

      <!-- 🔎 Buscador y botón -->
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <form class="search-form" style="display: flex; gap: 10px;">
          <input type="text" placeholder="Buscar por nombre..." style="flex: 1; padding: 10px;">
          <button type="submit" class="btn btn-primary">Buscar</button>
        </form>
        <a href="documentotipo_add.php" class="btn btn-success">+ Nuevo Tipo</a>
      </div>

      <!-- 📊 Tabla -->
      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Descripción</th>
              <th>Obligatorio</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <!-- 🧪 Ejemplos estáticos (se reemplazarán con datos del backend) -->
            <tr>
              <td>1</td>
              <td>Acta Constitutiva</td>
              <td>Documento oficial que acredita la existencia legal de la empresa.</td>
              <td><span class="badge obligatorio">Sí</span></td>
              <td>
                <a href="documentotipo_edit.php?id=1" class="btn btn-primary">Editar</a>
                <a href="documentotipo_delete.php?id=1" class="btn btn-danger">Eliminar</a>
              </td>
            </tr>
            <tr>
              <td>2</td>
              <td>Comprobante de Domicilio</td>
              <td>Documento que valida la dirección fiscal de la empresa.</td>
              <td><span class="badge obligatorio">Sí</span></td>
              <td>
                <a href="documentotipo_edit.php?id=2" class="btn btn-primary">Editar</a>
                <a href="documentotipo_delete.php?id=2" class="btn btn-danger">Eliminar</a>
              </td>
            </tr>
            <tr>
              <td>3</td>
              <td>RFC de la Empresa</td>
              <td>Registro Federal de Contribuyentes para fines fiscales.</td>
              <td><span class="badge opcional">No</span></td>
              <td>
                <a href="documentotipo_edit.php?id=3" class="btn btn-primary">Editar</a>
                <a href="documentotipo_delete.php?id=3" class="btn btn-danger">Eliminar</a>
              </td>
            </tr>
            <tr>
              <td>4</td>
              <td>Identificación del Representante</td>
              <td>Copia oficial de identificación vigente del representante legal.</td>
              <td><span class="badge obligatorio">Sí</span></td>
              <td>
                <a href="documentotipo_edit.php?id=4" class="btn btn-primary">Editar</a>
                <a href="documentotipo_delete.php?id=4" class="btn btn-danger">Eliminar</a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </main>

</body>
</html>
