<?php
/**
 * ===============================================================
 * Generador de PDF para Machote de Convenio (Hijo)
 * ---------------------------------------------------------------
 * Usa:
 *   - Logo institucional:   /assets/pdf/LogoHijo.jpg
 *   - CSS institucional:    /templates/machote_convenio_hijo.css
 * ===============================================================
 */

declare(strict_types=1);

// ===============================================================
// 1️⃣ DEPENDENCIAS Y MODELOS
// ===============================================================
require_once __DIR__ . '/../../../common/model/db.php';
require_once __DIR__ . '/../../model/convenio/ConvenioMachoteModel.php';
require_once __DIR__ . '/../../common/helpers/machote/machote_placeholders_helper.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Common\Model\Database;
use Dompdf\Dompdf;
use Dompdf\Options;
use Residencia\Model\Convenio\ConvenioMachoteModel;

// ===============================================================
// 2️⃣ VALIDAR PARÁMETRO "id"
// ===============================================================
$machoteId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$machoteId) {
    exit('⚠️ ID de machote inválido.');
}

// ===============================================================
// 3️⃣ OBTENER CONTENIDO DEL MACHOTE HIJO
// ===============================================================
$connection = Database::getConnection();
$model = new ConvenioMachoteModel($connection);
$machote = $model->getById($machoteId);

if (!$machote) {
    exit('⚠️ Machote no encontrado.');
}

$empresa = $model->getEmpresaByMachote($machoteId) ?? [];
$contenido = renderMachoteConEmpresa((string) ($machote['contenido_html'] ?? ''), $empresa);
$convenioId = (int) ($machote['convenio_id'] ?? 0);

// ===============================================================
// 4️⃣ CONFIGURACIÓN DE DOMPDF
// ===============================================================
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// ===============================================================
// 5️⃣ LOGO INSTITUCIONAL (ITSJ)
// ===============================================================
$logoPath = __DIR__ . '/../../assets/pdf/LogoHijo.jpg';
$logoBase64 = '';

if (file_exists($logoPath)) {
    $logoData = file_get_contents($logoPath);
    if ($logoData !== false) {
        $logoBase64 = 'data:image/jpeg;base64,' . base64_encode($logoData);
    }
}

// ===============================================================
// 6️⃣ CARGAR CSS DEL MACHOTE HIJO
// ===============================================================
$cssPath = __DIR__ . '/../../templates/machote_convenio_hijo.css';
$css = file_exists($cssPath)
    ? file_get_contents($cssPath)
    : 'body { font-family:"DejaVu Sans", sans-serif; font-size:12pt; margin:2cm; }';

// ===============================================================
// 7️⃣ ESTRUCTURA HTML FINAL DEL PDF
// ===============================================================
$header = '
<header class="page-header">
  <div class="logo-left">
    <img src="' . $logoBase64 . '" alt="No se cargo logo " />
  </div>
  <div class="logo-right">LOGOTIPO DE LA EMPRESA</div>
</header>';

$html = '
<html lang="es">
<head>
  <meta charset="UTF-8">
  <style>' . $css . '</style>
</head>
<body>
  ' . $header . '
  <main class="WordSection1">' . $contenido . '</main>
</body>
</html>';

// ===============================================================
// 8️⃣ GENERAR PDF Y MOSTRAR EN NAVEGADOR
// ===============================================================
$dompdf->loadHtml($html);
$dompdf->setPaper('letter', 'portrait');
$dompdf->render();

$filename = 'Machote_Convenio_' . ($convenioId ?: $machoteId) . '.pdf';
$dompdf->stream($filename, ['Attachment' => false]);
exit;
