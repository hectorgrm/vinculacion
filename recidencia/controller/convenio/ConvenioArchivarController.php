<?php
declare(strict_types=1);

namespace Residencia\Controller\Convenio;

require_once __DIR__ . '/../../model/convenio/ConvenioArchivarModel.php';
require_once __DIR__ . '/../../model/convenio/ConvenioArchivoModel.php';
require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use Residencia\Model\Convenio\ConvenioArchivarModel;
use Residencia\Model\Convenio\ConvenioArchivoModel;
use RuntimeException;

class ConvenioArchivarController
{
    private ConvenioArchivarModel $archivarModel;
    private ConvenioArchivoModel $archivoModel;

    public function __construct()
    {
        $pdo = Database::getConnection();
        $this->archivarModel = new ConvenioArchivarModel($pdo);
        $this->archivoModel = new ConvenioArchivoModel($pdo);
    }

    /**
     * @return array{status:bool, errors:array<int,string>, mensaje:string, empresa_id:int|null}
     */
    public function archivarConvenioCascade(int $convenioId, ?string $motivo = null): array
    {
        $errors = [];
        if ($convenioId <= 0) {
            $errors[] = 'Convenio no válido.';
        }

        if ($errors !== []) {
            return [
                'status' => false,
                'errors' => $errors,
                'mensaje' => 'No se pudo archivar el convenio.',
                'empresa_id' => null,
            ];
        }

        $convenio = $this->archivarModel->getConvenioResumen($convenioId);
        if ($convenio === null) {
            return [
                'status' => false,
                'errors' => ['No se encontró el convenio.'],
                'mensaje' => 'No se pudo archivar el convenio.',
                'empresa_id' => null,
            ];
        }

        if (isset($convenio['estatus']) && (string) $convenio['estatus'] === 'Inactiva') {
            return [
                'status' => false,
                'errors' => ['El convenio ya se encuentra inactivo.'],
                'mensaje' => 'No se pudo archivar el convenio.',
                'empresa_id' => (int) $convenio['empresa_id'],
            ];
        }

        try {
            $archivoId = $this->archivarModel->archivarConvenio($convenioId, $motivo);
        } catch (RuntimeException $exception) {
            return [
                'status' => false,
                'errors' => [$exception->getMessage()],
                'mensaje' => 'No se pudo archivar el convenio.',
                'empresa_id' => (int) $convenio['empresa_id'],
            ];
        }

        return [
            'status' => true,
            'errors' => [],
            'mensaje' => 'Convenio archivado correctamente.',
            'empresa_id' => (int) $convenio['empresa_id'],
            'archivo_id' => $archivoId,
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function obtenerArchivoPorId(int $archivoId): ?array
    {
        if ($archivoId <= 0) {
            return null;
        }

        return $this->archivoModel->getArchivo($archivoId);
    }
}
