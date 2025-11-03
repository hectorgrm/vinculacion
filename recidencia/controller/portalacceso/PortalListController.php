<?php

declare(strict_types=1);

namespace Residencia\Controller\PortalAcceso;

require_once __DIR__ . '/../../model/portalacceso/PortalListModel.php';
require_once __DIR__ . '/../../common/functions/portalacceso/portalacceso_list_functions.php';

use PDOException;
use Residencia\Model\PortalAcceso\PortalListModel;
use RuntimeException;

use function portalAccessListDefaults;
use function portalAccessListNormalizeSearch;
use function portalAccessListNormalizeStatus;
use function portalAccessResolveStatus;

class PortalListController
{
    private PortalListModel $model;

    public function __construct(?PortalListModel $model = null)
    {
        $this->model = $model ?? new PortalListModel();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listPortales(?string $search = null, ?string $status = null): array
    {
        $term = portalAccessListNormalizeSearch($search);
        $statusFilter = portalAccessListNormalizeStatus($status);

        if ($term === '') {
            $term = null;
        }

        if ($statusFilter === '') {
            $statusFilter = null;
        }

        try {
            $records = $this->model->fetchAll($term, $statusFilter);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudieron obtener los accesos registrados.', 0, $exception);
        }

        $portales = [];

        foreach ($records as $record) {
            if (!is_array($record)) {
                continue;
            }

            $statusResolved = portalAccessResolveStatus($record['activo'] ?? null, $record['expiracion'] ?? null);

            $portales[] = [
                'id' => isset($record['id']) ? (string) $record['id'] : '',
                'empresa_id' => isset($record['empresa_id']) ? (string) $record['empresa_id'] : '',
                'empresa_nombre' => isset($record['empresa_nombre']) ? (string) $record['empresa_nombre'] : '',
                'empresa_numero_control' => isset($record['empresa_numero_control']) && $record['empresa_numero_control'] !== null
                    ? (string) $record['empresa_numero_control']
                    : '',
                'token' => isset($record['token']) ? (string) $record['token'] : '',
                'nip' => isset($record['nip']) && $record['nip'] !== null ? (string) $record['nip'] : '',
                'activo' => isset($record['activo']) ? (string) $record['activo'] : '0',
                'expiracion' => isset($record['expiracion']) && $record['expiracion'] !== null ? (string) $record['expiracion'] : null,
                'creado_en' => isset($record['creado_en']) && $record['creado_en'] !== null ? (string) $record['creado_en'] : null,
                'status' => $statusResolved,
            ];
        }

        return $portales;
    }

    /**
     * @param array<string, mixed> $input
     * @return array{
     *     search: string,
     *     status: string,
     *     statusOptions: array<string, string>,
     *     portales: array<int, array<string, mixed>>,
     *     errorMessage: ?string
     * }
     */
    public function handle(array $input): array
    {
        $defaults = portalAccessListDefaults();

        $searchValue = $input['search'] ?? null;
        $statusValue = $input['status'] ?? null;

        $search = portalAccessListNormalizeSearch(is_string($searchValue) ? $searchValue : null);
        $status = portalAccessListNormalizeStatus(is_string($statusValue) ? $statusValue : null);

        $defaults['search'] = $search;
        $defaults['status'] = $status;
        $defaults['portales'] = $this->listPortales($search, $status);

        return $defaults;
    }
}
