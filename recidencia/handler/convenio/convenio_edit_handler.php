<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/auth.php';
require_once __DIR__ . '/../../common/functions/conveniofunction.php';
require_once __DIR__ . '/../../common/functions/convenio/conveniofunctions_edit.php';
require_once __DIR__ . '/../../controller/convenio/ConvenioEditController.php';

use Residencia\Controller\Convenio\ConvenioEditController;

$controller = null;
$controllerError = null;

try {
    $controller = new ConvenioEditController();
} catch (\Throwable $throwable) {
    $message = trim($throwable->getMessage());
    $controllerError = $message !== ''
        ? $message
        : 'No se pudo establecer conexión con la base de datos. Intenta nuevamente más tarde.';
}

if ($controller !== null) {
    return $controller->handle($_GET, $_POST, $_FILES, $_SERVER);
}

$method = isset($_SERVER['REQUEST_METHOD'])
    ? strtoupper((string) $_SERVER['REQUEST_METHOD'])
    : 'GET';

$convenioId = 0;

if (isset($_GET['id']) && is_scalar($_GET['id'])) {
    $filtered = preg_replace('/[^0-9]/', '', (string) $_GET['id']);
    $convenioId = $filtered !== null && $filtered !== '' ? (int) $filtered : 0;
}

if ($convenioId <= 0 && isset($_POST['id']) && is_scalar($_POST['id'])) {
    $filtered = preg_replace('/[^0-9]/', '', (string) $_POST['id']);
    $convenioId = $filtered !== null && $filtered !== '' ? (int) $filtered : 0;
}

$fallbackError = $controllerError
    ?? 'No se pudo establecer conexión con la base de datos. Intenta nuevamente más tarde.';

$errors = [];

if ($method === 'POST') {
    $errors[] = $fallbackError;
}

$cancelLink = $convenioId > 0
    ? 'convenio_view.php?id=' . urlencode((string) $convenioId)
    : 'convenio_list.php';

return [
    'estatusOptions' => convenioStatusOptions(),
    'controllerError' => $fallbackError,
    'controllerAvailable' => false,
    'errors' => $errors,
    'successMessage' => null,
    'convenio' => null,
    'convenioId' => $convenioId,
    'formData' => convenioFormDefaults(),
    'empresaLabel' => 'Empresa no disponible',
    'empresaNumeroControl' => '',
    'empresaLink' => '#',
    'convenioListLink' => 'convenio_list.php',
    'machoteLink' => '#',
    'cancelLink' => $cancelLink,
    'borradorUrl' => null,
    'borradorFileName' => null,
    'folioLabel' => 'Sin folio asignado',
    'formDisabled' => true,
    'empresaEstatus' => '',
    'empresaIsCompletada' => false,
    'empresaIsInactiva' => false,
    'convenioArchivado' => false,
];
