<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Documento Institucional · Versión Final</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
    body {
        margin: 0;
        font-family: "Inter", Arial, sans-serif;
        background: #f8fafc;
        color: #1e293b;
    }

    header {
        background: white;
        border-bottom: 1px solid #e2e8f0;
        padding: 16px 24px;
    }

    header h1 {
        margin: 0;
        font-size: 22px;
        font-weight: 600;
        color: #334155;
    }

    .container {
        max-width: 1100px;
        margin: 24px auto;
        padding: 0 20px;
    }

    .card {
        background: #fff;
        padding: 20px 24px;
        border-radius: 14px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.06);
        border: 1px solid #e2e8f0;
        margin-bottom: 24px;
    }

    .meta {
        font-size: 14px;
        color: #64748b;
        margin-top: 4px;
        line-height: 1.4;
    }

    .badge {
        display: inline-block;
        padding: 4px 10px;
        font-size: 13px;
        border-radius: 8px;
        font-weight: 600;
    }
    .badge.ok { background: #dcfce7; color: #166534; }

    .actions {
        margin-top: 16px;
    }

    .btn {
        display: inline-block;
        padding: 10px 18px;
        font-size: 14px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: 0.2s;
        margin-right: 8px;
    }

    .btn.primary {
        background: #2563eb;
        color: white;
    }
    .btn.primary:hover { background: #1d4ed8; }

    .btn.outline {
        background: white;
        border: 1px solid #cbd5e1;
        color: #334155;
    }
    .btn.outline:hover { background: #f1f5f9; }

    .pdf-viewer {
        width: 100%;
        height: 90vh;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        margin-top: 16px;
    }

</style>
</head>
<body>

<header>
    <h1>Documento Institucional · Versión Final Aprobada</h1>
</header>

<div class="container">

    <section class="card">
        <h2 style="margin:0;font-size:18px;font-weight:600;color:#1e293b;">
            Convenio — Versión oficial para firma
        </h2>

        <p class="meta">
            <strong>Versión:</strong> v1.0<br>
            <strong>Estado:</strong> <span class="badge ok">Aprobado</span><br>
            <strong>Aprobado por:</strong> Departamento de Residencias<br>
            <strong>Fecha de aprobación:</strong> 15/11/2025 10:42<br>
        </p>

        <div class="actions">
            <a href="#" class="btn primary">⬇️ Descargar PDF</a>
            <a href="index.php" class="btn outline">⟵ Volver al portal</a>
        </div>
    </section>

    <section class="card">
        <iframe class="pdf-viewer"
                src="https://example.com/archivo.pdf">
        </iframe>
    </section>

</div>

</body>
</html>
