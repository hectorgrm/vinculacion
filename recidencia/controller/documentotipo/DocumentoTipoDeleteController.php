<?php

declare(strict_types=1);

namespace Residencia\Controller\DocumentoTipo;

require_once __DIR__ . '/../../model/documentotipo/DocumentoTipoDeleteModel.php';

use Residencia\Model\DocumentoTipo\DocumentoTipoDeleteModel;
use RuntimeException;
use PDOException;
use Throwable;

class DocumentoTipoDeleteController
{
    private DocumentoTipoDeleteModel $model;

    public function __construct(?DocumentoTipoDeleteModel $model = null)
    {
        $this->model = $model ?? new DocumentoTipoDeleteModel();
    }

    /**
     * @return array<string, mixed>
     */
    public function getDocumentoTipoById(int $documentoTipoId): array
    {
        try {
            $documentoTipo = $this->model->findById($documentoTipoId);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo obtener el tipo de documento.', 0, $exception);
        }

        if ($documentoTipo === null) {
            throw new RuntimeException('El tipo de documento solicitado no existe.', 404);
        }

        return $documentoTipo;
    }

    /**
     * @return array{
     *     action: 'deleted'|'deactivated',
     *     documentoTipo: array<string, mixed>,
     *     usageCount: int
     * }
     */
    public function deleteDocumentoTipo(int $documentoTipoId): array
    {
        try {
            return $this->model->deleteOrDeactivate($documentoTipoId);
        } catch (RuntimeException $exception) {
            throw $exception;
        } catch (Throwable $throwable) {
            throw new RuntimeException('No se pudo completar la eliminacion del tipo de documento.', 0, $throwable);
        }
    }
}
