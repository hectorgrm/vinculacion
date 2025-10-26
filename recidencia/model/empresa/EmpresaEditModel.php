<?php

declare(strict_types=1);

namespace Residencia\Model\Empresa;

require_once __DIR__ . '/../../../common/model/db.php';
require_once __DIR__ . '/../../common/functions/empresafunction.php';

use Common\Model\Database;
use PDO;
use function array_key_exists;
use function empresaPrepareForPersistence;

class EmpresaEditModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    public function findById(int $empresaId): ?array
    {
        $sql = <<<'SQL'
            SELECT id,
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
                   notas,
                   creado_en,
                   actualizado_en
              FROM rp_empresa
             WHERE id = :id
             LIMIT 1
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id' => $empresaId]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result === false ? null : $result;
    }

    /**
     * @param array<string, string> $data
     */
    public function update(int $empresaId, array $data): void
    {
        $payload = empresaPrepareForPersistence($data);

        $sql = <<<'SQL'
            UPDATE rp_empresa
               SET numero_control = :numero_control,
                   nombre = :nombre,
                   rfc = :rfc,
                   representante = :representante,
                   cargo_representante = :cargo_representante,
                   sector = :sector,
                   sitio_web = :sitio_web,
                   contacto_nombre = :contacto_nombre,
                   contacto_email = :contacto_email,
                   telefono = :telefono,
                   estado = :estado,
                   municipio = :municipio,
                   cp = :cp,
                   direccion = :direccion,
                   estatus = :estatus,
                   regimen_fiscal = :regimen_fiscal,
                   notas = :notas
             WHERE id = :id
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
            ':id' => $empresaId,
        ]);
    }
}
