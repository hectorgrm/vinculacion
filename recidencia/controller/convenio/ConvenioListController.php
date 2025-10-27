<?php

declare(strict_types=1);

namespace Residencia\Controller\Convenio;

require_once __DIR__ . '/../../model/ConvenioModel.php';
require_once __DIR__ . '/../../../common/model/db.php';
require_once __DIR__ . '/../../common/functions/conveniofunction.php';

use Common\Model\Database;
use PDO;
use PDOException;
use Residencia\Model\ConvenioModel;
use RuntimeException;
use Throwable;

class ConvenioListController
{
    private ConvenioModel $convenioModel;

    public function __construct(?PDO $pdo = null)
    {
        if ($pdo === null) {
            $pdo = Database::getConnection();
        }

        $this->convenioModel = new ConvenioModel($pdo);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listConvenios(?string $search = null, ?string $estatus = null): array
    {
        $term = $search !== null ? trim($search) : null;
        if ($term === '') {
            $term = null;
        }

        $statusFilter = $estatus !== null ? trim($estatus) : null;
        if ($statusFilter === '') {
            $statusFilter = null;
        }

        try {
            return $this->convenioModel->fetchAll($term, $statusFilter);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudieron obtener los convenios registrados.', 0, $exception);
        }
    }

    /**
     * @param array<string, mixed> $query
     * @return array{
     *     search: string,
     *     selectedStatus: string,
     *     statusOptions: array<int, string>,
     *     convenios: array<int, array<string, mixed>>,
     *     errorMessage: ?string
     * }
     */
    public function handle(array $query): array
    {
        $search = isset($query['search']) ? trim((string) $query['search']) : '';
        $rawStatus = isset($query['estatus']) ? trim((string) $query['estatus']) : '';

        $estatusFilter = $rawStatus !== '' ? convenioNormalizeStatus($rawStatus) : null;
        $selectedStatus = $estatusFilter ?? '';

        $convenios = [];
        $errorMessage = null;

        try {
            $convenios = $this->listConvenios($search !== '' ? $search : null, $estatusFilter);
        } catch (Throwable $exception) {
            $errorMessage = $exception->getMessage();
        }

        return [
            'search' => $search,
            'selectedStatus' => $selectedStatus,
            'statusOptions' => convenioStatusOptions(),
            'convenios' => $convenios,
            'errorMessage' => $errorMessage,
        ];
    }
}
