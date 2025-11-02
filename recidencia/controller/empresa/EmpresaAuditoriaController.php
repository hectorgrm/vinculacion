<?php

declare(strict_types=1);

namespace Residencia\Controller\Empresa;

require_once __DIR__ . '/../../model/empresa/EmpresaAuditoriaModel.php';
require_once __DIR__ . '/../../common/functions/auditoria/auditoria_functions.php';

use PDOException;
use Residencia\Model\Empresa\EmpresaAuditoriaModel;
use RuntimeException;

use function empresaAuditoriaDecorateRegistros;
use function empresaAuditoriaDefaults;
use function empresaAuditoriaInputErrorMessage;
use function empresaAuditoriaNormalizeId;

class EmpresaAuditoriaController
{
    private EmpresaAuditoriaModel $model;

    public function __construct(?EmpresaAuditoriaModel $model = null)
    {
        $this->model = $model ?? new EmpresaAuditoriaModel();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getHistorial(int $empresaId): array
    {
        try {
            return $this->model->findByEmpresaId($empresaId);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo obtener el historial de auditoria de la empresa.', 0, $exception);
        }
    }

    /**
     * @param array<string, mixed> $input
     * @return array{
     *     empresaId: ?int,
     *     items: array<int, array<string, mixed>>,
     *     controllerError: ?string,
     *     inputError: ?string
     * }
     */
    public function handle(array $input): array
    {
        $viewData = empresaAuditoriaDefaults();

        $empresaId = empresaAuditoriaNormalizeId($input['id'] ?? null);

        if ($empresaId === null) {
            $viewData['inputError'] = empresaAuditoriaInputErrorMessage();

            return $viewData;
        }

        $viewData['empresaId'] = $empresaId;

        $registros = $this->getHistorial($empresaId);
        $viewData['items'] = empresaAuditoriaDecorateRegistros($registros);

        return $viewData;
    }
}
