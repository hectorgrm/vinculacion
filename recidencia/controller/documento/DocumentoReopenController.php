<?php

declare(strict_types=1);

namespace Residencia\Controller\Documento;

require_once __DIR__ . '/../../model/documento/DocumentoReopenModel.php';
require_once __DIR__ . '/../../common/functions/documento/documentofunctions_list.php';

use Residencia\Model\Documento\DocumentoReopenModel;
use function documentoAuditBuildDetalles;
use function documentoAuditFetchSnapshot;

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
    public function reopenDocument(int $documentId, array $auditContext = []): array
    {
        $before = documentoAuditFetchSnapshot($documentId);
        $document = $this->model->reopenDocument($documentId);
        $detalles = documentoAuditBuildDetalles($document, $before);

        documentoRegisterAuditEvent('reabrir', $documentId, $auditContext, $detalles);

        return $document;
    }
}
