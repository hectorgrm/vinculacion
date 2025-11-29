<?php

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/common/auth.php';
require_once __DIR__ . '/../../common/functions/documento/documentofunctions_review.php';
require_once __DIR__ . '/../../controller/documento/DocumentoReviewController.php';

use Residencia\Controller\Documento\DocumentoReviewController;

if (!function_exists('documentoReviewHandler')) {
    /**
     * @return array{
     *     documentId: ?int,
     *     document: ?array<string, mixed>,
     *     fileMeta: array{
     *         exists: bool,
     *         absolutePath: ?string,
     *         publicUrl: ?string,
     *         filename: ?string,
     *         sizeBytes: ?int,
     *         sizeLabel: ?string,
     *         extension: ?string,
     *         canPreview: bool
     *     },
     *     formData: array{estatus: string, observacion: string},
     *     statusOptions: array<string, string>,
     *     errors: array<int, string>,
     *     successMessage: ?string,
     *     controllerError: ?string,
     *     notFoundMessage: ?string
     * }
     */
    function documentoReviewHandler(): array
    {
        $viewData = documentoReviewDefaults();

        $documentId = documentoReviewNormalizeId($_GET['id'] ?? $_POST['id'] ?? null);
        if ($documentId === null) {
            $viewData['notFoundMessage'] = 'No se proporciono un identificador de documento valido.';

            return $viewData;
        }

        $viewData['documentId'] = $documentId;

        try {
            $controller = new DocumentoReviewController();
        } catch (\Throwable $exception) {
            $viewData['controllerError'] = documentoReviewControllerErrorMessage($exception);

            return $viewData;
        }

        $viewData['statusOptions'] = $controller->getStatusOptions();

        try {
            $document = $controller->getDocument($documentId);
        } catch (\Throwable $exception) {
            $viewData['controllerError'] = documentoReviewControllerErrorMessage($exception);

            return $viewData;
        }

        if ($document === null) {
            $viewData['notFoundMessage'] = documentoReviewNotFoundMessage($documentId);

            return $viewData;
        }

        $document = documentoReviewDecorateDocument($document);
        $viewData['document'] = $document;
        $viewData['fileMeta'] = documentoReviewBuildFileMeta(
            isset($document['ruta']) ? (string) $document['ruta'] : null,
            dirname(__DIR__, 2)
        );

        if (!documentoReviewIsPostRequest()) {
            $viewData['formData']['estatus'] = isset($document['estatus'])
                ? (string) $document['estatus']
                : '';

            $viewData['formData']['observacion'] = isset($document['observacion'])
                ? documentoReviewNormalizeObservation($document['observacion'])
                : '';

            return $viewData;
        }

        $viewData['formData'] = documentoReviewSanitizeInput($_POST);
        $errors = documentoReviewValidateForm($viewData['formData'], $viewData['statusOptions']);

        if ($errors !== []) {
            $viewData['errors'] = $errors;

            return $viewData;
        }

        $estatus = $viewData['formData']['estatus'];
        $observacion = $viewData['formData']['observacion'] !== ''
            ? $viewData['formData']['observacion']
            : null;

        $auditContext = documentoCurrentAuditContext();
        $empresaIdForRedirect = isset($document['empresa_id']) ? (int) $document['empresa_id'] : null;

        try {
            $controller->updateStatus($documentId, $estatus, $observacion, $auditContext);
            $viewData['successMessage'] = documentoReviewSuccessMessage($estatus);

            $document = $controller->getDocument($documentId);
            if ($document !== null) {
                $document = documentoReviewDecorateDocument($document);
                $viewData['document'] = $document;
                $viewData['fileMeta'] = documentoReviewBuildFileMeta(
                    isset($document['ruta']) ? (string) $document['ruta'] : null,
                    dirname(__DIR__, 2)
                );
            }

            if ($empresaIdForRedirect !== null && $empresaIdForRedirect > 0) {
                $redirectUrl = '../empresa/empresa_view.php?id=' . urlencode((string) $empresaIdForRedirect);
                if ($viewData['successMessage'] !== null && $viewData['successMessage'] !== '') {
                    $redirectUrl .= '&success_message=' . rawurlencode($viewData['successMessage']);
                }
                header('Location: ' . $redirectUrl);
                exit;
            }
        } catch (\Throwable $exception) {
            $viewData['controllerError'] = documentoReviewControllerErrorMessage($exception);
        }

        return $viewData;
    }
}

return documentoReviewHandler();

