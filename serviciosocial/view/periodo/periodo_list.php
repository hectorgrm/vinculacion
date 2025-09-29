<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Periodos · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/periodo/periodolist.css"/>
</head>
<body>

<header>
  <h1>Gestión de Periodos</h1>
  <nav class="breadcrumb">
    <a href="#">Inicio</a>
    <span>›</span>
    <span>Periodos</span>
  </nav>
</header>

<main>
  <div class="top-actions">
    <a href="#" class="btn-back">⬅ Volver</a>
    <a href="#" class="btn-new">➕ Nuevo Periodo</a>
  </div>

  <div class="search-bar">
    <form action="">
      <input type="text" placeholder="Buscar por servicio, número o estado..." />
      <button type="submit">Buscar</button>
    </form>
  </div>

  <table class="styled-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Servicio ID</th>
        <th>Número</th>
        <th>Estatus</th>
        <th>Apertura</th>
        <th>Cierre</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>4</td>
        <td>12345</td>
        <td>1</td>
        <td><span class="status abierto">Abierto</span></td>
        <td>01/02/2025</td>
        <td>-</td>
        <td class="actions">
          <a href="#" class="btn-view">Ver</a>
          <a href="#" class="btn-edit">Editar</a>
        </td>
      </tr>
      <tr>
        <td>5</td>
        <td>12345</td>
        <td>2</td>
        <td><span class="status en_revision">En Revisión</span></td>
        <td>01/08/2025</td>
        <td>-</td>
        <td class="actions">
          <a href="#" class="btn-view">Ver</a>
          <a href="#" class="btn-edit">Editar</a>
        </td>
      </tr>
      <tr>
        <td>6</td>
        <td>12345</td>
        <td>3</td>
        <td><span class="status completado">Completado</span></td>
        <td>01/01/2024</td>
        <td>30/06/2024</td>
        <td class="actions">
          <a href="#" class="btn-view">Ver</a>
          <a href="#" class="btn-edit">Editar</a>
        </td>
      </tr>
    </tbody>
  </table>
</main>

</body>
</html>
