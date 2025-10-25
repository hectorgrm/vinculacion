<?php

declare(strict_types=1);

namespace Residencia\Model\Convenio;

require_once __DIR__ . '/../ConvenioModel.php';
require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use PDO;
use Residencia\Model\ConvenioModel;

class ConvenioAddModel extends ConvenioModel
{
    public function __construct(?PDO $pdo = null)
    {
        if ($pdo === null) {
            $pdo = Database::getConnection();
        }

        parent::__construct($pdo);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getEmpresasForSelect(): array
    {
        return $this->fetchEmpresasForSelect();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function createConvenio(array $data): int
    {
        return $this->insert($data);
    }
}
