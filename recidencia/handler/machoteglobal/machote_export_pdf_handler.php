<?php

declare(strict_types=1);

use Residencia\Controller\MachoteGlobal\MachoteGlobalController;

require_once __DIR__ . '/../../controller/machoteglobal/MachoteGlobalController.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// === Obtener Machote ===
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    http_response_code(400);
    echo 'ID de machote no válido.';
    exit;
}

$controller = new MachoteGlobalController();
$machote = $controller->obtenerMachote($id);

if ($machote === null) {
    http_response_code(404);
    echo 'Machote no encontrado.';
    exit;
}

$titulo = 'Machote - ' . htmlspecialchars($machote['version'] ?? ('ID ' . $id));
$htmlContent = (string)($machote['contenido_html'] ?? '');
if (trim($htmlContent) === '') {
    $htmlContent = '<p>Sin contenido.</p>';
}

// === Configurar Dompdf ===
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'DejaVu Sans'); // Fuente con soporte completo UTF-8

$dompdf = new Dompdf($options);

// === Agregar estilos CSS ===
$css = '
@page {
  margin: 3cm 2.5cm;
}
body {
  font-family: "DejaVu Serif", "Times New Roman", serif;
  font-size: 11pt;
  color: #111;
  line-height: 1.6;
  text-align: justify;
}
h1 {
  font-size: 16pt;
  font-weight: bold;
  text-transform: uppercase;
  text-align: center;
  margin: 0 0 12px 0;
}
h2 {
  font-size: 14pt;
  font-weight: bold;
  text-transform: uppercase;
  text-align: center;
  margin: 22px 0 10px 0;
}
h3 {
  font-size: 12pt;
  font-weight: bold;
  margin: 14px 0 6px 0;
}
p { margin: 0 0 10px 0; }
ul { margin: 0 0 10px 24px; }
li { margin-bottom: 4px; }
.logo-header { text-align: center; margin-bottom: 14px; }
.logo-header img { width: 100px; height: auto; }
hr { border: none; border-top: 1px solid #999; margin: 10px 0 18px 0; }
.signature-table { width: 100%; border-collapse: collapse; margin-top: 24px; font-size: 11pt; }
.signature-table td { width: 50%; text-align: center; vertical-align: top; padding: 10px; }
.footer { text-align: center; font-size: 9pt; color: #666; margin-top: 28px; }
.page-break { page-break-after: always; }

';


// === Estructurar HTML completo ===
$html = '
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>' . $css . '</style>
<title>' . $titulo . '</title>
</head>
<body>
<h1 style="text-align:center;">' . $titulo . '</h1>
<hr>
' . $htmlContent . '
<div class="footer">
  Generado desde el sistema de Vinculación · ' . date('Y-m-d H:i') . '
</div>
</body>
</html>
';

// === Generar PDF ===
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// === Mostrar PDF en navegador ===
$dompdf->stream("machote_{$id}.pdf", ["Attachment" => false]);
exit;
