<?php
declare(strict_types=1);

namespace Serviciosocial\Controller;

require_once __DIR__ . '/../model/PlazaModel.php';
require_once __DIR__ . '/../../common/model/db.php';

use Common\Model\Database;
use InvalidArgumentException;
use PDO;
use Serviciosocial\Model\PlazaModel;

class PlazaController
{
    private PlazaModel $plazaModel;

    public function __construct(?PDO $pdo = null)
    {
        if ($pdo === null) {
            $pdo = Database::getConnection();
        }

        $this->plazaModel = new PlazaModel($pdo);
    }

    /**
     * Obtener todas las plazas registradas en el sistema.
     *
     * @return array<int, array<string, mixed>>
     */
    public function listAll(): array
    {
        return $this->plazaModel->getAll();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getEmpresas(): array
    {
        return $this->plazaModel->fetchEmpresas();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getConvenios(): array
    {
        return $this->plazaModel->fetchConvenios();
    }

    /**
     * Registrar una nueva plaza.
     *
     * @param array<string, mixed> $input
     */
    public function create(array $input): int
    {
        $data = $this->preparePlazaData($input);

        return $this->plazaModel->create($data);
    }

    /**
     * Obtener una plaza por su identificador.
     *
     * @return array<string, mixed>|null
     */
    public function findById(int $id): ?array
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('El identificador de la plaza no es válido.');
        }

        return $this->plazaModel->findById($id);
    }

    /**
     * @param array<string, mixed> $input
     */
    public function update(int $id, array $input): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('El identificador de la plaza no es válido.');
        }

        $data = $this->preparePlazaData($input);

        $this->plazaModel->update($id, $data);
    }

    public function delete(int $id): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('El identificador de la plaza no es válido.');
        }

        $this->plazaModel->delete($id);
    }

    private function toNullableInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (!is_numeric($value)) {
            return null;
        }

        return (int) $value;
    }

    private function toNullableString(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    private function validateDate(mixed $value, string $errorMessage): string
    {
        $date = $this->toNullableString($value);
        if ($date === null) {
            throw new InvalidArgumentException($errorMessage);
        }

        $dateTime = date_create_from_format('Y-m-d', $date);
        if ($dateTime === false || $dateTime->format('Y-m-d') !== $date) {
            throw new InvalidArgumentException('La fecha proporcionada no es válida.');
        }

        return $date;
    }

    /**
     * @param array<string, mixed> $input
     *
     * @return array<string, mixed>
     */
    private function preparePlazaData(array $input): array
    {
        $nombre = trim((string)($input['plaza_nombre'] ?? ''));
        if ($nombre === '') {
            throw new InvalidArgumentException('El nombre de la plaza es obligatorio.');
        }

        $empresaId = $this->toNullableInt($input['dependencia_id'] ?? null);
        if ($empresaId === null) {
            throw new InvalidArgumentException('Selecciona una empresa o dependencia.');
        }

        $convenioId = $this->toNullableInt($input['programa_id'] ?? null);

        $modalidad = strtolower(trim((string)($input['modalidad'] ?? '')));
        $modalidadesPermitidas = ['presencial', 'hibrida', 'remota'];
        if (!in_array($modalidad, $modalidadesPermitidas, true)) {
            throw new InvalidArgumentException('Selecciona una modalidad válida.');
        }

        $cupo = $this->toNullableInt($input['cupo'] ?? null);
        if ($cupo === null || $cupo < 1) {
            throw new InvalidArgumentException('El cupo debe ser un número entero positivo.');
        }

        $periodoInicio = $this->validateDate($input['periodo_inicio'] ?? null, 'El periodo de inicio es obligatorio.');
        $periodoFin = $this->validateDate($input['periodo_fin'] ?? null, 'El periodo de fin es obligatorio.');

        if ($periodoFin < $periodoInicio) {
            throw new InvalidArgumentException('La fecha de fin no puede ser anterior a la fecha de inicio.');
        }

        $actividades = trim((string)($input['actividades'] ?? ''));
        if ($actividades === '') {
            throw new InvalidArgumentException('Describe las actividades a realizar.');
        }

        $requisitos = trim((string)($input['requisitos'] ?? ''));
        if ($requisitos === '') {
            throw new InvalidArgumentException('Indica los requisitos de la plaza.');
        }

        $responsableNombre = trim((string)($input['responsable_nombre'] ?? ''));
        if ($responsableNombre === '') {
            throw new InvalidArgumentException('Indica el nombre del responsable.');
        }

        $responsableEmail = strtolower(trim((string)($input['responsable_email'] ?? '')));
        if ($responsableEmail === '' || filter_var($responsableEmail, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidArgumentException('Proporciona un correo electrónico válido.');
        }

        $responsablePuesto = $this->toNullableString($input['responsable_puesto'] ?? null);
        $responsableTel = $this->toNullableString($input['responsable_tel'] ?? null);

        $direccion = $this->toNullableString($input['direccion'] ?? null);
        $ubicacion = $this->toNullableString($input['ubicacion'] ?? null);

        $estado = strtolower(trim((string)($input['estado_plaza'] ?? '')));
        $estadosPermitidos = ['activa', 'inactiva'];
        if (!in_array($estado, $estadosPermitidos, true)) {
            throw new InvalidArgumentException('Selecciona un estado válido para la plaza.');
        }

        $observaciones = $this->toNullableString($input['observaciones'] ?? null);
        $horarioTexto = $this->toNullableString($input['horario_texto'] ?? null);
        if ($horarioTexto !== null) {
            $horarioLabel = 'Horario: ' . $horarioTexto;
            $observaciones = $observaciones !== null && $observaciones !== ''
                ? $horarioLabel . "\n" . $observaciones
                : $horarioLabel;
        }

        return [
            'nombre'             => $nombre,
            'ss_empresa_id'      => $empresaId,
            'ss_convenio_id'     => $convenioId,
            'direccion'          => $direccion,
            'cupo'               => $cupo,
            'periodo_inicio'     => $periodoInicio,
            'periodo_fin'        => $periodoFin,
            'modalidad'          => $modalidad,
            'actividades'        => $actividades,
            'requisitos'         => $requisitos,
            'responsable_nombre' => $responsableNombre,
            'responsable_puesto' => $responsablePuesto,
            'responsable_email'  => $responsableEmail,
            'responsable_tel'    => $responsableTel,
            'ubicacion'          => $ubicacion,
            'estado'             => $estado,
            'observaciones'      => $observaciones,
        ];
    }
}
