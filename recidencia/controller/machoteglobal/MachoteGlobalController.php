<?php

declare(strict_types=1);

namespace Residencia\Controller\MachoteGlobal;

require_once __DIR__ . '/../../model/machoteglobal/MachoteGlobalModel.php';
require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use Residencia\Model\MachoteGlobal\MachoteGlobalModel;
use PDO;
use Throwable;

final class MachoteGlobalController
{
    private MachoteGlobalModel $model;

    public function __construct()
    {
        // 🔹 Conexión a la BD
        $connection = Database::getConnection();
        $this->model = new MachoteGlobalModel($connection);
    }

    /**
     * 📋 Obtener todos los machotes institucionales
     * (para mostrarlos en machote_global_list.php)
     *
     * @return array<int, array<string, mixed>>
     */
    public function listarMachotes(): array
    {
        try {
            return $this->model->getAllMachotes();
        } catch (Throwable $e) {
            // En producción podrías registrar el error con un logger
            return [];
        }
    }

    /**
     * 🔍 Obtener un machote por ID
     *
     * @param int $id
     * @return array<string, mixed>|null
     */
    public function obtenerMachote(int $id): ?array
    {
        try {
            return $this->model->getMachoteById($id);
        } catch (Throwable $e) {
            return null;
        }
    }

    /**
     * 💾 Guardar o actualizar un machote
     * (se usará desde machote_edit.php)
     *
     * @param array<string, mixed> $data
     * @return bool
     */
    public function guardarMachote(array $data): bool
    {
        try {
            if (!empty($data['id'])) {
                return $this->model->updateMachote($data);
            } else {
                return $this->model->insertMachote($data);
            }
        } catch (Throwable $e) {
            return false;
        }
    }

    /**
     * 🗄️ Archivar o eliminar un machote
     *
     * @param int $id
     * @return bool
     */
    public function archivarMachote(int $id): bool
    {
        try {
            return $this->model->deleteMachote($id);
        } catch (Throwable $e) {
            return false;
        }
    }
}
