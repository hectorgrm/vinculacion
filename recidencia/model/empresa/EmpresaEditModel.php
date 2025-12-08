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

    public function hasConvenioActivo(int $empresaId): bool
    {
        $sql = <<<'SQL'
            SELECT 1
              FROM rp_convenio
             WHERE empresa_id = :empresa_id
               AND estatus = 'Activa'
             LIMIT 1
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':empresa_id' => $empresaId]);

        return (bool) $statement->fetchColumn();
    }

    /**
     * @return array{estatus: ?string}|null
     */
    public function findLatestMachoteStatus(int $empresaId): ?array
    {
        $sql = <<<'SQL'
            SELECT cm.estatus
              FROM rp_convenio_machote AS cm
              INNER JOIN rp_convenio AS c ON c.id = cm.convenio_id
             WHERE c.empresa_id = :empresa_id
             ORDER BY cm.actualizado_en DESC, cm.id DESC
             LIMIT 1
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':empresa_id' => $empresaId]);

        $estatus = $statement->fetchColumn();

        if ($estatus === false) {
            return null;
        }

        return ['estatus' => $estatus !== null ? (string) $estatus : null];
    }

    /**
     * @return array{total:int, aprobados:int, porcentaje:int}
     */
    public function getDocumentosStats(int $empresaId, ?string $tipoEmpresa = null): array
    {
        $globalStats = $this->getGlobalDocsStats($empresaId, $tipoEmpresa);
        $customStats = $this->getCustomDocsStats($empresaId);

        $total = $globalStats['total'] + $customStats['total'];
        $aprobados = $globalStats['aprobados'] + $customStats['aprobados'];
        $porcentaje = $total > 0 ? (int) round(($aprobados / $total) * 100) : 0;

        return [
            'total' => $total,
            'aprobados' => $aprobados,
            'porcentaje' => $porcentaje,
        ];
    }

    /**
     * @return array{total:int, aprobados:int}
     */
    private function getGlobalDocsStats(int $empresaId, ?string $tipoEmpresa = null): array
    {
        $sql = <<<'SQL'
            SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN LOWER(COALESCE(doc_estatus, '')) = 'aprobado' THEN 1 ELSE 0 END) AS aprobados
            FROM (
                SELECT
                    t.id,
                    (
                        SELECT d.estatus
                          FROM rp_empresa_doc AS d
                         WHERE d.empresa_id = :empresa_id
                           AND d.tipo_global_id = t.id
                         ORDER BY d.actualizado_en DESC, d.id DESC
                         LIMIT 1
                    ) AS doc_estatus
                  FROM rp_documento_tipo AS t
                 WHERE t.activo = 1
        SQL;

        $params = [':empresa_id' => $empresaId];

        if ($tipoEmpresa !== null && $tipoEmpresa !== '') {
            $sql .= ' AND (t.tipo_empresa = :tipo_empresa OR t.tipo_empresa = \'ambas\')';
            $params[':tipo_empresa'] = $tipoEmpresa;
        }

        $sql .= '
            ) AS docs
        ';

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        $row = $statement->fetch(PDO::FETCH_ASSOC) ?: ['total' => 0, 'aprobados' => 0];

        return [
            'total' => isset($row['total']) ? (int) $row['total'] : 0,
            'aprobados' => isset($row['aprobados']) ? (int) $row['aprobados'] : 0,
        ];
    }

    /**
     * @return array{total:int, aprobados:int}
     */
    private function getCustomDocsStats(int $empresaId): array
    {
        $sql = <<<'SQL'
            SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN LOWER(COALESCE(doc_estatus, '')) = 'aprobado' THEN 1 ELSE 0 END) AS aprobados
            FROM (
                SELECT
                    tipo.id,
                    (
                        SELECT d.estatus
                          FROM rp_empresa_doc AS d
                         WHERE d.empresa_id = :empresa_id
                           AND d.tipo_personalizado_id = tipo.id
                         ORDER BY d.actualizado_en DESC, d.id DESC
                         LIMIT 1
                    ) AS doc_estatus
                  FROM rp_documento_tipo_empresa AS tipo
                 WHERE tipo.empresa_id = :empresa_id
            ) AS docs
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':empresa_id' => $empresaId]);

        $row = $statement->fetch(PDO::FETCH_ASSOC) ?: ['total' => 0, 'aprobados' => 0];

        return [
            'total' => isset($row['total']) ? (int) $row['total'] : 0,
            'aprobados' => isset($row['aprobados']) ? (int) $row['aprobados'] : 0,
        ];
    }
}
