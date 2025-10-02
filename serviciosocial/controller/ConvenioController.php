<?php

declare(strict_types=1);

namespace Serviciosocial\Controller;

require_once __DIR__ . '/../model/ConvenioModel.php';
require_once __DIR__ . '/../../common/model/db.php';

use Common\Model\Database;
use InvalidArgumentException;
use PDO;
use Serviciosocial\Model\ConvenioModel;

class ConvenioController
{
    private ConvenioModel $convenioModel;

    public function __construct(?PDO $pdo = null)
    {
        if ($pdo === null) {
            $pdo = Database::getConnection();
        }

        $this->convenioModel = new ConvenioModel($pdo);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listAll(?string $search = null): array
    {
        return $this->convenioModel->fetchAll($search);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findById(int $id): ?array
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('El identificador del convenio no es válido.');
        }

        return $this->convenioModel->findById($id);
    }

    /**
     * @param array<string, mixed> $input
     */
    public function create(array $input): int
    {
        $data = $this->prepareConvenioData($input);

        return $this->convenioModel->create($data);
    }

    /**
     * @param array<string, mixed> $input
     */
    public function update(int $id, array $input): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('El identificador del convenio no es válido.');
        }

        $data = $this->prepareConvenioData($input);

        $this->convenioModel->update($id, $data);
    }

    public function delete(int $id): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('El identificador del convenio no es válido.');
        }

        $this->convenioModel->delete($id);
    }

    /**
     * @param array<string, mixed> $input
     *
     * @return array<string, mixed>
     */
    private function prepareConvenioData(array $input): array
    {
        $empresaId = $this->toPositiveInt($input['ss_empresa_id'] ?? null);
        if ($empresaId === null) {
            throw new InvalidArgumentException('Selecciona una empresa válida.');
        }

        $estatus = strtolower(trim((string) ($input['estatus'] ?? '')));
        $estatusPermitidos = ['pendiente', 'vigente', 'vencido'];
        if (!in_array($estatus, $estatusPermitidos, true)) {
            throw new InvalidArgumentException('Selecciona un estatus válido.');
        }

        $fechaInicio = $this->toDateString($input['fecha_inicio'] ?? null);
        $fechaFin = $this->toDateString($input['fecha_fin'] ?? null);

        if ($fechaInicio !== null && $fechaFin !== null && $fechaFin < $fechaInicio) {
            throw new InvalidArgumentException('La fecha de fin no puede ser anterior a la fecha de inicio.');
        }

        $versionActual = $this->toNullableString($input['version_actual'] ?? null);

        return [
            'ss_empresa_id' => $empresaId,
            'estatus' => $estatus,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'version_actual' => $versionActual,
        ];
    }

    private function toPositiveInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (!is_numeric($value)) {
            return null;
        }

        $intValue = (int) $value;

        return $intValue > 0 ? $intValue : null;
    }

    private function toNullableString(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    private function toDateString(mixed $value): ?string
    {
        $value = $this->toNullableString($value);
        if ($value === null) {
            return null;
        }

        $date = date_create_from_format('Y-m-d', $value);
        if ($date === false || $date->format('Y-m-d') !== $value) {
            throw new InvalidArgumentException('La fecha proporcionada no es válida.');
        }

        return $value;
    }
}
