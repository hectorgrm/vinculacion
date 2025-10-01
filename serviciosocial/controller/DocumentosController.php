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

    /**
     * @param array<string, mixed> $data
     */
    public function update(int $documentId, array $data): bool
    {
        if ($documentId <= 0) {
            throw new \InvalidArgumentException('El identificador del documento es inválido.');
        }

        $allowedStatuses = ['pendiente', 'aprobado', 'rechazado'];

        $payload = [];

        if (array_key_exists('estatus', $data)) {
            $estatus = strtolower(trim((string) $data['estatus']));

            if (in_array($estatus, $allowedStatuses, true)) {
                $payload['estatus'] = $estatus;
            }
        }

        if (array_key_exists('observacion', $data)) {
            $observacion = trim((string) $data['observacion']);
            $payload['observacion'] = $observacion === '' ? null : $observacion;
        }

        if (array_key_exists('recibido', $data)) {
            $payload['recibido'] = (bool) $data['recibido'];
        }

        if (array_key_exists('ruta', $data)) {
            $ruta = trim((string) $data['ruta']);
            $payload['ruta'] = $ruta === '' ? null : $ruta;
        }

        if ($payload === []) {
            return false;
        }

        return $this->model->updateDocument($documentId, $payload);
    }
}
