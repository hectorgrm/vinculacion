<?php
declare(strict_types=1);

namespace PortalEmpresa\Controller\Machote;

require_once __DIR__ . '/../model/MachoteComentarioModel.php';

use PortalEmpresa\Model\Machote\MachoteComentarioModel;
use Throwable;

final class MachoteComentarioController
{
    private MachoteComentarioModel $model;

    public function __construct(?MachoteComentarioModel $model = null)
    {
        $this->model = $model ?? new MachoteComentarioModel();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function obtenerComentarios(int $machoteId): array
    {
        return $this->model->getComentariosConRespuestas($machoteId);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function responderComentario(array $data): bool
    {
        try {
            return $this->model->insertRespuesta($data);
        } catch (Throwable $exception) {
            error_log('Error insertando respuesta: ' . $exception->getMessage());
            return false;
        }
    }
}
