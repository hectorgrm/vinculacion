<?php
declare(strict_types=1);

use Residencia\Controller\MachoteGlobal\MachoteGlobalController;

require_once __DIR__ . '/../../../controller/machoteglobal/MachoteGlobalController.php';
require_once __DIR__ . '/../../../vendor/autoload.php'; // por si tienes composer, opcional

// 🔹 Obtener el machote
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die('ID de machote no válido.');
}

$controller = new MachoteGlobalController();
$machote = $controller->obtenerMachote($id);
if (!$machote) {
    die('Machote no encontrado.');
}

// 🔹 Preparar contenido para PDF
$titulo = 'Machote - ' . $machote['version'];
$htmlContent = $machote['contenido_html'] ?: '<p>Sin contenido.</p>';

// 🔹 Generar PDF con ReportLab (formato limpio)
require_once __DIR__ . '/../../../vendor/autoload.php';

use ReportLab\PDF\PDFDocument;
use ReportLab\PDF\PDFStyle;

$pdfFile = sys_get_temp_dir() . '/machote_' . $id . '.pdf';

// --- Aquí usamos reportlab vía el entorno interno de ChatGPT ---
use reportlab\platypus\SimpleDocTemplate;
use reportlab\platypus\Paragraph;
use reportlab\platypus\Spacer;
use reportlab\lib\styles\getSampleStyleSheet;

// Esto es pseudocódigo representativo de ReportLab, pero tú usarás dompdf o TCPDF si trabajas localmente:
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="machote_'.$id.'.pdf"');

// Generar PDF dinámicamente
require_once __DIR__ . '/../../../lib/dompdf/autoload.inc.php'; // si usas dompdf
use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->loadHtml('
  <h1 style="text-align:center;">' . htmlspecialchars($titulo) . '</h1>
  <hr>
  ' . $htmlContent . '
  <hr><p style="font-size:12px;color:#555;text-align:center;">
  Generado desde el sistema de Vinculación - ' . date('Y-m-d H:i') . '
  </p>
');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("machote_{$id}.pdf", ["Attachment" => false]);
exit;
