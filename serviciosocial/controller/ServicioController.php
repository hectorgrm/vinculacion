<?php
declare(strict_types=1);

namespace Serviciosocial\Controller;

require_once __DIR__ . '/../model/ServicioModel.php';
require_once __DIR__ . '/../../common/model/db.php';

use Common\Model\Database;
use PDO;
use Serviciosocial\Model\ServicioModel;

class ServicioController
{
    private ServicioModel $servicioModel;

    public function __construct(?PDO $pdo = null)
    {
        if ($pdo === null) {
            $pdo = Database::getConnection();
        }

        $this->servicioModel = new ServicioModel($pdo);
    }

    /**
     * Obtener la lista de servicios registrados, opcionalmente filtrados por búsqueda.
     *
     * @return array<int, array<string, mixed>>
     */
    public function listServicios(?string $search = null): array
    {
        $term = $search !== null ? trim($search) : null;

        if ($term === '') {
            $term = null;
        }

        return $this->servicioModel->fetchAll($term);
    }

    /**
     * Obtener la información detallada de un servicio.
     *
     * @return array<string, mixed>|null
     */
    public function findServicio(int $id): ?array
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('El identificador del servicio debe ser un número positivo.');
        }

        return $this->servicioModel->findById($id);
    }
}
