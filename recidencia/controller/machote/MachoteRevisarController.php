<?php
declare(strict_types=1);

namespace Residencia\Controller\Machote;

require_once __DIR__ . '/../../../common/model/db.php';
require_once __DIR__ . '/../../model/convenio/ConvenioMachoteModel.php';
require_once __DIR__ . '/../../../portalempresa/model/MachoteComentarioModel.php';
require_once __DIR__ . '/../../common/helpers/machote/machote_revisar_helper.php';

use Common\Model\Database;
use PDO;
use Residencia\Model\Convenio\ConvenioMachoteModel;
use PortalEmpresa\Model\Machote\MachoteComentarioModel as MachoteConversacionModel;
use RuntimeException;
use function Residencia\Common\Helpers\Machote\resumenComentarios;

final class MachoteRevisarController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Maneja la carga de un machote con sus comentarios y datos relacionados.
     *
     * @return array<string, mixed>
     */
    public function handle(int $machoteId): array
    {
        if ($machoteId <= 0) {
            throw new RuntimeException('El identificador del machote es inválido.');
        }

        $modelMachote = new ConvenioMachoteModel($this->db);
        $modelComentarios = new MachoteConversacionModel($this->db);

        // 1️⃣ Cargar el machote hijo
        $machote = $modelMachote->getById($machoteId);
        if ($machote === null) {
            throw new RuntimeException('Machote no encontrado o eliminado.');
        }

        // 2️⃣ Cargar datos relacionados
        $empresa = $modelMachote->getEmpresaByMachote($machoteId);
        $convenio = $modelMachote->getConvenioByMachote($machoteId);

        // 3️⃣ Cargar comentarios
        $comentarios = $modelComentarios->getComentariosConRespuestas($machoteId);
        $resumen = resumenComentarios($comentarios);

        return [
            'machote'     => $machote,
            'empresa'     => $empresa,
            'convenio'    => $convenio,
            'comentarios' => $comentarios,
            'progreso'    => $resumen['progreso'],
            'estado'      => $resumen['estado'],
            'totales'     => $resumen,
            'error'       => null,
        ];
    }
}
