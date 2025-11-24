<?php

declare(strict_types=1);

namespace Residencia\Controller\Machote;

require_once __DIR__ . '/../../model/machote/MachoteReabrirModel.php';
require_once __DIR__ . '/../../common/functions/auditoria/auditoriafunctions.php';
require_once __DIR__ . '/../../common/functions/convenio/conveniofunctions_auditoria.php';

use Residencia\Model\Machote\MachoteReabrirModel;
use RuntimeException;
use function auditoriaRegistrarEvento;
use function convenioCurrentAuditContext;

final class MachoteReabrirController
{
    private MachoteReabrirModel $model;

    public function __construct(?MachoteReabrirModel $model = null)
    {
        $this->model = $model ?? MachoteReabrirModel::createWithDefaultConnection();
    }

    /**
     * @return array{status: 'reopened'|'already_open'}
     */
    public function reabrir(int $machoteId): array
    {
        if ($machoteId <= 0) {
            throw new RuntimeException('Identificador de machote inválido.');
        }

        $machote = $this->model->findMachoteById($machoteId);

        if ($machote === null) {
            throw new RuntimeException('Machote no encontrado.');
        }

        if ((int) ($machote['confirmacion_empresa'] ?? 0) === 0) {
            return ['status' => 'already_open'];
        }

        $this->model->reabrirMachote($machoteId);
        $this->registrarAuditoriaReapertura($machote);

        return ['status' => 'reopened'];
    }

    /**
     * @param array<string, mixed> $machote
     */
    private function registrarAuditoriaReapertura(array $machote): void
    {
        if (!function_exists('auditoriaRegistrarEvento')) {
            return;
        }

        $machoteId = isset($machote['id']) ? (int) $machote['id'] : 0;
        $estatusAnterior = isset($machote['estatus']) ? trim((string) $machote['estatus']) : '';
        $confirmado = (int) ($machote['confirmacion_empresa'] ?? 0) === 1;

        if ($machoteId <= 0) {
            return;
        }

        $detalles = [
            [
                'campo' => 'confirmacion_empresa',
                'campo_label' => 'Confirmacion de empresa',
                'valor_anterior' => $confirmado ? 'Confirmado' : 'No confirmado',
                'valor_nuevo' => 'No confirmado',
            ],
        ];

        if ($estatusAnterior !== '') {
            $detalles[] = [
                'campo' => 'estatus',
                'campo_label' => 'Estatus del machote',
                'valor_anterior' => $estatusAnterior,
                'valor_nuevo' => 'En revisión',
            ];
        }

        $contexto = function_exists('convenioCurrentAuditContext')
            ? convenioCurrentAuditContext()
            : [];

        $payload = [
            'accion' => 'reabrir',
            'entidad' => 'rp_convenio_machote',
            'entidad_id' => $machoteId,
            'detalles' => $detalles,
        ];

        if (isset($contexto['actor_tipo'])) {
            $payload['actor_tipo'] = $contexto['actor_tipo'];
        }

        if (isset($contexto['actor_id'])) {
            $payload['actor_id'] = $contexto['actor_id'];
        }

        if (isset($contexto['ip'])) {
            $payload['ip'] = $contexto['ip'];
        }

        auditoriaRegistrarEvento($payload);
    }
}
