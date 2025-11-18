<?php

declare(strict_types=1);

namespace Residencia\Model\Empresa;

require_once __DIR__ . '/../../../common/model/db.php';
require_once __DIR__ . '/../../common/functions/empresafunction.php';

use Common\Model\Database;
use PDO;
use function array_key_exists;
use function empresaPrepareForPersistence;

class EmpresaAddModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    /**
     * @param array<string, string> $data
     */
    public function create(array $data): int
    {
        $payload = empresaPrepareForPersistence($data);

        $sql = <<<'SQL'
            INSERT INTO rp_empresa (
                numero_control,
                nombre,
                rfc,
                representante,
                cargo_representante,
                sector,
                sitio_web,
                contacto_nombre,
                contacto_email,
                telefono,
                estado,
                municipio,
                cp,
                direccion,
                estatus,
                regimen_fiscal,
                notas
            ) VALUES (
                :numero_control,
                :nombre,
                :rfc,
                :representante,
                :cargo_representante,
                :sector,
                :sitio_web,
                :contacto_nombre,
                :contacto_email,
                :telefono,
                :estado,
                :municipio,
                :cp,
                :direccion,
                :estatus,
                :regimen_fiscal,
                :notas
            )
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            ':numero_control' => array_key_exists('numero_control', $payload) ? $payload['numero_control'] : null,
            ':nombre' => $payload['nombre'],
            ':rfc' => $payload['rfc'],
            ':representante' => $payload['representante'],
            ':cargo_representante' => $payload['cargo_representante'],
            ':sector' => $payload['sector'],
            ':sitio_web' => $payload['sitio_web'],
            ':contacto_nombre' => $payload['contacto_nombre'],
            ':contacto_email' => $payload['contacto_email'],
            ':telefono' => $payload['telefono'],
            ':estado' => $payload['estado'],
            ':municipio' => $payload['municipio'],
            ':cp' => $payload['cp'],
            ':direccion' => $payload['direccion'],
            ':estatus' => $payload['estatus'],
            ':regimen_fiscal' => $payload['regimen_fiscal'],
            ':notas' => $payload['notas'],
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * @param array<string, string> $data
     * @return array<int, string>
     */
    public function duplicateFieldErrors(array $data): array
    {
        $errors = [];

        if ($this->existsByNumeroControl($data['numero_control'])) {
            $errors[] = 'Ya existe una empresa registrada con el número de control proporcionado.';
        }

        if ($this->existsByRfc($data['rfc'])) {
            $errors[] = 'Ya existe una empresa registrada con el RFC proporcionado.';
        }

        if ($this->existsByNombre($data['nombre'])) {
            $errors[] = 'Ya existe una empresa registrada con ese nombre.';
        }

        return $errors;
    }

    private function existsByNumeroControl(string $value): bool
    {
        $value = trim($value);

        if ($value === '') {
            return false;
        }

        $statement = $this->pdo->prepare('SELECT 1 FROM rp_empresa WHERE numero_control = :value LIMIT 1');
        $statement->execute([':value' => $value]);

        return (bool) $statement->fetchColumn();
    }

    private function existsByRfc(string $value): bool
    {
        $value = trim($value);

        if ($value === '') {
            return false;
        }

        $statement = $this->pdo->prepare('SELECT 1 FROM rp_empresa WHERE rfc = :value LIMIT 1');
        $statement->execute([':value' => $value]);

        return (bool) $statement->fetchColumn();
    }

    private function existsByNombre(string $value): bool
    {
        $value = trim($value);

        if ($value === '') {
            return false;
        }

        $statement = $this->pdo->prepare('SELECT 1 FROM rp_empresa WHERE LOWER(nombre) = LOWER(:value) LIMIT 1');
        $statement->execute([':value' => $value]);

        return (bool) $statement->fetchColumn();
    }
}
