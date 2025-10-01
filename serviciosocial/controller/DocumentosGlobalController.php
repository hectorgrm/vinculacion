<?php

declare(strict_types=1);

namespace Serviciosocial\Controller;

require_once __DIR__ . '/../model/DocumentosGlobalModel.php';
require_once __DIR__ . '/../../common/model/db.php';

use Common\Model\Database;
use PDO;
use Serviciosocial\Model\DocumentosGlobalModel;

class DocumentosGlobalController
{
    private const ALLOWED_STATUSES = ['activo', 'inactivo'];

    private DocumentosGlobalModel $model;

    public function __construct(?PDO $pdo = null)
    {
        if ($pdo === null) {
            $pdo = Database::getConnection();
        }

        $this->model = new DocumentosGlobalModel($pdo);
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

        if ($estatus !== null && !in_array($estatus, self::ALLOWED_STATUSES, true)) {
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

    public function findOrFail(int $documentId): array
    {
        $document = $this->find($documentId);

        if ($document === null) {
            throw new \RuntimeException('El documento solicitado no existe o no está disponible.');
        }

        return $document;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getDocumentTypeCatalog(): array
    {
        return $this->model->fetchDocumentTypeCatalog();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findDocumentType(int $tipoId): ?array
    {
        if ($tipoId <= 0) {
            return null;
        }

        return $this->model->fetchDocumentTypeById($tipoId);
    }

    /**
     * @return array<int, string>
     */
    public function getAllowedStatuses(): array
    {
        return self::ALLOWED_STATUSES;
    }

    public function create(array $data): int
    {
        $tipoId = isset($data['tipo_id']) ? (int) $data['tipo_id'] : 0;
        $nombre = isset($data['nombre']) ? trim((string) $data['nombre']) : '';
        $ruta = isset($data['ruta']) ? trim((string) $data['ruta']) : '';
        $estatus = isset($data['estatus']) ? strtolower(trim((string) $data['estatus'])) : 'activo';
        $descripcion = array_key_exists('descripcion', $data) ? trim((string) $data['descripcion']) : null;

        if ($tipoId <= 0) {
            throw new \InvalidArgumentException('Debe seleccionar un tipo de documento válido.');
        }

        if ($this->model->fetchDocumentTypeById($tipoId) === null) {
            throw new \InvalidArgumentException('El tipo de documento seleccionado no existe.');
        }

        if ($nombre === '') {
            throw new \InvalidArgumentException('El nombre del documento es obligatorio.');
        }

        if ($ruta === '') {
            throw new \InvalidArgumentException('La ruta del archivo es obligatoria.');
        }

        if (!in_array($estatus, self::ALLOWED_STATUSES, true)) {
            $estatus = 'activo';
        }

        if ($descripcion === '') {
            $descripcion = null;
        }

        return $this->model->createDocument($tipoId, $nombre, $descripcion, $ruta, $estatus);
    }

    public function update(int $documentId, array $data): bool
    {
        if ($documentId <= 0) {
            throw new \InvalidArgumentException('El identificador del documento es inválido.');
        }

        $payload = [];

        if (array_key_exists('tipo_id', $data)) {
            $tipoId = (int) $data['tipo_id'];

            if ($tipoId <= 0) {
                throw new \InvalidArgumentException('Debe seleccionar un tipo de documento válido.');
            }

            $payload['tipo_id'] = $tipoId;
        }

        if (array_key_exists('nombre', $data)) {
            $nombre = trim((string) $data['nombre']);
            if ($nombre === '') {
                throw new \InvalidArgumentException('El nombre del documento es obligatorio.');
            }

            $payload['nombre'] = $nombre;
        }

        if (array_key_exists('descripcion', $data)) {
            $descripcion = trim((string) $data['descripcion']);
            $payload['descripcion'] = $descripcion === '' ? null : $descripcion;
        }

        if (array_key_exists('ruta', $data)) {
            $ruta = trim((string) $data['ruta']);
            if ($ruta === '') {
                throw new \InvalidArgumentException('La ruta del archivo es obligatoria cuando se actualiza.');
            }

            $payload['ruta'] = $ruta;
        }

        if (array_key_exists('estatus', $data)) {
            $estatus = strtolower(trim((string) $data['estatus']));
            if (!in_array($estatus, self::ALLOWED_STATUSES, true)) {
                throw new \InvalidArgumentException('El estatus seleccionado no es válido.');
            }

            $payload['estatus'] = $estatus;
        }

        if ($payload === []) {
            return false;
        }

        return $this->model->updateDocument($documentId, $payload);
    }

    public function delete(int $documentId): bool
    {
        if ($documentId <= 0) {
            throw new \InvalidArgumentException('El identificador del documento es inválido.');
        }

        return $this->model->deleteDocument($documentId);
    }
}
