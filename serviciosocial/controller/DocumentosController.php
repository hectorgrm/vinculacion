<?php

declare(strict_types=1);

namespace Serviciosocial\Controller;

require_once __DIR__ . '/../model/DocumentosModel.php';
require_once __DIR__ . '/../../common/model/db.php';

use Common\Model\Database;
use PDO;
use Serviciosocial\Model\DocumentosModel;

class DocumentosController
{
    private DocumentosModel $model;

    public function __construct(?PDO $pdo = null)
    {
        if ($pdo === null) {
            $pdo = Database::getConnection();
        }

        $this->model = new DocumentosModel($pdo);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function list(?string $searchTerm = null, ?string $estatus = null): array
    {
        $searchTerm = $searchTerm !== null ? trim($searchTerm) : null;
        if ($searchTerm === '') {
            $searchTerm = null;
        }

        $estatus = $estatus !== null ? strtolower(trim($estatus)) : null;
        if ($estatus === '') {
            $estatus = null;
        }

        $allowedStatuses = ['pendiente', 'aprobado', 'rechazado'];
        if ($estatus !== null && !in_array($estatus, $allowedStatuses, true)) {
            $estatus = null;
        }

        return $this->model->fetchDocuments($searchTerm, $estatus);
    }

    public function find(int $documentId): ?array
    {
        if ($documentId <= 0) {
            return null;
        }

        return $this->model->fetchDocumentById($documentId);
    }
}
