<?php

declare(strict_types=1);

namespace Residencia\Controller\Convenio;

require_once __DIR__ . '/../../model/convenio/ConvenioAuditoriaModel.php';

use Residencia\Model\Convenio\ConvenioAuditoriaModel;

class ConvenioAuditoriaController
{
    private ConvenioAuditoriaModel $model;

    public function __construct(?ConvenioAuditoriaModel $model = null)
    {
        $this->model = $model ?? new ConvenioAuditoriaModel();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchHistorial(int $convenioId, ?int $empresaId = null, int $limit = 30): array
    {
        return $this->model->fetchHistorial($convenioId, $empresaId, $limit);
    }
}
