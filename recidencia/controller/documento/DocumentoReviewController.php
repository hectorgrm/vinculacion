<?php

declare(strict_types=1);

namespace Residencia\Controller\Documento;

require_once __DIR__ . '/../../model/documento/DocumentoReviewModel.php';
require_once __DIR__ . '/../../common/functions/documento/documentofunctions_list.php';

use Residencia\Model\Documento\DocumentoReviewModel;

class DocumentoReviewController
{
    private DocumentoReviewModel $model;

    public function __construct(?DocumentoReviewModel $model = null)
    {
        $this->model = $model ?? new DocumentoReviewModel();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getDocument(int $documentId): ?array
    {
        return $this->model->findById($documentId);
    }

    public function updateStatus(int $documentId, string $estatus, ?string $observacion): void
    {
        $this->model->updateStatus($documentId, $estatus, $observacion);
    }

    /**
     * @return array<string, string>
     */
    public function getStatusOptions(): array
    {
        return documentoStatusOptions();
    }
}

