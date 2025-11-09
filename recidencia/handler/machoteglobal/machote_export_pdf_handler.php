<?php
/**
 * Genera el PDF del machote global con Dompdf
 * Compatible con HTML + CSS separados y logo en base64.
 */

use Dompdf\Dompdf;
use Dompdf\Options;
use Residencia\Controller\MachoteGlobal\MachoteGlobalController;

require_once __DIR__ . '/../../controller/machoteglobal/MachoteGlobalController.php';
require_once __DIR__ . '/../../vendor/autoload.php';

// === 1. Obtener el machote seleccionado ===
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    http_response_code(400);
    exit('ID de machote inválido.');
}

$controller = new MachoteGlobalController();
$machote = $controller->obtenerMachote($id);

if (!$machote) {
    http_response_code(404);
    exit('No se encontró el machote.');
}

$htmlContent = $machote['contenido_html'] ?? '';
if (trim($htmlContent) === '') {
    $htmlContent = '<p>No hay contenido disponible para este machote.</p>';
}

// === 2. Convertir logo a Base64 (para asegurar que siempre aparezca en el PDF) ===
$logoPath = __DIR__ . '/../../assets/pdf/Logo.jpg';
$logoUri = '';
if (file_exists($logoPath)) {
    $logoData = base64_encode(file_get_contents($logoPath));
    $logoUri = 'data:image/jpeg;base64,' . $logoData;
}

// === 3. Cargar CSS institucional ===
$cssPath = __DIR__ . '/../../templates/machote_oficial_v1_content.css';
$css = file_exists($cssPath) ? file_get_contents($cssPath) : 'body{font-family:"DejaVu Serif"; font-size:11pt;}';

// === 4. Insertar el logo base64 directamente si el HTML original hace referencia al recurso institucional ===
if ($logoUri !== '') {
    $logoSearchPaths = [
        '../../assets/pdf/Logo.jpg',
        '../assets/pdf/Logo.jpg',
        './assets/pdf/Logo.jpg',
        'assets/pdf/Logo.jpg',
    ];

    $htmlContent = str_ireplace($logoSearchPaths, $logoUri, $htmlContent);
}

// === 5. Configurar Dompdf ===
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'DejaVu Serif');

$dompdf = new Dompdf($options);

// === 6. Envolver el HTML y CSS en un documento completo ===
$html = '
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<style>' . $css . '</style>
</head>
<body>
' . $htmlContent . '
</body>
</html>';

// === 7. Generar PDF ===
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// === 8. Enviar PDF al navegador ===
$pdfName = 'machote_' . $id . '.pdf';
$dompdf->stream($pdfName, ['Attachment' => false]);
exit;
