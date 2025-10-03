<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard de Reportes - Residencia Profesional</title>
  <link rel="stylesheet" href="../../assets/css/reportes/reportes_globalstyles.css">
</head>
<body>

  <!-- HEADER -->
  <header>
    <h1>📊 Dashboard de Reportes - Residencia Profesional</h1>
    <nav class="breadcrumb">
      <a href="../dashboard.php">Inicio</a> <span>›</span>
      <span>Reportes</span>
    </nav>
  </header>

  <main>

    <!-- 🔎 TARJETAS RESUMEN -->
    <section class="dashboard-cards">
      <div class="card">
        <h3>Empresas Registradas</h3>
        <div class="value">42</div>
      </div>
      <div class="card success">
        <h3>Convenios Vigentes</h3>
        <div class="value">31</div>
      </div>
      <div class="card warning">
        <h3>Convenios por Revisar</h3>
        <div class="value">7</div>
      </div>
      <div class="card danger">
        <h3>Convenios Vencidos</h3>
        <div class="value">4</div>
      </div>
    </section>

    <!-- 📈 SECCIÓN DE GRÁFICOS -->
    <section class="charts">
      <div class="chart">
        <h2>Distribución de Convenios por Estatus</h2>
        <img src="../../assets/img/charts/convenios_estatus_placeholder.png" alt="Gráfico de convenios por estatus" style="width:100%; border-radius:12px;">
      </div>
      <div class="chart">
        <h2>Documentos Subidos por Mes</h2>
        <img src="../../assets/img/charts/documentos_mes_placeholder.png" alt="Gráfico de documentos por mes" style="width:100%; border-radius:12px;">
      </div>
    </section>

    <!-- 🏢 TABLA DE EMPRESAS -->
    <section class="table-section">
      <h2>Empresas con Convenios Vigentes</h2>
      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Empresa</th>
              <th>Convenios</th>
              <th>Estado</th>
              <th>Fecha Registro</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>Casa del Barrio</td>
              <td>3</td>
              <td><span class="status vigente">Vigente</span></td>
              <td>2024-03-15</td>
              <td><a href="../empresas/empresa_edit.php?id=1" class="btn btn-primary">Ver</a></td>
            </tr>
            <tr>
              <td>2</td>
              <td>Tequila ECT</td>
              <td>2</td>
              <td><span class="status en_revision">En Revisión</span></td>
              <td>2024-06-01</td>
              <td><a href="../empresas/empresa_edit.php?id=2" class="btn btn-primary">Ver</a></td>
            </tr>
            <tr>
              <td>3</td>
              <td>Industria Yakumo</td>
              <td>1</td>
              <td><span class="status vencido">Vencido</span></td>
              <td>2023-10-22</td>
              <td><a href="../empresas/empresa_edit.php?id=3" class="btn btn-primary">Ver</a></td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

    <!-- 📑 TABLA DE DOCUMENTOS -->
    <section class="table-section">
      <h2>Documentos Recientes</h2>
      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Empresa</th>
              <th>Tipo de Documento</th>
              <th>Estatus</th>
              <th>Fecha de Carga</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>15</td>
              <td>Casa del Barrio</td>
              <td>Acta Constitutiva</td>
              <td><span class="status aprobado">Aprobado</span></td>
              <td>2025-09-15</td>
              <td><a href="../documentos/documento_edit.php?id=15" class="btn btn-primary">Ver</a></td>
            </tr>
            <tr>
              <td>16</td>
              <td>Tequila ECT</td>
              <td>Comprobante de Domicilio</td>
              <td><span class="status pendiente">Pendiente</span></td>
              <td>2025-09-20</td>
              <td><a href="../documentos/documento_edit.php?id=16" class="btn btn-primary">Ver</a></td>
            </tr>
            <tr>
              <td>17</td>
              <td>Industria Yakumo</td>
              <td>INE Representante</td>
              <td><span class="status rechazado">Rechazado</span></td>
              <td>2025-09-25</td>
              <td><a href="../documentos/documento_edit.php?id=17" class="btn btn-primary">Ver</a></td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

  </main>

</body>
</html>
