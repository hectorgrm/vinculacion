<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Machote del Convenio - Revisión Empresa</title>
  <link rel="stylesheet" href="../../assets/css/portal/portal_styles.css" />
  <style>
    /* ===== Machote Viewer Styles ===== */
    .machote-container {
      max-width: 1000px;
      margin: 40px auto;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .machote-header {
      text-align: center;
      margin-bottom: 40px;
    }

    .machote-header h1 {
      font-size: 28px;
      color: #2c3e50;
      margin-bottom: 10px;
    }

    .machote-header p {
      font-size: 16px;
      color: #555;
    }

    .clausula {
      background: #f9fafc;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      padding: 20px;
      margin-bottom: 25px;
      position: relative;
    }

    .clausula h3 {
      font-size: 20px;
      margin: 0 0 10px;
      color: #1f2937;
    }

    .clausula p {
      font-size: 15px;
      color: #374151;
      line-height: 1.6;
      margin-bottom: 15px;
    }

    .comment-box {
      margin-top: 10px;
    }

    .comment-box textarea {
      width: 100%;
      padding: 10px;
      font-size: 14px;
      border-radius: 6px;
      border: 1px solid #d1d5db;
      resize: vertical;
      min-height: 70px;
      outline: none;
      transition: border 0.3s;
    }

    .comment-box textarea:focus {
      border: 1px solid #3b82f6;
      box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }

    .comment-box .btn-group {
      display: flex;
      gap: 10px;
      margin-top: 10px;
    }

    .btn-approve {
      background: #16a34a;
      color: #fff;
      border: none;
      padding: 8px 18px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 600;
      transition: background 0.3s;
    }

    .btn-approve:hover {
      background: #15803d;
    }

    .btn-comment {
      background: #2563eb;
      color: #fff;
      border: none;
      padding: 8px 18px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 600;
      transition: background 0.3s;
    }

    .btn-comment:hover {
      background: #1d4ed8;
    }

    .status-tag {
      position: absolute;
      top: 20px;
      right: 20px;
      padding: 5px 12px;
      font-size: 13px;
      border-radius: 20px;
      font-weight: bold;
    }

    .status-pendiente {
      background: #fef9c3;
      color: #92400e;
    }

    .status-resuelto {
      background: #dcfce7;
      color: #166534;
    }

    .submit-section {
      text-align: center;
      margin-top: 40px;
    }

    .submit-section button {
      background: #3b82f6;
      color: #fff;
      border: none;
      padding: 14px 40px;
      border-radius: 8px;
      font-size: 18px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .submit-section button:hover {
      background: #2563eb;
    }
  </style>
</head>
<body>

  <header>
    <h1>Portal de Convenios - Empresa</h1>
    <nav class="breadcrumb">
      <a href="portal_dashboard.php">Inicio</a> › <a href="portal_documentos.php">Documentos</a> › <span>Machote</span>
    </nav>
  </header>

  <main>
    <div class="machote-container">
      <div class="machote-header">
        <h1>Convenio de Colaboración</h1>
        <p>Por favor revise cada cláusula y apruébela o agregue un comentario.</p>
      </div>

      <form action="procesar_comentarios.php" method="POST">

        <!-- 🧾 Clausula 1 -->
        <div class="clausula">
          <span class="status-tag status-pendiente">Pendiente</span>
          <h3>Cláusula 1 - Objeto del Convenio</h3>
          <p>El presente convenio tiene como objeto establecer las bases de colaboración para que estudiantes del Instituto Tecnológico de Tequila realicen su residencia profesional en la empresa...</p>
          <div class="comment-box">
            <textarea name="comentario_1" placeholder="Escribe un comentario si deseas sugerir cambios..."></textarea>
            <div class="btn-group">
              <button type="button" class="btn-approve">✅ Aprobar</button>
              <button type="submit" class="btn-comment">💬 Enviar comentario</button>
            </div>
          </div>
        </div>

        <!-- 🧾 Clausula 2 -->
        <div class="clausula">
          <span class="status-tag status-resuelto">Aprobada</span>
          <h3>Cláusula 2 - Duración</h3>
          <p>El presente convenio tendrá una duración de un año, contado a partir de la fecha de firma, con posibilidad de renovación por acuerdo de ambas partes...</p>
          <div class="comment-box">
            <textarea name="comentario_2" placeholder="Escribe un comentario si deseas sugerir cambios..."></textarea>
            <div class="btn-group">
              <button type="button" class="btn-approve">✅ Aprobar</button>
              <button type="submit" class="btn-comment">💬 Enviar comentario</button>
            </div>
          </div>
        </div>

        <!-- 🧾 Clausula 3 -->
        <div class="clausula">
          <span class="status-tag status-pendiente">Pendiente</span>
          <h3>Cláusula 3 - Obligaciones</h3>
          <p>La empresa se compromete a proporcionar los recursos necesarios para que el estudiante cumpla con los objetivos académicos establecidos por el instituto...</p>
          <div class="comment-box">
            <textarea name="comentario_3" placeholder="Escribe un comentario si deseas sugerir cambios..."></textarea>
            <div class="btn-group">
              <button type="button" class="btn-approve">✅ Aprobar</button>
              <button type="submit" class="btn-comment">💬 Enviar comentario</button>
            </div>
          </div>
        </div>

        <div class="submit-section">
          <button type="submit">📤 Enviar revisión completa</button>
        </div>
      </form>
    </div>
  </main>

</body>
</html>
