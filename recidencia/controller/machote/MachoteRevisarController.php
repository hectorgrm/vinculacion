<?php
declare(strict_types=1);

namespace Residencia\Controller\Machote;

require_once __DIR__ . '/../../model/convenio/ConvenioMachoteModel.php';
require_once __DIR__ . '/../../model/machote/MachoteComentarioModel.php';
require_once __DIR__ . '/../../common/helpers/machote/machote_revisar_helper.php';
require_once __DIR__ . '/../../common/model/Database.php';

use Residencia\Model\Convenio\ConvenioMachoteModel;
use Residencia\Model\Machote\MachoteComentarioModel;
use Common\Model\Database;
use Exception;

class MachoteRevisarController
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
    }

    /**
     * Maneja la carga de un machote con sus comentarios.
     */
    public function handle(int $machoteId): array
    {
        $modelMachote = new ConvenioMachoteModel($this->db);
        $modelComentarios = new MachoteComentarioModel($this->db);

        // 1️⃣ Cargar el machote hijo
        $machote = $modelMachote->getById($machoteId);
        if (!$machote) {
            throw new Exception("Machote no encontrado o eliminado.");
        }

        // 2️⃣ Cargar datos relacionados
        $empresa = $modelMachote->getEmpresaByMachote($machoteId);
        $convenio = $modelMachote->getConvenioByMachote($machoteId);

        // 3️⃣ Cargar comentarios
        $comentarios = $modelComentarios->getByMachote($machoteId);
        $total = count($comentarios);
        $resueltos = count(array_filter($comentarios, fn($c) => $c['estatus'] === 'resuelto'));
        $progreso = $total > 0 ? round(($resueltos / $total) * 100) : 0;

        // 4️⃣ Estado dinámico
        $estado = $progreso === 100 ? 'Aprobado' : ($resueltos > 0 ? 'Con observaciones' : 'En revisión');

        return [
            'machote'     => $machote,
            'empresa'     => $empresa,
            'convenio'    => $convenio,
            'comentarios' => $comentarios,
            'progreso'    => $progreso,
            'estado'      => $estado,
            'error'       => null
        ];
    }
}
