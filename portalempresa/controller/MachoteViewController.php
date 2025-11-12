<?php
namespace PortalEmpresa\Controller\Machote;

require_once __DIR__ . '/../../../model/machote/MachoteViewModel.php';

use PortalEmpresa\Model\Machote\MachoteViewModel;
use Throwable;

class MachoteViewController
{
    private MachoteViewModel $model;

    public function __construct()
    {
        $this->model = new MachoteViewModel();
    }

    public function handleView(int $machoteId): array
    {
        if ($machoteId <= 0) {
            throw new \Exception("ID de machote inválido.");
        }

        $machote = $this->model->getMachoteById($machoteId);
        if (!$machote) {
            throw new \Exception("No se encontró el machote especificado.");
        }

        $comentarios = $this->model->getComentariosByMachote($machoteId);
        $total = count($comentarios);
        $pendientes = count(array_filter($comentarios, fn($c) => $c['estatus'] === 'pendiente'));
        $resueltos = $total - $pendientes;

        $avance = $total > 0 ? round(($resueltos / $total) * 100) : 0;

        return [
            'empresa' => [
                'nombre' => $machote['empresa_nombre'],
                'logo'   => $machote['empresa_logo']
            ],
            'machote' => [
                'id' => $machoteId,
                'version_local' => $machote['version_local'],
                'confirmacion_empresa' => (bool) $machote['confirmacion_empresa']
            ],
            'convenio' => [
                'estatus' => $machote['estatus']
            ],
            'comentarios' => $comentarios,
            'comentarios_pendientes' => $pendientes,
            'comentarios_resueltos' => $resueltos,
            'progreso' => $avance
        ];
    }
}
