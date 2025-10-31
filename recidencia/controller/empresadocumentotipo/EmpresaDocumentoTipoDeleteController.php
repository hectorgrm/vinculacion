<?php

declare(strict_types=1);

namespace Residencia\Controller\Empresadocumentotipo;

require_once __DIR__ . '/../../model/empresadocumentotipo/EmpresaDocumentoTipoDeleteModel.php';

use Residencia\Model\Empresadocumentotipo\EmpresaDocumentoTipoDeleteModel;
use RuntimeException;
use Throwable;

class EmpresaDocumentoTipoDeleteController
{
    private EmpresaDocumentoTipoDeleteModel $model;

    public function __construct(?EmpresaDocumentoTipoDeleteModel $model = null)
    {
        $this->model = $model ?? new EmpresaDocumentoTipoDeleteModel();
    }

    /**
     * @return array{
     *     documento: array<string, mixed>,
     *     empresa: array<string, mixed>|null,
     *     usageCount: int,
     *     supportsActivo: bool
     * }
     */
    public function getDocumento(int $documentoId, ?int $empresaId = null): array
    {
        try {
            $result = $this->model->findDocumentoForDelete($documentoId, $empresaId);
        } catch (Throwable $exception) {
            throw new RuntimeException('No se pudo obtener el documento individual.', 0, $exception);
        }

        if ($result === null) {
            throw new RuntimeException('El documento individual solicitado no existe.', 404);
        }

        $documento = $result['documento'];
        $empresa = $result['empresa'];

        $supportsActivo = false;
        if (isset($documento['supports_activo'])) {
            $supportsActivo = (bool) $documento['supports_activo'];
            unset($documento['supports_activo']);
        }

        if (isset($documento['supports_tipo_empresa'])) {
            unset($documento['supports_tipo_empresa']);
        }

        $usageCount = $this->model->countLinkedUploadsFor($documentoId);

        return [
            'documento' => $documento,
            'empresa' => $empresa,
            'usageCount' => $usageCount,
            'supportsActivo' => $supportsActivo,
        ];
    }

    /**
     * @return array{
     *     action: 'deleted'|'deactivated',
     *     documento: array<string, mixed>,
     *     usageCount: int,
     *     supportsActivo: bool
     * }
     */
    public function deleteDocumento(int $documentoId, ?int $empresaId = null): array
    {
        try {
            return $this->model->deleteOrDeactivate($documentoId, $empresaId);
        } catch (RuntimeException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            throw new RuntimeException('No se pudo completar la eliminacion del documento individual.', 0, $exception);
        }
    }
}
