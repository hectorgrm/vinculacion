<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../common/model/db.php';
require_once __DIR__ . '/../../model/convenio/ConvenioMachoteModel.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

use Common\Model\Database;
use Dompdf\Dompdf;
use Dompdf\Options;
use Residencia\Model\Convenio\ConvenioMachoteModel;

$machoteId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($machoteId === false || $machoteId === null) {
    exit('ID de machote inválido.');
}

$connection = Database::getConnection();
$machoteModel = new ConvenioMachoteModel($connection);
$machote = $machoteModel->getById($machoteId);

if ($machote === null) {
    exit('Machote no encontrado.');
}

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

$contenido = isset($machote['contenido_html']) ? (string) $machote['contenido_html'] : '';

$html = '<html>
<head>
  <meta charset="UTF-8">
  <style>
    body {
      font-family: "DejaVu Sans", sans-serif;
      font-size: 12pt;
      line-height: 1.6;
      margin: 40px;
    }
    h1, h2, h3 { color: #333; text-align: center; }
    p { text-align: justify; }
  </style>
</head>
<body>' . $contenido . '</body>
</html>';

$dompdf->loadHtml($html);
$dompdf->setPaper('letter', 'portrait');
$dompdf->render();

$convenioId = isset($machote['convenio_id']) ? (int) $machote['convenio_id'] : 0;
$filename = 'Machote_Convenio_' . ($convenioId > 0 ? $convenioId : $machoteId) . '.pdf';
$dompdf->stream($filename, ['Attachment' => false]);
exit;
