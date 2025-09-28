<?php

declare(strict_types=1);

namespace Serviciosocial\Controller;

require_once __DIR__ . '/../model/GestEstudianteModel.php';
require_once __DIR__ . '/../../common/model/db.php';

use Common\Model\Database;
use PDO;
use Serviciosocial\Model\GestEstudianteModel;

class GestEstudianteController
{
    private GestEstudianteModel $model;

    public function __construct(?PDO $pdo = null)
    {
        if ($pdo === null) {
            $pdo = Database::getConnection();
        }

        $this->model = new GestEstudianteModel($pdo);
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
    public function updateServicioSocial(int $id, array $input): void
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('Identificador de estudiante inválido.');
        }

        $nombre = $this->requireString($input['nombre'] ?? null, 'nombre completo', 191);
        $matricula = $this->requireString($input['matricula'] ?? null, 'matrícula', 50);
        $carrera = $this->toNullableString($input['carrera'] ?? null, 'carrera', 100);
        $semestre = $this->toNullableSemestre($input['semestre'] ?? null);
        $plazaId = $this->toNullablePositiveInt($input['plaza_id'] ?? null);
        $dependencia = $this->toNullableString($input['dependencia_asignada'] ?? null, 'dependencia asignada');
        $proyecto = $this->toNullableString($input['proyecto'] ?? null, 'proyecto', 255);
        $periodoInicio = $this->toNullableDate($input['periodo_inicio'] ?? null);
        $periodoFin = $this->toNullableDate($input['periodo_fin'] ?? null);
        $horasAcumuladas = $this->toNonNegativeInt($input['horas_acumuladas'] ?? null, 'horas acumuladas');
        $horasRequeridas = $this->toNonNegativeInt($input['horas_requeridas'] ?? null, 'horas requeridas');
        $estadoServicio = $this->toEstadoServicio($input['estado_servicio'] ?? '');
        $asesorInterno = $this->toNullableString($input['asesor_interno'] ?? null, 'asesor interno');
        $documentosEntregados = $this->toNullableText($input['documentos_entregados'] ?? null, 'documentos entregados');
        $observaciones = $this->toNullableText($input['observaciones'] ?? null, 'observaciones');
        $email = $this->toNullableEmail($input['email'] ?? null);
        $telefono = $this->toNullableString($input['telefono'] ?? null, 'teléfono', 30);

        if ($periodoInicio !== null && $periodoFin !== null) {
            $inicioDate = new \DateTimeImmutable($periodoInicio);
            $finDate = new \DateTimeImmutable($periodoFin);

            if ($finDate < $inicioDate) {
                throw new \InvalidArgumentException('La fecha de fin no puede ser anterior al inicio.');
            }
        }

        if ($horasAcumuladas > $horasRequeridas) {
            throw new \InvalidArgumentException('Las horas acumuladas no pueden exceder las horas requeridas.');
        }

        $this->model->updateServicioSocial($id, [
            'nombre'               => $nombre,
            'matricula'            => $matricula,
            'carrera'              => $carrera,
            'semestre'             => $semestre,
            'plaza_id'             => $plazaId,
            'dependencia_asignada' => $dependencia,
            'proyecto'             => $proyecto,
            'periodo_inicio'       => $periodoInicio,
            'periodo_fin'          => $periodoFin,
            'horas_acumuladas'     => $horasAcumuladas,
            'horas_requeridas'     => $horasRequeridas,
            'estado_servicio'      => $estadoServicio,
            'asesor_interno'       => $asesorInterno,
            'documentos_entregados'=> $documentosEntregados,
            'observaciones'        => $observaciones,
            'email'                => $email,
            'telefono'             => $telefono,
        ]);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getPlazas(): array
    {
        return $this->model->fetchPlazas();
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

            if (!is_numeric($value)) {
                throw new \InvalidArgumentException('El identificador de la plaza no es válido.');
            }

            $intValue = (int) $value;
        } else {
            throw new \InvalidArgumentException('El identificador de la plaza no es válido.');
        }

        if ($intValue <= 0) {
            throw new \InvalidArgumentException('El identificador de la plaza no es válido.');
        }

        return $intValue;
    }

    private function requireString(mixed $value, string $fieldLabel, int $maxLength): string
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException(sprintf('El campo %s es obligatorio.', $fieldLabel));
        }

        $value = trim($value);

        if ($value === '') {
            throw new \InvalidArgumentException(sprintf('El campo %s es obligatorio.', $fieldLabel));
        }

        if ($this->stringLength($value) > $maxLength) {
            throw new \InvalidArgumentException(sprintf('El campo %s no puede exceder %d caracteres.', $fieldLabel, $maxLength));
        }

        return $value;
    }

    private function toNullableString(mixed $value, string $fieldLabel, int $maxLength = 191): ?string
    {
        if ($value === null) {
            return null;
        }

        if (!is_string($value)) {
            throw new \InvalidArgumentException(sprintf('El campo %s no es válido.', $fieldLabel));
        }

        $value = trim($value);

        if ($value === '') {
            return null;
        }

        if ($this->stringLength($value) > $maxLength) {
            throw new \InvalidArgumentException(sprintf('El campo %s no puede exceder %d caracteres.', $fieldLabel, $maxLength));
        }

        return $value;
    }

    private function toNullableText(mixed $value, string $fieldLabel): ?string
    {
        if ($value === null) {
            return null;
        }

        if (!is_string($value)) {
            throw new \InvalidArgumentException(sprintf('El campo %s no es válido.', $fieldLabel));
        }

        $value = trim($value);

        if ($value === '') {
            return null;
        }

        return $value;
    }

    private function toNullableSemestre(mixed $value): ?int
    {
        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            $value = trim($value);

            if ($value === '') {
                return null;
            }
        }

        if (!is_numeric($value)) {
            throw new \InvalidArgumentException('El semestre debe ser un número entero.');
        }

        $intValue = (int) $value;

        if ($intValue <= 0) {
            throw new \InvalidArgumentException('El semestre debe ser mayor a cero.');
        }

        if ($intValue > 15) {
            throw new \InvalidArgumentException('El semestre debe ser un número válido (1-15).');
        }

        return $intValue;
    }

    private function toNullableEmail(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (!is_string($value)) {
            throw new \InvalidArgumentException('Proporciona un correo electrónico válido.');
        }

        $value = trim($value);

        if ($value === '') {
            return null;
        }

        if ($this->stringLength($value) > 191) {
            throw new \InvalidArgumentException('El correo electrónico no puede exceder 191 caracteres.');
        }

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Proporciona un correo electrónico válido.');
        }

        return strtolower($value);
    }

    private function stringLength(string $value): int
    {
        return function_exists('mb_strlen') ? mb_strlen($value) : strlen($value);
    }

    private function toNullableDate(mixed $value): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        $value = trim($value);

        if ($value === '') {
            return null;
        }

        $date = \DateTimeImmutable::createFromFormat('Y-m-d', $value);

        if ($date === false) {
            throw new \InvalidArgumentException('Formato de fecha inválido, usa AAAA-MM-DD.');
        }

        return $date->format('Y-m-d');
    }

    private function toNonNegativeInt(mixed $value, string $fieldLabel): int
    {
        if ($value === null || $value === '') {
            return 0;
        }

        if (is_int($value)) {
            $intValue = $value;
        } elseif (is_string($value)) {
            $value = trim($value);

            if ($value === '') {
                return 0;
            }

            if (!is_numeric($value)) {
                throw new \InvalidArgumentException(sprintf('El campo %s debe ser un número entero.', $fieldLabel));
            }

            $intValue = (int) $value;
        } else {
            throw new \InvalidArgumentException(sprintf('El campo %s debe ser un número entero.', $fieldLabel));
        }

        if ($intValue < 0) {
            throw new \InvalidArgumentException(sprintf('El campo %s no puede ser negativo.', $fieldLabel));
        }

        return $intValue;
    }

    private function toEstadoServicio(mixed $value): string
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException('Selecciona un estado de servicio válido.');
        }

        $value = strtolower(trim($value));

        $allowed = ['pendiente', 'en_curso', 'concluido', 'cancelado'];

        if (!in_array($value, $allowed, true)) {
            throw new \InvalidArgumentException('Selecciona un estado de servicio válido.');
        }

        return $value;
    }
}
