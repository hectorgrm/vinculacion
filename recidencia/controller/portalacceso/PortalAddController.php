<?php

declare(strict_types=1);

namespace Residencia\Controller\PortalAcceso;

require_once __DIR__ . '/../../model/portalacceso/PortalAddModel.php';
require_once __DIR__ . '/../../common/functions/portalacceso/portalacceso_auditoria.php';

use Residencia\Model\PortalAcceso\PortalAddModel;
use RuntimeException;
use PDOException;
use function portalAccessBuildCreacionDetalles;
use function portalAccessCurrentAuditContext;
use function portalAccessRegisterAuditEvent;

class PortalAddController
{
    private PortalAddModel $model;

    public function __construct(?PortalAddModel $model = null)
    {
        $this->model = $model ?? new PortalAddModel();
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function fetchEmpresas(): array
    {
        try {
            return $this->model->getEmpresas();
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo obtener el listado de empresas.', 0, $exception);
        }
    }

    /**
     * @param array<string, string> $data
     * @param array<int, array<string, string>> $empresaOptions
     */
    public function createPortalAccess(array $data, array $empresaOptions = []): int
    {
        try {
            $accessId = $this->model->create($data);
            $this->registrarAuditoriaCreacion($accessId, $data, $empresaOptions);

            return $accessId;
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo crear el acceso al portal.', 0, $exception);
        }
    }

    /**
     * @param array<string, string> $data
     * @param array<int, array<string, string>> $empresaOptions
     */
    private function registrarAuditoriaCreacion(int $accessId, array $data, array $empresaOptions = []): void
    {
        if (!function_exists('portalAccessRegisterAuditEvent')) {
            return;
        }

        try {
            $context = portalAccessCurrentAuditContext();
            $detalles = portalAccessBuildCreacionDetalles($data, $empresaOptions);

            portalAccessRegisterAuditEvent('crear', $accessId, $context, $detalles);
        } catch (\Throwable) {
        }
    }
}
