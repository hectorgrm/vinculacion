<?php

declare(strict_types=1);

namespace Residencia\Controller\DocumentoTipo;

require_once __DIR__ . '/../../model/documentotipo/documentoTipoEditModel.php';

use Residencia\Model\DocumentoTipo\DocumentoTipoEditModel;
use RuntimeException;
use PDOException;

class DocumentoTipoEditController
{
    private DocumentoTipoEditModel $model;

    public function __construct(?DocumentoTipoEditModel $model = null)
    {
        $this->model = $model ?? new DocumentoTipoEditModel();
    }

    public function getDocumentoTipoById(int $documentoTipoId): array
    {
        try {
            $documentoTipo = $this->model->findById($documentoTipoId);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo obtener el tipo de documento.', 0, $exception);
        }

        if ($documentoTipo === null) {
            throw new RuntimeException('El tipo de documento solicitado no existe.');
        }

        return $documentoTipo;
    }

    /**
     * @param array<string, string> $data
     */
    public function updateDocumentoTipo(int $documentoTipoId, array $data): void
    {
        try {
            $this->model->update($documentoTipoId, $data);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo actualizar el tipo de documento.', 0, $exception);
        }
    }

    /**
     * @param array<string, string> $data
     */
    public function update(int $documentoTipoId, array $data): void
    {
        $this->updateDocumentoTipo($documentoTipoId, $data);
    }

    public function reactivateDocumentoTipo(int $documentoTipoId): void
    {
        try {
            $this->model->reactivate($documentoTipoId);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo reactivar el tipo de documento.', 0, $exception);
        }
    }
}
