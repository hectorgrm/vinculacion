<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Servicio Social - Reportes</title>
  <link rel="stylesheet" href="../../assets/css/reportes/reportesglobalstyles.css">
</head>
<body>

  <header>
    <h1>📊 Reportes y Dashboard</h1>
    <nav class="breadcrumb">
      <a href="../dashboard.php">Inicio</a> <span>›</span>
      <a href="#">Módulos</a> <span>›</span>
      <span>Reportes</span>
    </nav>
  </header>

  <main>

    <!-- 📌 Resumen general -->
    <section class="stats">
      <div class="card">
        <h2>12</h2>
        <p>Empresas registradas</p>
      </div>
      <div class="card">
        <h2>8</h2>
        <p>Convenios vigentes</p>
      </div>
      <div class="card">
        <h2>45</h2>
        <p>Estudiantes activos</p>
      </div>
      <div class="card">
        <h2>18</h2>
        <p>Plazas disponibles</p>
      </div>
    </section>

    <!-- 📈 Sección de gráficos -->
    <section class="chart">
      <h3>Distribución de Estudiantes por Empresa</h3>
      <div class="chart-placeholder">
        [ Gráfico en construcción – se conectará con la BD más adelante ]
      </div>
    </section>

    <!-- 📄 Tabla resumen -->
    <section class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>Empresa</th>
            <th>Convenios</th>
            <th>Estudiantes</th>
            <th>Plazas activas</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Universidad Tecnológica del Centro</td>
            <td>3</td>
            <td>12</td>
            <td>5</td>
          </tr>
          <tr>
            <td>Hospital General San José</td>
            <td>2</td>
            <td>8</td>
            <td>4</td>
          </tr>
          <tr>
            <td>Biblioteca Municipal Central</td>
            <td>1</td>
            <td>5</td>
            <td>2</td>
          </tr>
        </tbody>
      </table>
    </section>

  </main>
</body>
</html>
