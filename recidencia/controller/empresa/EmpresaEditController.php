<?php

declare(strict_types=1);

namespace Residencia\Controller\Empresa;

require_once __DIR__ . '/../../model/empresa/EmpresaEditModel.php';

use Residencia\Model\Empresa\EmpresaEditModel;
use RuntimeException;
use PDOException;
use function array_key_exists;

class EmpresaEditController
{
    private EmpresaEditModel $model;

    public function __construct(?EmpresaEditModel $model = null)
    {
        $this->model = $model ?? new EmpresaEditModel();
    }

    public function getEmpresaById(int $empresaId): array
    {
        try {
            $empresa = $this->model->findById($empresaId);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo obtener la información de la empresa.', 0, $exception);
        }

        if ($empresa === null) {
            throw new RuntimeException('La empresa solicitada no existe.');
        }

        return $empresa;
    }

    public function empresaHasConvenioActivo(int $empresaId): bool
    {
        try {
            return $this->model->hasConvenioActivo($empresaId);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo validar los convenios asociados a la empresa.', 0, $exception);
        }
    }

    /**
     * @return array{estatus: ?string}|null
     */
    public function getLatestMachoteStatus(int $empresaId): ?array
    {
        try {
            return $this->model->findLatestMachoteStatus($empresaId);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo validar el machote asociado a la empresa.', 0, $exception);
        }
    }

    /**
     * @return array{total:int, aprobados:int, porcentaje:int}
     */
    public function getDocumentosStats(int $empresaId, ?string $tipoEmpresa = null): array
    {
        try {
            return $this->model->getDocumentosStats($empresaId, $tipoEmpresa);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo validar los documentos asociados a la empresa.', 0, $exception);
        }
    }

    /**
     * @param array<string, string> $data
     */
    public function updateEmpresa(int $empresaId, array $data): void
    {
        if (!array_key_exists('numero_control', $data)) {
            $existing = $this->getEmpresaById($empresaId);
            $data['numero_control'] = isset($existing['numero_control']) && $existing['numero_control'] !== null
                ? (string) $existing['numero_control']
                : '';
        }

        try {
            $this->model->update($empresaId, $data);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo actualizar la empresa.', 0, $exception);
        }
    }

    /**
     * @param array<string, string> $data
     */
    public function update(int $empresaId, array $data): void
    {
        $this->updateEmpresa($empresaId, $data);
    }
}
