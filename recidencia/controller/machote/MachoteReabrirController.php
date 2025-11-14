<?php

declare(strict_types=1);

namespace Residencia\Controller\Machote;

require_once __DIR__ . '/../../model/machote/MachoteReabrirModel.php';

use Residencia\Model\Machote\MachoteReabrirModel;
use RuntimeException;

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

        return ['status' => 'reopened'];
    }
}
