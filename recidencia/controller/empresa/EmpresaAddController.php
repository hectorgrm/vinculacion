<?php

declare(strict_types=1);

namespace Residencia\Controller\Empresa;

require_once __DIR__ . '/../../model/empresa/EmpresaAddModel.php';

use Residencia\Model\Empresa\EmpresaAddModel;

class EmpresaAddController
{
    private EmpresaAddModel $model;

    public function __construct(?EmpresaAddModel $model = null)
    {
        $this->model = $model ?? new EmpresaAddModel();
    }

    /**
     * @param array<string, string> $data
     */
    public function create(array $data): int
    {
        return $this->model->createEmpresa($data);
    }
}
