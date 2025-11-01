<?php

declare(strict_types=1);

namespace Residencia\Controller\Auditoria;

require_once __DIR__ . '/../../../common/model/db.php';
require_once __DIR__ . '/../../model/auditoria/AuditoriaListModel.php';
require_once __DIR__ . '/../../common/functions/auditoria/auditoriafunctions.php';

use Common\Model\Database;
use PDO;
use Residencia\Model\Auditoria\AuditoriaListModel;

class AuditoriaListController
{
    private AuditoriaListModel $model;

    public function __construct(?PDO $pdo = null)
    {
        if ($pdo === null) {
            $pdo = Database::getConnection();
        }

        $this->model = new AuditoriaListModel($pdo);
    }

    /**
     * @param array<string, mixed> $query
     * @return array{
     *     q: string,
     *     actor_tipo: string,
     *     actor_id: string,
     *     accion: string,
     *     entidad: string,
     *     fecha_desde: string,
     *     fecha_hasta: string,
     *     auditorias: array<int, array<string, mixed>>,
     *     actorTipoOptions: array<string, string>,
     *     errorMessage: ?string
     * }
     */
    public function handle(array $query): array
    {
        $data = auditoriaListDefaults();

        $search = auditoriaNormalizeSearch($query['q'] ?? '');
        $actorTipo = auditoriaNormalizeActorTipo($query['actor_tipo'] ?? null);
        $actorId = auditoriaNormalizePositiveInt($query['actor_id'] ?? null);
        $accion = auditoriaNormalizePlain($query['accion'] ?? null);
        $entidad = auditoriaNormalizePlain($query['entidad'] ?? null);
        $fechaDesde = auditoriaNormalizeDate($query['fecha_desde'] ?? null);
        $fechaHasta = auditoriaNormalizeDate($query['fecha_hasta'] ?? null);

        try {
            $auditorias = $this->model->fetchAuditorias(
                $actorTipo,
                $actorId,
                $accion,
                $entidad,
                $search,
                $fechaDesde,
                $fechaHasta
            );

            $data['auditorias'] = $auditorias;
        } catch (\Throwable $throwable) {
            $message = trim($throwable->getMessage());
            $data['errorMessage'] = $message !== ''
                ? $message
                : 'No se pudo obtener el historial de auditoría. Intenta nuevamente más tarde.';
        }

        $data['q'] = $search;
        $data['actor_tipo'] = $actorTipo ?? '';
        $data['actor_id'] = $actorId !== null ? (string) $actorId : '';
        $data['accion'] = $accion ?? '';
        $data['entidad'] = $entidad ?? '';
        $data['fecha_desde'] = $fechaDesde ?? '';
        $data['fecha_hasta'] = $fechaHasta ?? '';

        return $data;
    }
}
