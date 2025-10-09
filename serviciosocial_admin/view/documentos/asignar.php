<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Asignación de Documentos · Servicio Social</title>
      <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../assets/css/asignaciones/asignacionadd.css">


  </head>
<body>
  <div class="app">
    <aside class="sidebar">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>
    
    </aside>

    <main class="main">
      <header class="topbar">
        <h2>📄 Asignar Documentos – María López</h2>
        <a href="../estudiantes/profile.html?id=2030456" class="btn secondary">⬅ Volver al perfil</a>
      </header>

      <!-- 📁 Lista de documentos disponibles -->
      <section class="card">
        <header>Documentos disponibles para asignación</header>
        <div class="content">
          <form>
            <table>
              <thead>
                <tr>
                  <th class="checkbox">Asignar</th>
                  <th>Documento</th>
                  <th>Descripción</th>
                  <th>Requiere entrega</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="checkbox"><input type="checkbox" name="doc[]" value="1" /></td>
                  <td>Carta de Presentación</td>
                  <td>Documento oficial que introduce al estudiante en la empresa.</td>
                  <td>Sí</td>
                </tr>
                <tr>
                  <td class="checkbox"><input type="checkbox" name="doc[]" value="2" /></td>
                  <td>Plan de Trabajo</td>
                  <td>Detalle de las actividades que el estudiante realizará.</td>
                  <td>Sí</td>
                </tr>
                <tr>
                  <td class="checkbox"><input type="checkbox" name="doc[]" value="3" /></td>
                  <td>Informe Parcial</td>
                  <td>Reporte de avance que debe entregarse en el periodo 1.</td>
                  <td>Sí</td>
                </tr>
                <tr>
                  <td class="checkbox"><input type="checkbox" name="doc[]" value="4" /></td>
                  <td>Evaluación Final</td>
                  <td>Evaluación de desempeño realizada por la empresa.</td>
                  <td>Sí</td>
                </tr>
              </tbody>
            </table>
            <div class="actions">
              <button type="submit" class="btn primary">📎 Asignar Documentos Seleccionados</button>
              <a href="../estudiantes/profile.html?id=2030456" class="btn secondary">Cancelar</a>
            </div>
          </form>
        </div>
      </section>

      <!-- 📂 Documentos ya asignados -->
      <section class="card">
        <header>Documentos asignados</header>
        <div class="content">
          <table>
            <thead>
              <tr>
                <th>Documento</th>
                <th>Estado</th>
                <th>Fecha de entrega</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Carta de Presentación</td>
                <td>📥 Pendiente de entrega</td>
                <td>-</td>
              </tr>
              <tr>
                <td>Plan de Trabajo</td>
                <td>✅ Entregado</td>
                <td>20 Feb 2025</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
    </main>
  </div>
</body>
</html>