<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/documento/documentofunctions_delete.php';
require_once __DIR__ . '/../../controller/documento/DocumentoDeleteController.php';

use Residencia\Controller\Documento\DocumentoDeleteController;

if (!function_exists('documentoDeleteHandler')) {
    /**
     * @return array{
     *     documentId: ?int,
     *     document: ?array<string, mixed>,
     *     empresaId: ?int,
     *     tipoGlobalId: ?int,
     *     tipoPersonalizadoId: ?int,
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
     *     controllerError: ?string,
     *     notFoundMessage: ?string,
     *     errorMessage: ?string
     * }
     */
    function documentoDeleteHandler(): array
    {
        $viewData = documentoDeleteDefaults();

        $documentId = documentoDeleteNormalizeId($_GET['id'] ?? null);
        if ($documentId === null) {
            $viewData['notFoundMessage'] = 'No se proporciono un identificador de documento valido.';

            return $viewData;
        }

        $viewData['documentId'] = $documentId;

        $errorCode = isset($_GET['error']) ? trim((string) $_GET['error']) : '';
        if ($errorCode !== '') {
            $viewData['errorMessage'] = documentoDeleteErrorMessageFromCode($errorCode);
        }

        try {
            $controller = new DocumentoDeleteController();
        } catch (\Throwable $exception) {
            $viewData['controllerError'] = documentoDeleteControllerErrorMessage($exception);

            return $viewData;
        }

        try {
            $document = $controller->getDocument($documentId);
        } catch (\Throwable $exception) {
            $viewData['controllerError'] = documentoDeleteControllerErrorMessage($exception);

            return $viewData;
        }

        if ($document === null) {
            $viewData['notFoundMessage'] = documentoDeleteNotFoundMessage($documentId);

            return $viewData;
        }

        $viewData['document'] = documentoDeleteDecorateDocument($document);
        $viewData['fileMeta'] = documentoDeleteBuildFileMeta(
            isset($document['ruta']) ? (string) $document['ruta'] : null,
            dirname(__DIR__, 2)
        );

        $viewData['empresaId'] = documentoDeleteNormalizeId($document['empresa_id'] ?? null);
        $viewData['tipoGlobalId'] = documentoDeleteNormalizeId($document['tipo_global_id'] ?? null);
        $viewData['tipoPersonalizadoId'] = documentoDeleteNormalizeId($document['tipo_personalizado_id'] ?? null);

        return $viewData;
    }
}

return documentoDeleteHandler();
