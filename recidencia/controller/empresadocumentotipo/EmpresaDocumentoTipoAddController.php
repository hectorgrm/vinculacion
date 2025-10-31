<?php

declare(strict_types=1);

namespace Residencia\Controller\Empresadocumentotipo;

require_once __DIR__ . '/../../model/empresadocumentotipo/EmpresaDocumentoTipoAddModel.php';

use PDOException;
use Residencia\Model\Empresadocumentotipo\EmpresaDocumentoTipoAddModel;
use RuntimeException;

class EmpresaDocumentoTipoAddController
{
    private EmpresaDocumentoTipoAddModel $model;

    public function __construct(?EmpresaDocumentoTipoAddModel $model = null)
    {
        $this->model = $model ?? new EmpresaDocumentoTipoAddModel();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getEmpresa(int $empresaId): ?array
    {
        try {
            return $this->model->findEmpresaById($empresaId);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo obtener la empresa solicitada.', 0, $exception);
        }
    }

    /**
     * @param array<string, string> $data
     */
    public function createDocumento(array $data): int
    {
        try {
            return $this->model->createDocumento($data);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo registrar el documento individual.', 0, $exception);
        }
    }
}
