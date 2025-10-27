<?php

declare(strict_types=1);

namespace Residencia\Controller\Empresa;

require_once __DIR__ . '/../../model/empresa/EmpresaListModel.php';
require_once __DIR__ . '/../../common/functions/empresa/empresafunctions_list.php';

use PDOException;
use Residencia\Model\Empresa\EmpresaListModel;
use RuntimeException;

use function empresaListDefaults;
use function empresaNormalizeSearch;

class EmpresaListController
{
    private EmpresaListModel $model;

    public function __construct(?EmpresaListModel $model = null)
    {
        $this->model = $model ?? new EmpresaListModel();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listEmpresas(?string $search = null): array
    {
        $term = empresaNormalizeSearch($search);

        if ($term === '') {
            $term = null;
        }

        try {
            return $this->model->fetchAll($term);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudieron obtener las empresas registradas.', 0, $exception);
        }
    }

    public function list(?string $search = null): array
    {
        return $this->listEmpresas($search);
    }

    /**
     * @param array<string, mixed> $input
     * @return array{
     *     search: string,
     *     empresas: array<int, array<string, mixed>>,
     *     errorMessage: ?string
     * }
     */
    public function handle(array $input): array
    {
        $defaults = empresaListDefaults();

        $searchValue = $input['search'] ?? null;
        $search = empresaNormalizeSearch(is_string($searchValue) ? $searchValue : null);

        $defaults['search'] = $search;
        $defaults['empresas'] = $this->listEmpresas($search);

        return $defaults;
    }
}
