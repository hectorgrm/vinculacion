<?php

declare(strict_types=1);

namespace Residencia\Controller\Documento;

require_once __DIR__ . '/../../model/documento/DocumentoDeleteModel.php';

use Residencia\Model\Documento\DocumentoDeleteModel;

class DocumentoDeleteController
{
    private DocumentoDeleteModel $model;

    public function __construct(?DocumentoDeleteModel $model = null)
    {
        $this->model = $model ?? new DocumentoDeleteModel();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getDocument(int $documentId): ?array
    {
        return $this->model->findById($documentId);
    }

    /**
     * @return array<string, mixed>
     */
    public function deleteDocument(int $documentId): array
    {
        return $this->model->deleteDocument($documentId);
    }
}
