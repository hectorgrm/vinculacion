<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/conveniofunction.php';
require_once __DIR__ . '/../../common/functions/convenio/conveniofunctions_add.php';
require_once __DIR__ . '/../../controller/convenio/ConvenioAddController.php';

use Residencia\Controller\Convenio\ConvenioAddController;

$controller = null;
$controllerError = null;

try {
    $controller = new ConvenioAddController();
} catch (\Throwable $throwable) {
    $message = trim($throwable->getMessage());
    $controllerError = $message !== ''
        ? $message
        : 'No se pudo establecer conexión con la base de datos. Intenta nuevamente más tarde.';
}

if ($controller !== null) {
    $viewData = $controller->handle($_POST, $_FILES, $_SERVER, $_GET);
    $createdEmpresaId = isset($viewData['createdEmpresaId']) ? $viewData['createdEmpresaId'] : null;
    $successMessage = isset($viewData['successMessage']) ? $viewData['successMessage'] : null;

    if (
        $createdEmpresaId !== null &&
        $successMessage !== null &&
        $successMessage !== ''
    ) {
        $redirectUrl = '../empresa/empresa_view.php?id='
            . rawurlencode((string) $createdEmpresaId)
            . '&success_message='
            . rawurlencode($successMessage);

        header('Location: ' . $redirectUrl);
        exit;
    }
} else {
    $viewData = [
        'estatusOptions' => convenioStatusOptions(),
        'empresaOptions' => [],
        'formData' => convenioFormDefaults(),
        'errors' => [],
        'successMessage' => null,
        'controllerError' => $controllerError,
        'controllerAvailable' => false,
        'empresaLockedId' => null,
    ];

    $method = isset($_SERVER['REQUEST_METHOD'])
        ? strtoupper((string) $_SERVER['REQUEST_METHOD'])
        : 'GET';

    if ($method === 'POST') {
        $viewData['errors'][] = $controllerError
            ?? 'No se pudo procesar la solicitud. Intenta nuevamente más tarde.';
    }
}

return $viewData;
