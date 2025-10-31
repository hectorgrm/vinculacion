<?php

declare(strict_types=1);

namespace Residencia\Controller\Documento;

require_once __DIR__ . '/../../model/documento/DcumentoViewModel.php';

use Residencia\Model\Documento\DocumentoViewModel;

class DocumentoViewController
{
    private DocumentoViewModel $model;

    public function __construct(?DocumentoViewModel $model = null)
    {
        $this->model = $model ?? new DocumentoViewModel();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getDocument(int $documentId): ?array
    {
        return $this->model->findById($documentId);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getHistory(
        int $empresaId,
        ?int $tipoGlobalId,
        ?int $tipoPersonalizadoId,
        int $excludeDocumentId,
        int $limit = 6
    ): array
    {
        return $this->model->fetchHistory(
            $empresaId,
            $tipoGlobalId,
            $tipoPersonalizadoId,
            $excludeDocumentId,
            $limit
        );
    }
}
