<?php

declare(strict_types=1);

namespace Residencia\Controller\DocumentoTipo;

require_once __DIR__ . '/../../model/documentotipo/DocumentoTipoAddModel.php';

use Residencia\Model\DocumentoTipo\DocumentoTipoAddModel;
use RuntimeException;
use PDOException;

class DocumentoTipoAddController
{
    private DocumentoTipoAddModel $model;

    public function __construct(?DocumentoTipoAddModel $model = null)
    {
        $this->model = $model ?? new DocumentoTipoAddModel();
    }

    /**
     * @param array<string, string> $data
     */
    public function createDocumentoTipo(array $data): int
    {
        try {
            return $this->model->create($data);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo registrar el tipo de documento.', 0, $exception);
        }
    }

    /**
     * @param array<string, string> $data
     */
    public function create(array $data): int
    {
        return $this->createDocumentoTipo($data);
    }
}

