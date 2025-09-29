<?php

declare(strict_types=1);

namespace Serviciosocial\Controller;

require_once __DIR__ . '/../model/PeriodoModel.php';
require_once __DIR__ . '/../../common/model/db.php';

use Common\Model\Database;
use DateTimeImmutable;
use InvalidArgumentException;
use PDO;
use Serviciosocial\Model\PeriodoModel;

class PeriodoController
{
    private PeriodoModel $model;

    public function __construct(?PDO $pdo = null)
    {
        if ($pdo === null) {
            $pdo = Database::getConnection();
        }

        $this->model = new PeriodoModel($pdo);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function list(string $searchTerm = ''): array
    {
        $searchTerm = trim($searchTerm);
        if ($searchTerm === '') {
            return $this->model->getAll();
        }

        return $this->model->search($searchTerm);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }

        return $this->model->findById($id);
    }

    /**
     * @param array<string, mixed> $input
     */
    public function update(int $id, array $input): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('El identificador del periodo no es válido.');
        }

        $numero = filter_var($input['numero'] ?? null, FILTER_VALIDATE_INT);
        if ($numero === false || $numero === null || $numero < 1) {
            throw new InvalidArgumentException('El número del periodo debe ser un entero positivo.');
        }

        $estatus = strtolower(trim((string)($input['estatus'] ?? '')));
        $allowedStatus = ['abierto', 'en_revision', 'completado'];
        if (!in_array($estatus, $allowedStatus, true)) {
            throw new InvalidArgumentException('Selecciona un estatus válido.');
        }

        $abiertoEn = $this->parseDateTimeLocal($input['abierto_en'] ?? null, true, 'La fecha de apertura es obligatoria.');
        $cerradoEn = $this->parseDateTimeLocal($input['cerrado_en'] ?? null, false, '');

        if ($cerradoEn !== null) {
            $abiertoDate = new DateTimeImmutable($abiertoEn);
            $cerradoDate = new DateTimeImmutable($cerradoEn);
            if ($cerradoDate < $abiertoDate) {
                throw new InvalidArgumentException('La fecha de cierre no puede ser anterior a la fecha de apertura.');
            }
        }

        $data = [
            'numero'      => $numero,
            'estatus'     => $estatus,
            'abierto_en'  => $abiertoEn,
            'cerrado_en'  => $cerradoEn,
        ];

        $this->model->update($id, $data);
    }

    private function parseDateTimeLocal(mixed $value, bool $required, string $errorMessage): ?string
    {
        if ($value === null) {
            if ($required) {
                throw new InvalidArgumentException($errorMessage);
            }

            return null;
        }

        $stringValue = trim((string) $value);
        if ($stringValue === '') {
            if ($required) {
                throw new InvalidArgumentException($errorMessage);
            }

            return null;
        }

        $date = date_create_from_format('Y-m-d\TH:i', $stringValue);
        if ($date === false) {
            throw new InvalidArgumentException('La fecha y hora proporcionadas no son válidas.');
        }

        return $date->format('Y-m-d H:i:s');
    }
}
