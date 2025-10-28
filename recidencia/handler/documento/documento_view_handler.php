<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/documento/documentofunction_view.php';
require_once __DIR__ . '/../../controller/documento/DocumentoViewController.php';

use Residencia\Controller\Documento\DocumentoViewController;

if (!function_exists('documentoViewHandler')) {
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
     *     history: array<int, array<string, mixed>>,
     *     controllerError: ?string,
     *     notFoundMessage: ?string
     * }
     */
    function documentoViewHandler(): array
    {
        $viewData = documentoViewDefaults();

        $documentId = documentoViewNormalizeId($_GET['id'] ?? null);
        if ($documentId === null) {
            $viewData['notFoundMessage'] = 'No se proporciono un identificador de documento valido.';

            return $viewData;
        }

        $viewData['documentId'] = $documentId;

        try {
            $controller = new DocumentoViewController();
        } catch (\Throwable $exception) {
            $viewData['controllerError'] = documentoViewControllerErrorMessage($exception);

            return $viewData;
        }

        try {
            $document = $controller->getDocument($documentId);
        } catch (\Throwable $exception) {
            $viewData['controllerError'] = documentoViewControllerErrorMessage($exception);

            return $viewData;
        }

        if ($document === null) {
            $viewData['notFoundMessage'] = documentoViewNotFoundMessage($documentId);

            return $viewData;
        }

        $document = documentoViewDecorateDocument($document);
        $viewData['document'] = $document;

        $viewData['fileMeta'] = documentoViewBuildFileMeta(
            isset($document['ruta']) ? (string) $document['ruta'] : null,
            dirname(__DIR__, 2)
        );

        $empresaId = documentoNormalizePositiveInt($document['empresa_id'] ?? null);
        $tipoId = documentoNormalizePositiveInt($document['tipo_id'] ?? null);

        if ($empresaId !== null && $tipoId !== null) {
            try {
                $history = $controller->getHistory($empresaId, $tipoId, $documentId);
                $viewData['history'] = documentoViewDecorateHistory($history);
            } catch (\Throwable) {
                $viewData['history'] = [];
            }
        }

        return $viewData;
    }
}

return documentoViewHandler();
