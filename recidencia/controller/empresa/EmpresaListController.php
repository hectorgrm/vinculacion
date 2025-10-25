<?php

declare(strict_types=1);

namespace Residencia\Controller\Empresa;

require_once __DIR__ . '/../../model/empresa/EmpresaListModel.php';
require_once __DIR__ . '/../../common/functions/empresa/empresafunctions_list.php';

use Residencia\Model\Empresa\EmpresaListModel;
use Throwable;

class EmpresaListController
{
    private EmpresaListModel $model;

    public function __construct(?EmpresaListModel $model = null)
    {
        $this->model = $model ?? new EmpresaListModel();
    }

    /**
     * @param array<string, mixed> $query
     * @return array{
     *     search: string,
     *     empresas: array<int, array<string, mixed>>,
     *     errorMessage: ?string
     * }
     */
    public function handle(array $query): array
    {
        $rawSearch = $query['search'] ?? null;
        $search = empresaNormalizeSearch($rawSearch);

        $empresas = [];
        $errorMessage = null;

        try {
            $empresas = $this->model->getEmpresas($search !== '' ? $search : null);
        } catch (Throwable $exception) {
            $errorMessage = $exception->getMessage();
        }

        return [
            'search' => $search,
            'empresas' => $empresas,
            'errorMessage' => $errorMessage,
        ];
    }
}
