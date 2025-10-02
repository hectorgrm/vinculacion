<?php

declare(strict_types=1);

namespace Serviciosocial\Controller;

require_once __DIR__ . '/../model/EmpresaModel.php';
require_once __DIR__ . '/../../common/model/db.php';

use Common\Model\Database;
use InvalidArgumentException;
use PDO;
use Serviciosocial\Model\EmpresaModel;

class EmpresaController
{
    private EmpresaModel $empresaModel;

    public function __construct(?PDO $pdo = null)
    {
        if ($pdo === null) {
            $pdo = Database::getConnection();
        }

        $this->empresaModel = new EmpresaModel($pdo);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listEmpresas(?string $search = null): array
    {
        $term = $search !== null ? trim($search) : null;

        if ($term === '') {
            $term = null;
        }

        return $this->empresaModel->fetchAll($term);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findEmpresa(int $id): ?array
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('El identificador de la empresa debe ser un número positivo.');
        }

        return $this->empresaModel->findById($id);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function createEmpresa(array $data): int
    {
        $nombre = trim((string) ($data['nombre'] ?? ''));
        if ($nombre === '') {
            throw new InvalidArgumentException('El nombre de la empresa es obligatorio.');
        }

        $contactoNombre = isset($data['contacto_nombre']) ? trim((string) $data['contacto_nombre']) : null;
        if ($contactoNombre === '') {
            $contactoNombre = null;
        }

        $contactoEmail = isset($data['contacto_email']) ? trim((string) $data['contacto_email']) : null;
        if ($contactoEmail === '') {
            $contactoEmail = null;
        }

        if ($contactoEmail !== null && filter_var($contactoEmail, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidArgumentException('El correo electrónico del contacto no es válido.');
        }

        $telefono = isset($data['telefono']) ? trim((string) $data['telefono']) : null;
        if ($telefono === '') {
            $telefono = null;
        }

        $direccion = isset($data['direccion']) ? trim((string) $data['direccion']) : null;
        if ($direccion === '') {
            $direccion = null;
        }

        $estado = strtolower(trim((string) ($data['estado'] ?? 'activo')));
        $allowedStates = ['activo', 'inactivo'];
        if (!in_array($estado, $allowedStates, true)) {
            $estado = 'activo';
        }

        $payload = [
            'nombre' => $nombre,
            'contacto_nombre' => $contactoNombre,
            'contacto_email' => $contactoEmail,
            'telefono' => $telefono,
            'direccion' => $direccion,
            'estado' => $estado,
        ];

        return $this->empresaModel->create($payload);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function updateEmpresa(int $id, array $data): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('El identificador de la empresa debe ser un número positivo.');
        }

        $nombre = trim((string) ($data['nombre'] ?? ''));
        if ($nombre === '') {
            throw new InvalidArgumentException('El nombre de la empresa es obligatorio.');
        }

        $contactoNombre = isset($data['contacto_nombre']) ? trim((string) $data['contacto_nombre']) : null;
        $contactoEmail = isset($data['contacto_email']) ? trim((string) $data['contacto_email']) : null;
        $telefono = isset($data['telefono']) ? trim((string) $data['telefono']) : null;
        $direccion = isset($data['direccion']) ? trim((string) $data['direccion']) : null;
        $estado = strtolower(trim((string) ($data['estado'] ?? 'activo')));

        $allowedStates = ['activo', 'inactivo'];
        if (!in_array($estado, $allowedStates, true)) {
            throw new InvalidArgumentException('El estado de la empresa no es válido.');
        }

        if ($contactoEmail !== null && $contactoEmail !== '' && filter_var($contactoEmail, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidArgumentException('El correo electrónico del contacto no es válido.');
        }

        $payload = [
            'nombre' => $nombre,
            'contacto_nombre' => $contactoNombre,
            'contacto_email' => $contactoEmail,
            'telefono' => $telefono,
            'direccion' => $direccion,
            'estado' => $estado,
        ];

        $this->empresaModel->update($id, $payload);
    }
}
