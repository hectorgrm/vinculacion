<?php

declare(strict_types=1);

namespace Residencia\Controller\Empresa;

require_once __DIR__ . '/../../model/empresa/EmpresaAddModel.php';
require_once __DIR__ . '/../../common/functions/empresa/empresafunctions_auditoria.php';

use Residencia\Model\Empresa\EmpresaAddModel;
use RuntimeException;
use PDOException;
use function empresaBuildCreacionDetalles;
use function empresaCurrentAuditContext;
use function empresaRegisterAuditEvent;

class EmpresaAddController
{
    private EmpresaAddModel $model;

    public function __construct(?EmpresaAddModel $model = null)
    {
        $this->model = $model ?? new EmpresaAddModel();
    }

    /**
     * @param array<string, string> $data
     */
    public function createEmpresa(array $data): int
    {
        try {
            $empresaId = $this->model->create($data);
            $this->registrarAuditoriaCreacion($empresaId, $data);

            return $empresaId;
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo registrar la empresa.', 0, $exception);
        }
    }

    /**
     * @param array<string, string> $data
     */
    public function create(array $data): int
    {
        return $this->createEmpresa($data);
    }

    /**
     * @param array<string, string> $data
     * @return array<int, string>
     */
    public function duplicateFieldErrors(array $data): array
    {
        return $this->model->duplicateFieldErrors($data);
    }

    /**
     * @param array<string, string> $data
     */
    private function registrarAuditoriaCreacion(int $empresaId, array $data): void
    {
        try {
            $context = empresaCurrentAuditContext();
            $detalles = empresaBuildCreacionDetalles($data);

            empresaRegisterAuditEvent('crear', $empresaId, $context, $detalles);
        } catch (\Throwable) {
        }
    }
}
