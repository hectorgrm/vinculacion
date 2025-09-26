<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../assets/css/gestestudiante/estudianteliststyles.css">
</head>

<body>

  <header>
    <h1>Gestión de Estudiantes · Servicio Social</h1>
  </header>

  <div class="search-bar">
    <input type="text" placeholder="Buscar por nombre, matrícula o carrera..." />
    <button>Buscar</button>
  </div>

  <main>
    <div class="top-actions">
      <h2>Lista de estudiantes registrados</h2>
      <a href="estudiante_add.html" class="btn new">+ Alta de estudiante</a>
    </div>

    <table>
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Matrícula</th>
          <th>Carrera</th>
          <th>Plaza asignada</th>
          <th>Periodo</th>
          <th>Horas</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Ana Rodríguez</td>
          <td>A012345</td>
          <td>Ing. Informática</td>
          <td>Secretaría de Innovación</td>
          <td>01/02/2025 - 31/07/2025</td>
          <td>120 / 480</td>
          <td><span class="status en_curso">En curso</span></td>
          <td class="actions">
            <a href="estudiante_view.html" class="btn view">Ver</a>
            <a href="estudiante_edit.html" class="btn edit">Editar</a>
            <button class="btn delete">Eliminar</button>
          </td>
        </tr>
        <tr>
          <td>Diego López</td>
          <td>A067890</td>
          <td>Ing. Sistemas</td>
          <td>Ayuntamiento de Tijuana</td>
          <td>05/03/2025 - 10/08/2025</td>
          <td>0 / 480</td>
          <td><span class="status pendiente">Pendiente</span></td>
          <td class="actions">
            <a href="estudiante_view.html" class="btn view">Ver</a>
            <a href="estudiante_edit.html" class="btn edit">Editar</a>
            <button class="btn delete">Eliminar</button>
          </td>
        </tr>
        <tr>
          <td>Karla Méndez</td>
          <td>A045678</td>
          <td>Ing. Gestión</td>
          <td>Centro de Innovación</td>
          <td>15/01/2025 - 30/06/2025</td>
          <td>480 / 480</td>
          <td><span class="status concluido">Concluido</span></td>
          <td class="actions">
            <a href="estudiante_view.html" class="btn view">Ver</a>
            <a href="estudiante_edit.html" class="btn edit">Editar</a>
            <button class="btn delete">Eliminar</button>
          </td>
        </tr>
      </tbody>
    </table>
  </main>

</body>
</html>