<?php
declare(strict_types=1);

namespace Residencia\Controller\Machote;

require_once __DIR__ . '/../../model/machote/ConvenioMachoteModel.php';
require_once __DIR__ . '/../../../common/model/db.php';
require_once __DIR__ . '/../../common/helpers/machote/machote_helper.php';

use Residencia\Model\Machote\ConvenioMachoteModel;

class MachoteListController
{
    /**
     * Maneja la lógica del listado
     * @param array<string, mixed> $query
     * @return array<string, mixed>
     */
    public function handle(array $query): array
    {
        $search = isset($query['search']) ? trim((string)$query['search']) : '';

        $model = ConvenioMachoteModel::createWithDefaultConnection();
        $machotes = $model->getAll($search);

        // Aplicar formateos
        $machotesFormateados = array_map(static function ($m) {
            return [
                'id' => $m['id'],
                'empresa_id' => isset($m['empresa_id']) ? (int) $m['empresa_id'] : null,
                'empresa' => $m['empresa_nombre'],
                'version' => $m['machote_version'],
                'fecha' => machote_format_date($m['fecha']),
                'estatus' => $m['machote_estatus'] ?? '',
                'badge' => machote_estado_badge($m['machote_estatus'] ?? '')
            ];
        }, $machotes);

        return [
            'search' => $search,
            'machotes' => $machotesFormateados,
            'error' => count($machotesFormateados) === 0 ? 'No se encontraron machotes.' : null
        ];
    }
}
