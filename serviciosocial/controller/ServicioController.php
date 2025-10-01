<?php
declare(strict_types=1);

namespace Serviciosocial\Controller;

require_once __DIR__ . '/../model/ServicioModel.php';
require_once __DIR__ . '/../../common/model/db.php';

use Common\Model\Database;
use InvalidArgumentException;
use PDO;
use Serviciosocial\Model\ServicioModel;

class ServicioController
{
    private ServicioModel $servicioModel;

    public function __construct(?PDO $pdo = null)
    {
        if ($pdo === null) {
            $pdo = Database::getConnection();
        }

        $this->servicioModel = new ServicioModel($pdo);
    }

    /**
     * Obtener la lista de servicios registrados, opcionalmente filtrados por búsqueda.
     *
     * @return array<int, array<string, mixed>>
     */
    public function listServicios(?string $search = null): array
    {
        $term = $search !== null ? trim($search) : null;

        if ($term === '') {
            $term = null;
        }

        return $this->servicioModel->fetchAll($term);
    }

    /**
     * Obtener la información detallada de un servicio.
     *
     * @return array<string, mixed>|null
     */
    public function findServicio(int $id): ?array
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('El identificador del servicio debe ser un número positivo.');
        }

        return $this->servicioModel->findById($id);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getPlazasCatalog(): array
    {
        return $this->servicioModel->fetchPlazasCatalog();
    }

    /**
     * @param array<string, mixed> $input
     */
    public function updateServicio(int $id, array $input): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('El identificador del servicio debe ser un número positivo.');
        }

        $plazaId = $this->toNullablePositiveInt($input['plaza'] ?? null);
        $estatus = $this->normalizeEstatus($input['estatus'] ?? null);
        $horasAcumuladas = $this->toNonNegativeInt($input['horas'] ?? null);
        $observaciones = $this->toNullableText($input['observaciones'] ?? null);

        $this->servicioModel->update($id, [
            'plaza_id'         => $plazaId,
            'estatus'          => $estatus,
            'horas_acumuladas' => $horasAcumuladas,
            'observaciones'    => $observaciones,
        ]);
    }

    private function toNullablePositiveInt(mixed $value): ?int
    {
        if ($value === null || $value === '' || $value === '0') {
            return null;
        }

        if (is_int($value)) {
            $intValue = $value;
        } elseif (is_string($value)) {
            $value = trim($value);

            if ($value === '' || $value === '0') {
                return null;
            }

            if (!ctype_digit($value)) {
                throw new InvalidArgumentException('El identificador de la plaza no es válido.');
            }

            $intValue = (int) $value;
        } else {
            throw new InvalidArgumentException('El identificador de la plaza no es válido.');
        }

        if ($intValue <= 0) {
            throw new InvalidArgumentException('El identificador de la plaza no es válido.');
        }

        return $intValue;
    }

    private function normalizeEstatus(mixed $value): string
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException('Selecciona un estado válido para el servicio.');
        }

        $value = strtolower(trim($value));

        $allowed = ['prealta', 'activo', 'concluido', 'cancelado'];

        if (!in_array($value, $allowed, true)) {
            throw new InvalidArgumentException('Selecciona un estado válido para el servicio.');
        }

        return $value;
    }

    private function toNonNegativeInt(mixed $value): int
    {
        if ($value === null || $value === '') {
            return 0;
        }

        if (is_int($value)) {
            $intValue = $value;
        } elseif (is_numeric($value)) {
            $intValue = (int) $value;
        } else {
            throw new InvalidArgumentException('Las horas acumuladas deben ser un número entero.');
        }

        if ($intValue < 0) {
            throw new InvalidArgumentException('Las horas acumuladas no pueden ser negativas.');
        }

        return $intValue;
    }

    private function toNullableText(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (!is_string($value)) {
            throw new InvalidArgumentException('Las observaciones proporcionadas no son válidas.');
        }

        $value = trim($value);

        return $value === '' ? null : $value;
    }
}
