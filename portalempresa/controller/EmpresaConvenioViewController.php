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
     * @return array{
     *     empresa: array<string, mixed>|null,
     *     convenio: array<string, mixed>|null,
     *     status: array<string, mixed>,
     *     historial: array<int, array<string, mixed>>,
     *     errors: array<int, string>
     * }
     */
    public function buildViewData(int $empresaId, ?int $selectedConvenioId = null): array
    {
        if ($empresaId <= 0) {
            return [
                'empresa' => null,
                'convenio' => null,
                'status' => EmpresaConvenioHelper::statusMeta(null, false),
                'historial' => [],
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
                'historial' => [],
                'errors' => ['database_error'],
            ];
        }

        if ($empresa === null) {
            return [
                'empresa' => null,
                'convenio' => null,
                'status' => EmpresaConvenioHelper::statusMeta(null, false),
                'historial' => [],
                'errors' => ['empresa_not_found'],
            ];
        }

        $empresaDecorated = EmpresaConvenioHelper::decorateEmpresa($empresa);

        try {
            $convenios = $this->model->findConveniosByEmpresaId($empresaId);
        } catch (\Throwable) {
            return [
                'empresa' => $empresaDecorated,
                'convenio' => null,
                'status' => EmpresaConvenioHelper::statusMeta(null, false),
                'historial' => [],
                'errors' => ['database_error'],
            ];
        }

        $selectedConvenio = null;

        if ($selectedConvenioId !== null) {
            foreach ($convenios as $registro) {
                if ((int) ($registro['id'] ?? 0) === $selectedConvenioId) {
                    $selectedConvenio = $registro;
                    break;
                }
            }
        }

        if ($selectedConvenio === null && $convenios !== []) {
            $selectedConvenio = $convenios[0];
        }

        $convenioDecorated = EmpresaConvenioHelper::decorateConvenio($selectedConvenio);
        $hasConvenio = $convenioDecorated !== null;
        $status = EmpresaConvenioHelper::statusMeta($convenioDecorated['estatus'] ?? null, $hasConvenio);
        $historialDecorated = EmpresaConvenioHelper::decorateConvenioHistory($convenios, $convenioDecorated['id'] ?? null);

        return [
            'empresa' => $empresaDecorated,
            'convenio' => $convenioDecorated,
            'status' => $status,
            'historial' => $historialDecorated,
            'errors' => [],
        ];
    }
}
