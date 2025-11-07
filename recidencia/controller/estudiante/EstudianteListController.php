<?php
declare(strict_types=1);

namespace Residencia\Controller\Estudiante;

use Residencia\Model\Estudiante\EstudianteListModel;

class EstudianteListController
{
    public function __construct(private readonly EstudianteListModel $model)
    {
    }

    /**
     * Obtains the information required by the student list view.
     *
     * @return array{
     *     estudiantes: array<int, array<string, mixed>>,
     *     empresas: array<int, array<string, mixed>>,
     *     convenios: array<int, array<string, mixed>>
     * }
     */
    public function obtenerDatos(?int $empresaId, ?int $convenioId): array
    {
        $empresas = $this->model->obtenerEmpresas();
        $convenios = $this->model->obtenerConvenios();
        $estudiantes = $this->model->obtenerEstudiantes($empresaId, $convenioId);

        return [
            'estudiantes' => $estudiantes,
            'empresas' => $empresas,
            'convenios' => $convenios,
        ];
    }
}
