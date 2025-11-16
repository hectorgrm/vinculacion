<?php

declare(strict_types=1);

namespace PortalEmpresa\Controller;

use PortalEmpresa\Model\EstudianteModel;
use RuntimeException;

require_once __DIR__ . '/../model/EstudianteModel.php';
require_once __DIR__ . '/../helpers/estudiante_helper.php';

class PortalEstudianteListController
{
    private EstudianteModel $model;

    public function __construct(?EstudianteModel $model = null)
    {
        $this->model = $model ?? new EstudianteModel();
    }

    /**
     * @return array{
     *     activos: array<int, array<string, mixed>>,
     *     historico: array<int, array<string, mixed>>,
     *     kpi: array{activos: int, finalizados: int, total: int}
     * }
     */
    public function obtenerListadoPorEmpresa(int $empresaId): array
    {
        if ($empresaId <= 0) {
            throw new RuntimeException('La sesión de la empresa no es válida.');
        }

        $registros = $this->model->findByEmpresaId($empresaId);
        $hidratados = array_map('estudianteHydrateRecord', $registros);
        $split = estudianteSplitPorEstatus($hidratados);

        $activos = $split['activos'];
        $historico = $split['historico'];
        $finalizados = $split['finalizados'];
        $total = count($activos) + count($historico);

        return [
            'activos' => $activos,
            'historico' => $historico,
            'kpi' => [
                'activos' => count($activos),
                'finalizados' => $finalizados,
                'total' => $total,
            ],
        ];
    }
}
