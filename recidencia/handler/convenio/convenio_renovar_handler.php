<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/helpers/convenio/convenio_helper_renovar.php';
require_once __DIR__ . '/../../controller/convenio/ConvenioRenovarController.php';

use Residencia\Controller\Convenio\ConvenioRenovarController;

$controller = null;
$controllerError = null;

try {
    $controller = new ConvenioRenovarController();
} catch (Throwable $throwable) {
    $message = trim($throwable->getMessage());
    $controllerError = $message !== ''
        ? $message
        : 'No se pudo establecer conexión con la base de datos. Intenta nuevamente más tarde.';
}

if ($controller !== null) {
    return $controller->handle($_GET, $_POST, $_SERVER);
}

$method = isset($_SERVER['REQUEST_METHOD'])
    ? strtoupper((string) $_SERVER['REQUEST_METHOD'])
    : 'GET';

$copyId = null;

if ($method === 'POST' && isset($_POST['copy_id']) && is_scalar($_POST['copy_id'])) {
    $filtered = preg_replace('/[^0-9]/', '', (string) $_POST['copy_id']);
    $copyId = $filtered !== null && $filtered !== '' ? (int) $filtered : null;
} elseif (isset($_GET['copy']) && is_scalar($_GET['copy'])) {
    $filtered = preg_replace('/[^0-9]/', '', (string) $_GET['copy']);
    $copyId = $filtered !== null && $filtered !== '' ? (int) $filtered : null;
}

$empresaId = null;

if (isset($_GET['empresa']) && is_scalar($_GET['empresa'])) {
    $filtered = preg_replace('/[^0-9]/', '', (string) $_GET['empresa']);

    if ($filtered !== null && $filtered !== '') {
        $empresaId = (int) $filtered;
    }
}

$cancelLink = $copyId !== null
    ? 'convenio_view.php?id=' . urlencode((string) $copyId)
    : 'convenio_list.php';

$errors = [];

if ($method === 'POST') {
    $errors[] = $controllerError
        ?? 'No se pudo procesar la solicitud de renovación. Intenta nuevamente más tarde.';
}

$controllerError = $controllerError
    ?? 'No se pudo establecer conexión con la base de datos. Intenta nuevamente más tarde.';

return [
    'controllerAvailable' => false,
    'controllerError' => $controllerError,
    'errors' => $errors,
    'successMessage' => null,
    'formData' => convenioRenewalFormDefaults(),
    'originalConvenio' => null,
    'originalMetadata' => convenioRenewalPrepareOriginalMetadata(null),
    'empresaId' => $empresaId,
    'empresaLink' => $empresaId !== null ? '../empresa/empresa_view.php?id=' . urlencode((string) $empresaId) : null,
    'cancelLink' => $cancelLink,
    'listLink' => 'convenio_list.php',
    'copyId' => $copyId,
    'renewalAllowed' => false,
    'renewalWarning' => null,
    'allowedStatuses' => convenioRenewalAllowedStatuses(),
    'newConvenioId' => null,
    'newConvenioUrl' => null,
];
