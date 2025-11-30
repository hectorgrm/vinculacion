<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/empresadocumentotipo/empresa_documentotipo_functions_add.php';
require_once __DIR__ . '/../../common/functions/empresadocumentotipo/empresa_documentotipo_functions_list.php';
require_once __DIR__ . '/../../controller/empresadocumentotipo/EmpresaDocumentoTipoAddController.php';

use Residencia\Controller\Empresadocumentotipo\EmpresaDocumentoTipoAddController;

if (!function_exists('empresaDocumentoTipoAddAction')) {
    /**
     * @return array{
     *     empresaId: ?int,
     *     empresa: ?array<string, mixed>,
     *     formData: array<string, string>,
     *     errors: array<int, string>,
     *     successMessage: ?string,
     *     controllerError: ?string,
     *     inputError: ?string,
     *     notFoundMessage: ?string
     * }
     */
    function empresaDocumentoTipoAddAction(): array
    {
        $viewData = empresaDocumentoTipoAddDefaults();
        $methodIsPost = empresaDocumentoTipoAddIsPostRequest();

        $empresaId = empresaDocumentoTipoAddNormalizeEmpresaId(
            $_GET['id_empresa'] ?? $_GET['id'] ?? ($_POST['empresa_id'] ?? null)
        );

        if ($empresaId === null) {
            $viewData['inputError'] = empresaDocumentoTipoAddInputErrorMessage();

            if ($methodIsPost) {
                $viewData['errors'][] = $viewData['inputError'];
                $viewData['formData'] = empresaDocumentoTipoAddSanitizeInput($_POST);
            }

            return $viewData;
        }

        $viewData['empresaId'] = $empresaId;
        $viewData['formData'] = empresaDocumentoTipoAddFormDefaults($empresaId);

        try {
            $controller = new EmpresaDocumentoTipoAddController();
        } catch (\Throwable $exception) {
            $controllerError = empresaDocumentoTipoAddControllerErrorMessage($exception);
            $viewData['controllerError'] = $controllerError;

            if ($methodIsPost) {
                $viewData['errors'][] = $controllerError;
                $viewData['formData'] = empresaDocumentoTipoAddSanitizeInput($_POST, $empresaId);
            }

            return $viewData;
        }

        try {
            $empresa = $controller->getEmpresa($empresaId);
        } catch (\Throwable $exception) {
            $controllerError = empresaDocumentoTipoAddControllerErrorMessage($exception);
            $viewData['controllerError'] = $controllerError;

            if ($methodIsPost) {
                $viewData['errors'][] = $controllerError;
                $viewData['formData'] = empresaDocumentoTipoAddSanitizeInput($_POST, $empresaId);
            }

            return $viewData;
        }

        if ($empresa === null) {
            $viewData['notFoundMessage'] = empresaDocumentoTipoAddNotFoundMessage($empresaId);

            if ($methodIsPost) {
                $viewData['errors'][] = $viewData['notFoundMessage'];
                $viewData['formData'] = empresaDocumentoTipoAddSanitizeInput($_POST, $empresaId);
            }

            return $viewData;
        }

        $empresaDecorated = empresaDocumentoTipoListDecorateEmpresa($empresa);
        $viewData['empresa'] = $empresaDecorated;

        if (!$methodIsPost) {
            return $viewData;
        }

        $sanitized = empresaDocumentoTipoAddSanitizeInput($_POST, $empresaId);
        $viewData['formData'] = $sanitized;
        $viewData['errors'] = empresaDocumentoTipoAddValidateData($sanitized, $empresaId);

        if ($viewData['errors'] !== []) {
            return $viewData;
        }

        try {
            $duplicateErrors = $controller->duplicateFieldErrors($sanitized);
        } catch (\Throwable $exception) {
            $controllerError = empresaDocumentoTipoAddControllerErrorMessage($exception);
            $viewData['controllerError'] = $controllerError;
            $viewData['errors'][] = $controllerError;

            return $viewData;
        }

        if ($duplicateErrors !== []) {
            $viewData['errors'] = $duplicateErrors;

            return $viewData;
        }

        try {
            $documentoId = $controller->createDocumento($sanitized);
            $empresaNombre = isset($empresaDecorated['nombre_label'])
                ? (string) $empresaDecorated['nombre_label']
                : (string) ($empresaDecorated['nombre'] ?? '');
            $viewData['successMessage'] = empresaDocumentoTipoAddSuccessMessage($empresaNombre, $documentoId);
            $viewData['formData'] = empresaDocumentoTipoAddFormDefaults($empresaId);

            if (
                $empresaId !== null &&
                $viewData['successMessage'] !== null &&
                $viewData['successMessage'] !== ''
            ) {
                $redirectUrl = '../empresa/empresa_view.php?id='
                    . rawurlencode((string) $empresaId)
                    . '&success_message='
                    . rawurlencode($viewData['successMessage']);

                header('Location: ' . $redirectUrl);
                exit;
            }
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
                $duplicateErrors = empresaDocumentoTipoAddDuplicateErrors($pdoException);

                if ($duplicateErrors !== []) {
                    $viewData['errors'] = $duplicateErrors;

                    return $viewData;
                }
            }

            $viewData['errors'][] = empresaDocumentoTipoAddPersistenceErrorMessage($exception);
        }

        return $viewData;
    }
}

return empresaDocumentoTipoAddAction();
