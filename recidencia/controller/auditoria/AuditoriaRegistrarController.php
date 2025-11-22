<?php

declare(strict_types=1);

namespace Residencia\Controller\Auditoria;

require_once __DIR__ . '/../../../common/model/db.php';
require_once __DIR__ . '/../../model/auditoria/AuditoriaModel.php';
require_once __DIR__ . '/../../common/functions/auditoria/auditoriafunctions.php';

use Residencia\Model\Auditoria\AuditoriaModel;

class AuditoriaRegistrarController
{
    private AuditoriaModel $model;

    public function __construct(?AuditoriaModel $model = null)
    {
        $this->model = $model ?? new AuditoriaModel();
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function registrar(array $payload): bool
    {
        $accion = auditoriaNormalizePlain($payload['accion'] ?? null);
        $entidad = auditoriaNormalizePlain($payload['entidad'] ?? null);
        $entidadId = auditoriaNormalizePositiveInt($payload['entidad_id'] ?? null);

        if ($accion === null || $entidad === null || $entidadId === null) {
            return false;
        }

        $actorTipo = auditoriaNormalizeActorTipo($payload['actor_tipo'] ?? null) ?? 'sistema';
        $actorId = auditoriaNormalizePositiveInt($payload['actor_id'] ?? null);
        $ip = isset($payload['ip']) ? trim((string) $payload['ip']) : '';
        $detalles = auditoriaNormalizeDetallesPayload($payload['detalles'] ?? null);

        $dbPayload = [
            'accion' => $accion,
            'entidad' => $entidad,
            'entidad_id' => $entidadId,
            'actor_tipo' => $actorTipo,
            'actor_id' => $actorId,
            'ip' => $ip !== '' ? $ip : null,
        ];

        return $this->model->registrar($dbPayload, $detalles);
    }
}
