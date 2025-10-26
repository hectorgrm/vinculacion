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
}
