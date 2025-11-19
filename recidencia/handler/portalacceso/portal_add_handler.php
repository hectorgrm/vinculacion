<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/portalacceso/portalacceso_functions.php';
require_once __DIR__ . '/../../controller/portalacceso/PortalAddController.php';

use Residencia\Controller\PortalAcceso\PortalAddController;

if (!function_exists('portalAccessAddHandler')) {
    /**
     * @return array{
     *     formData: array<string, string>,
     *     empresaOptions: array<int, array<string, string>>,
     *     errors: array<int, string>,
     *     successMessage: ?string,
     *     controllerError: ?string
     * }
     */
    function portalAccessAddHandler(): array
    {
        $viewData = portalAccessDefaults();

        try {
            $controller = new PortalAddController();
            $viewData['empresaOptions'] = $controller->fetchEmpresas();
        } catch (\Throwable $exception) {
            $controllerError = portalAccessControllerErrorMessage($exception);
            $viewData['controllerError'] = $controllerError;

            if (portalAccessIsPostRequest()) {
                $viewData['errors'][] = $controllerError;
            }

            return $viewData;
        }

        if (!portalAccessIsPostRequest()) {
            $empresaIdFromQuery = isset($_GET['empresa_id']) ? trim((string) $_GET['empresa_id']) : '';

            if ($empresaIdFromQuery !== '' && preg_match('/^\d+$/', $empresaIdFromQuery) === 1) {
                foreach ($viewData['empresaOptions'] as $empresaOption) {
                    if (!isset($empresaOption['id'])) {
                        continue;
                    }

                    if ((string) $empresaOption['id'] === $empresaIdFromQuery) {
                        $viewData['formData']['empresa_id'] = $empresaIdFromQuery;
                        break;
                    }
                }
            }

            return $viewData;
        }

        $viewData['formData'] = portalAccessSanitizeInput($_POST);
        $viewData['errors'] = portalAccessValidateData($viewData['formData'], $viewData['empresaOptions']);

        if ($viewData['errors'] !== []) {
            return $viewData;
        }

        try {
            $accessId = $controller->createPortalAccess($viewData['formData']);
            $viewData['successMessage'] = portalAccessSuccessMessage($accessId);
            $viewData['formData'] = portalAccessFormDefaults();
        } catch (\Throwable $exception) {
            $pdoException = null;

            if ($exception instanceof \PDOException) {
                $pdoException = $exception;
            } elseif ($exception instanceof \RuntimeException) {
                $previous = $exception->getPrevious();

                if ($previous instanceof \PDOException) {
                    $pdoException = $previous;
                }
            }

            if ($pdoException instanceof \PDOException) {
                $duplicateErrors = portalAccessDuplicateErrors($pdoException);

                if ($duplicateErrors !== []) {
                    $viewData['errors'] = $duplicateErrors;

                    return $viewData;
                }
            }

            $viewData['errors'][] = portalAccessPersistenceErrorMessage($exception);
        }

        return $viewData;
    }
}

return portalAccessAddHandler();
