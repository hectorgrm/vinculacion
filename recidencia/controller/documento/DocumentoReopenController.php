<?php

declare(strict_types=1);

namespace Residencia\Controller\Documento;

require_once __DIR__ . '/../../model/documento/DocumentoReopenModel.php';

use Residencia\Model\Documento\DocumentoReopenModel;

class DocumentoReopenController
{
    private DocumentoReopenModel $model;

    public function __construct(?DocumentoReopenModel $model = null)
    {
        $this->model = $model ?? new DocumentoReopenModel();
    }

    /**
     * @return array<string, mixed>
     */
    public function reopenDocument(int $documentId): array
    {
        return $this->model->reopenDocument($documentId);
    }
}
