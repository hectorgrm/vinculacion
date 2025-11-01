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

    public function updateStatus(int $documentId, string $estatus, ?string $observacion, array $auditContext = []): void
    {
        $this->model->updateStatus($documentId, $estatus, $observacion);

        $normalizedStatus = documentoNormalizeStatus($estatus) ?? trim($estatus);

        $accion = match ($normalizedStatus) {
            'aprobado' => 'aprobar',
            'rechazado' => 'rechazar',
            'pendiente' => 'actualizar_estatus',
            default => 'actualizar_estatus',
        };

        documentoRegisterAuditEvent($accion, $documentId, $auditContext);
    }

    /**
     * @return array<string, string>
     */
    public function getStatusOptions(): array
    {
        return documentoStatusOptions();
    }
}

