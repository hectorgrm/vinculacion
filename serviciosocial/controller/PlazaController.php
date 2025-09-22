<?php
declare(strict_types=1);

namespace Serviciosocial\Controller;

require_once __DIR__ . '/../model/PlazaModel.php';
require_once __DIR__ . '/../../common/model/db.php';

use Common\Model\Database;
use PDO;
use Serviciosocial\Model\PlazaModel;

class PlazaController
{
    private PlazaModel $plazaModel;

    public function __construct(?PDO $pdo = null)
    {
        if ($pdo === null) {
            $pdo = Database::getConnection();
        }

        $this->plazaModel = new PlazaModel($pdo);
    }

    /**
     * Obtener todas las plazas registradas en el sistema.
     *
     * @return array<int, array<string, mixed>>
     */
    public function listAll(): array
    {
        return $this->plazaModel->getAll();
    }
}
