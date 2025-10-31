<?php

declare(strict_types=1);

namespace Residencia\Controller\Empresadocumentotipo;

require_once __DIR__ . '/../../model/empresadocumentotipo/EmpresaDocumentoTipoEditModel.php';

use PDOException;
use Residencia\Model\Empresadocumentotipo\EmpresaDocumentoTipoEditModel;
use RuntimeException;

class EmpresaDocumentoTipoEditController
{
    private EmpresaDocumentoTipoEditModel $model;

    public function __construct(?EmpresaDocumentoTipoEditModel $model = null)
    {
        $this->model = $model ?? new EmpresaDocumentoTipoEditModel();
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
     * @return array<string, mixed>|null
     */
    public function getDocumento(int $documentoId, int $empresaId): ?array
    {
        try {
            return $this->model->findDocumentoForEmpresa($documentoId, $empresaId);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo obtener el documento individual solicitado.', 0, $exception);
        }
    }

    /**
     * @param array{
     *     empresa_id: int,
     *     documento_id: int,
     *     nombre: string,
     *     descripcion: ?string,
     *     obligatorio: int,
     *     tipo_empresa: string
     * } $payload
     */
    public function updateDocumento(array $payload): void
    {
        try {
            $this->model->updateDocumento($payload);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo actualizar el documento individual.', 0, $exception);
        }
    }
}
