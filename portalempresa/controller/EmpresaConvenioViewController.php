<?php

declare(strict_types=1);

namespace PortalEmpresa\Controller;

use PortalEmpresa\Helpers\EmpresaConvenioHelper;
use PortalEmpresa\Model\EmpresaConvenioViewModel;

require_once __DIR__ . '/../helpers/empresaconveniofunction.php';
require_once __DIR__ . '/../model/EmpresaConvenioViewModel.php';

class EmpresaConvenioViewController
{
    private EmpresaConvenioViewModel $model;

    public function __construct(?EmpresaConvenioViewModel $model = null)
    {
        $this->model = $model ?? new EmpresaConvenioViewModel();
    }

    /**
     * @return array{empresa: array<string, mixed>|null, convenio: array<string, mixed>|null, status: array<string, mixed>, errors: array<int, string>}
     */
    public function buildViewData(int $empresaId): array
    {
        if ($empresaId <= 0) {
            return [
                'empresa' => null,
                'convenio' => null,
                'status' => EmpresaConvenioHelper::statusMeta(null, false),
                'errors' => ['missing_empresa_id'],
            ];
        }

        try {
            $empresa = $this->model->findEmpresaById($empresaId);
        } catch (\Throwable) {
            return [
                'empresa' => null,
                'convenio' => null,
                'status' => EmpresaConvenioHelper::statusMeta(null, false),
                'errors' => ['database_error'],
            ];
        }

        if ($empresa === null) {
            return [
                'empresa' => null,
                'convenio' => null,
                'status' => EmpresaConvenioHelper::statusMeta(null, false),
                'errors' => ['empresa_not_found'],
            ];
        }

        $empresaDecorated = EmpresaConvenioHelper::decorateEmpresa($empresa);

        try {
            $convenio = $this->model->findLatestConvenioByEmpresaId($empresaId);
        } catch (\Throwable) {
            return [
                'empresa' => $empresaDecorated,
                'convenio' => null,
                'status' => EmpresaConvenioHelper::statusMeta(null, false),
                'errors' => ['database_error'],
            ];
        }

        $convenioDecorated = EmpresaConvenioHelper::decorateConvenio($convenio);
        $hasConvenio = $convenioDecorated !== null;
        $status = EmpresaConvenioHelper::statusMeta($convenioDecorated['estatus'] ?? null, $hasConvenio);

        return [
            'empresa' => $empresaDecorated,
            'convenio' => $convenioDecorated,
            'status' => $status,
            'errors' => [],
        ];
    }
}
