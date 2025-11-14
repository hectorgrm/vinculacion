<?php

declare(strict_types=1);

namespace Residencia\Controller\Machote;

require_once __DIR__ . '/../../model/machote/MachoteConfirmModel.php';

use Residencia\Model\Machote\MachoteConfirmModel;
use RuntimeException;

final class MachoteConfirmController
{
    private MachoteConfirmModel $model;

    public function __construct(?MachoteConfirmModel $model = null)
    {
        $this->model = $model ?? MachoteConfirmModel::createWithDefaultConnection();
    }

    /**
     * @return array{status: 'confirmed'|'already'|'pending', pendientes?: int}
     */
    public function confirmarDesdeEmpresa(int $machoteId, int $empresaId): array
    {
        if ($machoteId <= 0 || $empresaId <= 0) {
            throw new RuntimeException('Parámetros inválidos para confirmar el machote.');
        }

        $machote = $this->model->findMachoteForEmpresa($machoteId, $empresaId);

        if ($machote === null) {
            throw new RuntimeException('Machote no disponible.');
        }

        if ((int) ($machote['confirmacion_empresa'] ?? 0) === 1) {
            return ['status' => 'already'];
        }

        $pendientes = $this->model->countComentariosPendientes($machoteId);

        if ($pendientes > 0) {
            return [
                'status' => 'pending',
                'pendientes' => $pendientes,
            ];
        }

        $this->model->confirmarMachote($machoteId);

        return ['status' => 'confirmed'];
    }
}
