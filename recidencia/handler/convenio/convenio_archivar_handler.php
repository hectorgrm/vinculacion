<?php
declare(strict_types=1);

require_once __DIR__ . '/../../controller/convenio/ConvenioArchivarController.php';
require_once __DIR__ . '/../../controller/convenio/ConvenioEditController.php';
require_once __DIR__ . '/../../common/helpers/convenio/convenio_archivar_helper.php';

use Residencia\Controller\Convenio\ConvenioArchivarController;
use Residencia\Controller\Convenio\ConvenioEditController;
use function Residencia\Common\Helpers\Convenio\buildArchivarViewData;
use function Residencia\Common\Helpers\Convenio\sanitizeArchivarPost;
use function Residencia\Common\Helpers\Convenio\sanitizePositiveInt;

$requestedId = sanitizePositiveInt($_GET['id'] ?? null);
$requestedEmpresaId = sanitizePositiveInt($_GET['empresa_id'] ?? null);

$archivarController = null;
$editController = null;
$controllerError = null;

try {
    $archivarController = new ConvenioArchivarController();
} catch (\Throwable $throwable) {
    $message = trim($throwable->getMessage());
    $controllerError = $message !== '' ? $message : 'No se pudo establecer conexión con la base de datos.';
}

try {
    $editController = new ConvenioEditController();
} catch (\Throwable $throwable) {
    $message = trim($throwable->getMessage());
    if ($controllerError === null) {
        $controllerError = $message !== '' ? $message : 'No se pudo establecer conexión con la base de datos.';
    }
}

$errors = [];
$successMessage = null;
$convenio = null;
$sanitizedPost = null;

if ($editController === null && $controllerError !== null) {
    $errors[] = $controllerError;
}

if ($editController !== null && $requestedId > 0) {
    try {
        $convenio = $editController->getConvenioById($requestedId);
        if ($convenio === null) {
            $errors[] = 'No se encontró el convenio solicitado.';
        }
    } catch (\Throwable $throwable) {
        $errors[] = $throwable->getMessage();
    }
}

if ($archivarController !== null && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $sanitizedPost = sanitizeArchivarPost($_POST);
    $errors = array_merge($errors, $sanitizedPost['errors']);

    if ($errors === []) {
        $result = $archivarController->archivarConvenioCascade($sanitizedPost['convenio_id'], $sanitizedPost['motivo']);
        if ($result['status'] === true) {
            $successMessage = $result['mensaje'];
            $requestedId = $sanitizedPost['convenio_id'];
            $requestedEmpresaId = $result['empresa_id'];

            if ($editController !== null && $requestedId > 0) {
                try {
                    $refreshed = $editController->getConvenioById($requestedId);
                    if ($refreshed !== null) {
                        $convenio = $refreshed;
                    }
                } catch (\Throwable $throwable) {
                    $errors[] = $throwable->getMessage();
                }
            }
        } else {
            $errors = array_merge($errors, $result['errors']);
        }
    }
} elseif (($archivarController === null || $editController === null) && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $errors[] = $controllerError ?? 'No se pudo procesar la solicitud.';
}

$viewData = buildArchivarViewData(
    convenio: $convenio,
    requestedId: $requestedId,
    requestedEmpresaId: $requestedEmpresaId,
    sanitizedPost: $sanitizedPost,
    errors: $errors,
    successMessage: $successMessage
);

return $viewData;
