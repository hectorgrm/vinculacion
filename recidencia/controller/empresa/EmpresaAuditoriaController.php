<?php

declare(strict_types=1);

namespace Residencia\Controller\Empresa;

require_once __DIR__ . '/../../model/empresa/EmpresaAuditoriaModel.php';
require_once __DIR__ . '/../../common/functions/auditoria/auditoria_functions.php';
require_once __DIR__ . '/../../common/functions/empresa/empresafunctions_auditoria.php';

use PDOException;
use Residencia\Model\Empresa\EmpresaAuditoriaModel;
use RuntimeException;

use function empresaAuditoriaDecorateRegistros;
use function empresaAuditoriaDefaults;
use function empresaAuditoriaInputErrorMessage;
use function empresaAuditoriaNormalizeId;
use function empresaCurrentAuditContext;
use function empresaRegisterAuditEvent;

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
     * @return ?string
     */
    public function getEmpresaStatus(int $empresaId): ?string
    {
        try {
            return $this->model->findEmpresaStatus($empresaId);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo obtener el estatus de la empresa.', 0, $exception);
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

        $empresaStatus = $this->getEmpresaStatus($empresaId);

        if ($empresaStatus !== null && $this->isEmpresaInactiva($empresaStatus)) {
            $this->registrarEventoDesactivacion($empresaId);
        }

        $registros = $this->getHistorial($empresaId);
        $viewData['items'] = empresaAuditoriaDecorateRegistros($registros);

        return $viewData;
    }

    private function isEmpresaInactiva(string $estatus): bool
    {
        return strcasecmp(trim($estatus), 'Inactiva') === 0;
    }

    private function registrarEventoDesactivacion(int $empresaId): void
    {
        if ($empresaId <= 0) {
            return;
        }

        $context = empresaCurrentAuditContext();
        empresaRegisterAuditEvent('desactivacion_de_empresa', $empresaId, $context);
    }
}
