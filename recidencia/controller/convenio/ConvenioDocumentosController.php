<?php

declare(strict_types=1);

namespace Residencia\Controller\Convenio;

require_once __DIR__ . '/../../model/convenio/ConvenioDocumentosModel.php';
require_once __DIR__ . '/../../common/functions/convenio/conveniofunctions_documentos.php';

use PDOException;
use Residencia\Model\Convenio\ConvenioDocumentosModel;
use RuntimeException;

use function convenioDocumentosDecorateList;

class ConvenioDocumentosController
{
    private ConvenioDocumentosModel $model;

    public function __construct(?ConvenioDocumentosModel $model = null)
    {
        $this->model = $model ?? new ConvenioDocumentosModel();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getDocumentos(int $convenioId): array
    {
        if ($convenioId <= 0) {
            return [];
        }

        try {
            $data = $this->model->findByConvenioId($convenioId);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudieron obtener los documentos asociados al convenio.', 0, $exception);
        }

        if ($data === null) {
            return [];
        }

        return convenioDocumentosDecorateList(
            $data['global'],
            $data['custom'],
            $data['empresa_id']
        );
    }
}

