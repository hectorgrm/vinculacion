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
    private const ALLOWED_STATUSES = ['pendiente', 'aprobado', 'rechazado'];

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

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getPeriodCatalog(): array
    {
        return $this->model->fetchPeriodCatalog();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getDocumentTypeCatalog(): array
    {
        return $this->model->fetchDocumentTypeCatalog();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): int
    {
        $periodoId = isset($data['periodo_id']) ? (int) $data['periodo_id'] : 0;
        $tipoId = isset($data['tipo_id']) ? (int) $data['tipo_id'] : 0;

        if ($periodoId <= 0) {
            throw new \InvalidArgumentException('Debe seleccionar un periodo válido.');
        }

        if ($tipoId <= 0) {
            throw new \InvalidArgumentException('Debe seleccionar un tipo de documento válido.');
        }

        $periodo = $this->model->fetchPeriodById($periodoId);
        if ($periodo === null) {
            throw new \InvalidArgumentException('El periodo seleccionado no existe.');
        }

        $estudianteId = isset($data['estudiante_id']) ? (int) $data['estudiante_id'] : null;
        if ($estudianteId !== null && $estudianteId > 0) {
            $periodoEstudianteId = (int) ($periodo['estudiante']['id'] ?? 0);
            if ($periodoEstudianteId !== $estudianteId) {
                throw new \InvalidArgumentException('El periodo seleccionado no corresponde al estudiante indicado.');
            }
        }

        if ($this->model->fetchDocumentTypeById($tipoId) === null) {
            throw new \InvalidArgumentException('El tipo de documento seleccionado no existe.');
        }

        if ($this->model->documentExistsForPeriodAndType($periodoId, $tipoId)) {
            throw new \RuntimeException('Ya existe un documento para el periodo y tipo seleccionados.');
        }

        $estatus = isset($data['estatus']) ? strtolower(trim((string) $data['estatus'])) : 'pendiente';
        if ($estatus === '' || !in_array($estatus, self::ALLOWED_STATUSES, true)) {
            $estatus = 'pendiente';
        }

        $observacion = null;
        if (array_key_exists('observacion', $data)) {
            $observacionValue = trim((string) $data['observacion']);
            $observacion = $observacionValue === '' ? null : $observacionValue;
        }

        $ruta = null;
        if (array_key_exists('ruta', $data)) {
            $rutaValue = trim((string) $data['ruta']);
            $ruta = $rutaValue === '' ? null : $rutaValue;
        }

        $recibido = false;
        if (array_key_exists('recibido', $data)) {
            $recibido = (bool) $data['recibido'];
        } elseif ($ruta !== null) {
            $recibido = true;
        }

        return $this->model->createDocument($periodoId, $tipoId, $ruta, $recibido, $observacion, $estatus);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(int $documentId, array $data): bool
    {
        if ($documentId <= 0) {
            throw new \InvalidArgumentException('El identificador del documento es inválido.');
        }

        $payload = [];

        if (array_key_exists('estatus', $data)) {
            $estatus = strtolower(trim((string) $data['estatus']));

            if (in_array($estatus, self::ALLOWED_STATUSES, true)) {
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
