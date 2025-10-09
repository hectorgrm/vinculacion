<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>🪄 Alta Completa – Servicio Social</title>
<style>
  body {
    font-family: 'Segoe UI', Tahoma, sans-serif;
    margin: 0;
    background: #f4f6f9;
    color: #333;
  }

  .app {
    display: flex;
    min-height: 100vh;
  }

  /* SIDEBAR */
  .sidebar {
    width: 240px;
    background: #1e2a38;
    color: #fff;
    padding: 30px 20px;
  }
  .sidebar h2 { margin-bottom: 20px; font-size: 22px; }
  .sidebar nav a {
    display: block;
    color: #cdd9e5;
    text-decoration: none;
    padding: 10px 0;
    border-left: 3px solid transparent;
  }
  .sidebar nav a.active,
  .sidebar nav a:hover {
    border-left: 3px solid #00a8ff;
    color: #fff;
    padding-left: 10px;
  }

  /* MAIN */
  .main {
    flex: 1;
    padding: 40px;
  }
  .main h1 {
    margin-top: 0;
    font-size: 28px;
    color: #2c3e50;
  }

  /* WIZARD */
  .step {
    display: none;
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    animation: fadeIn 0.4s ease;
  }
  .step.active { display: block; }

  .step h2 {
    margin-top: 0;
    border-left: 5px solid #00a8ff;
    padding-left: 10px;
    color: #2c3e50;
  }

  .form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 20px;
    margin-top: 20px;
  }
  label { font-weight: 600; display: block; margin-bottom: 6px; }
  input, select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 15px;
  }

  /* NAVIGATION BUTTONS */
  .navigation {
    display: flex;
    justify-content: space-between;
    margin-top: 30px;
  }
  .btn {
    padding: 12px 25px;
    font-size: 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
  }
  .btn.prev {
    background: #ccc;
    color: #333;
  }
  .btn.next {
    background: #00a8ff;
    color: #fff;
  }
  .btn.next:hover { background: #0093dd; }
  .btn.prev:hover { background: #b3b3b3; }

  /* PROGRESS BAR */
  .progress-container {
    display: flex;
    margin-bottom: 30px;
    justify-content: space-between;
  }
  .progress-step {
    flex: 1;
    text-align: center;
    font-size: 14px;
    position: relative;
  }
  .progress-step:before {
    content: "";
    display: block;
    width: 20px;
    height: 20px;
    margin: 0 auto 10px;
    background: #ccc;
    border-radius: 50%;
  }
  .progress-step.active:before {
    background: #00a8ff;
  }
  .progress-step::after {
    content: "";
    position: absolute;
    top: 10px;
    left: 50%;
    width: 100%;
    height: 4px;
    background: #ccc;
    z-index: -1;
  }
  .progress-step:last-child::after { display: none; }
  .progress-step.active ~ .progress-step::before {
    background: #ccc;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
  }
</style>
</head>
<body>

<div class="app">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <h2>Servicio Social</h2>
    <nav>
      <a href="../estudiantes/list.php">👩‍🎓 Estudiantes</a>
      <a href="#" class="active">🪄 Alta Completa</a>
    </nav>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="main">
    <h1>🪄 Asistente de Alta Completa</h1>

    <!-- Progress bar -->
    <div class="progress-container">
      <div class="progress-step active">Estudiante</div>
      <div class="progress-step">Empresa</div>
      <div class="progress-step">Convenio</div>
      <div class="progress-step">Plaza</div>
      <div class="progress-step">Servicio</div>
      <div class="progress-step">Periodo</div>
      <div class="progress-step">Documentos</div>
    </div>

    <!-- PASO 1 -->
    <section class="step active" id="step-1">
      <h2>1️⃣ Datos del Estudiante</h2>
      <div class="form-grid">
        <div><label>Nombre</label><input type="text"></div>
        <div><label>Matrícula</label><input type="text"></div>
        <div><label>Carrera</label><input type="text"></div>
        <div><label>Correo</label><input type="email"></div>
      </div>
      <div class="navigation">
        <button class="btn next" onclick="nextStep()">Siguiente ➡️</button>
      </div>
    </section>

    <!-- PASO 2 -->
    <section class="step" id="step-2">
      <h2>2️⃣ Empresa</h2>
      <div class="form-grid">
        <div><label>Nombre Empresa</label><input type="text"></div>
        <div><label>Giro</label><input type="text"></div>
      </div>
      <div class="navigation">
        <button class="btn prev" onclick="prevStep()">⬅️ Anterior</button>
        <button class="btn next" onclick="nextStep()">Siguiente ➡️</button>
      </div>
    </section>

    <!-- PASO 3 -->
    <section class="step" id="step-3">
      <h2>3️⃣ Convenio</h2>
      <div class="form-grid">
        <div><label>Clave convenio</label><input type="text"></div>
        <div><label>Fecha inicio</label><input type="date"></div>
        <div><label>Fecha fin</label><input type="date"></div>
      </div>
      <div class="navigation">
        <button class="btn prev" onclick="prevStep()">⬅️ Anterior</button>
        <button class="btn next" onclick="nextStep()">Siguiente ➡️</button>
      </div>
    </section>

    <!-- PASO 4 -->
    <section class="step" id="step-4">
      <h2>4️⃣ Plaza</h2>
      <div class="form-grid">
        <div><label>Área</label><input type="text"></div>
        <div><label>Cupo</label><input type="number"></div>
      </div>
      <div class="navigation">
        <button class="btn prev" onclick="prevStep()">⬅️ Anterior</button>
        <button class="btn next" onclick="nextStep()">Siguiente ➡️</button>
      </div>
    </section>

    <!-- PASO 5 -->
    <section class="step" id="step-5">
      <h2>5️⃣ Crear Servicio</h2>
      <div class="form-grid">
        <div><label>Estado del servicio</label>
          <select>
            <option>Activo</option>
            <option>Pendiente</option>
          </select>
        </div>
      </div>
      <div class="navigation">
        <button class="btn prev" onclick="prevStep()">⬅️ Anterior</button>
        <button class="btn next" onclick="nextStep()">Siguiente ➡️</button>
      </div>
    </section>

    <!-- PASO 6 -->
    <section class="step" id="step-6">
      <h2>6️⃣ Crear Periodo</h2>
      <div class="form-grid">
        <div><label>Número de periodo</label><input type="number"></div>
        <div><label>Estatus</label><select><option>Abierto</option><option>En revisión</option></select></div>
      </div>
      <div class="navigation">
        <button class="btn prev" onclick="prevStep()">⬅️ Anterior</button>
        <button class="btn next" onclick="nextStep()">Siguiente ➡️</button>
      </div>
    </section>

    <!-- PASO 7 -->
    <section class="step" id="step-7">
      <h2>7️⃣ Asignar Documentos</h2>
      <div class="form-grid">
        <label><input type="checkbox"> Carta de Presentación</label>
        <label><input type="checkbox"> Carta de Aceptación</label>
        <label><input type="checkbox"> Reporte Final</label>
      </div>
      <div class="navigation">
        <button class="btn prev" onclick="prevStep()">⬅️ Anterior</button>
        <button class="btn next" onclick="alert('✅ Alta completa finalizada!')">Finalizar ✅</button>
      </div>
    </section>

  </main>
</div>

<script>
  let currentStep = 0;
  const steps = document.querySelectorAll('.step');
  const progressSteps = document.querySelectorAll('.progress-step');

  function showStep() {
    steps.forEach((s, i) => s.classList.toggle('active', i === currentStep));
    progressSteps.forEach((p, i) => p.classList.toggle('active', i <= currentStep));
  }

  function nextStep() {
    if (currentStep < steps.length - 1) {
      currentStep++;
      showStep();
    }
  }

  function prevStep() {
    if (currentStep > 0) {
      currentStep--;
      showStep();
    }
  }
</script>

</body>
</html>
