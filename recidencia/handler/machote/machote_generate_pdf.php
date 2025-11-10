<?php
declare(strict_types=1);

require_once __DIR__ . '/../../model/Conexion.php';
require_once __DIR__ . '/../../model/convenio/ConvenioMachoteModel.php';
require_once __DIR__ . '/../../../vendor/autoload.php'; // ruta a dompdf/autoload.php

use Dompdf\Dompdf;
use Dompdf\Options;

// Validar ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die('ID de machote inválido.');
}

// Obtener machote hijo
$machote = ConvenioMachoteModel::getById($id);
if (!$machote) {
    die('Machote no encontrado.');
}

// Configuración de Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// Contenido del machote
$html = '
<html>
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
<body>
' . $machote['contenido_html'] . '
</body>
</html>';

// Cargar y generar PDF
$dompdf->loadHtml($html);
$dompdf->setPaper('letter', 'portrait');
$dompdf->render();

// Descargar o mostrar
$filename = 'Machote_Convenio_' . $machote['convenio_id'] . '.pdf';
$dompdf->stream($filename, ["Attachment" => false]);
exit;
