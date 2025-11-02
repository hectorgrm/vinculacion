<?php

declare(strict_types=1);

namespace Residencia\Model\Convenio;

require_once __DIR__ . '/../../../common/model/db.php';
require_once __DIR__ . '/../empresa/EmpresaViewModel.php';

use Common\Model\Database;
use PDO;
use Residencia\Model\Empresa\EmpresaViewModel;

class ConvenioDocumentosModel
{
    private PDO $pdo;
    private EmpresaViewModel $empresaModel;

    public function __construct(?PDO $pdo = null, ?EmpresaViewModel $empresaModel = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
        $this->empresaModel = $empresaModel ?? new EmpresaViewModel($this->pdo);
    }

    /**
     * @return array{
     *     empresa_id: int,
     *     tipo_empresa: ?string,
     *     global: array<int, array<string, mixed>>,
     *     custom: array<int, array<string, mixed>>
     * }|null
     */
    public function findByConvenioId(int $convenioId): ?array
    {
        $sql = <<<'SQL'
            SELECT c.empresa_id,
                   e.regimen_fiscal
              FROM rp_convenio AS c
              JOIN rp_empresa AS e ON e.id = c.empresa_id
             WHERE c.id = :id
             LIMIT 1
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id' => $convenioId]);

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        $empresaId = (int) $row['empresa_id'];
        $tipoEmpresa = $this->inferTipoEmpresa($row['regimen_fiscal'] ?? null);

        $documentos = $this->empresaModel->findDocumentosResumen($empresaId, $tipoEmpresa);

        return [
            'empresa_id' => $empresaId,
            'tipo_empresa' => $tipoEmpresa,
            'global' => $documentos['global'] ?? [],
            'custom' => $documentos['custom'] ?? [],
        ];
    }

    private function inferTipoEmpresa(mixed $regimenFiscal): ?string
    {
        if ($regimenFiscal === null) {
            return null;
        }

        $normalized = trim((string) $regimenFiscal);

        if ($normalized === '') {
            return null;
        }

        if (function_exists('mb_strtolower')) {
            $normalized = mb_strtolower($normalized, 'UTF-8');
        } else {
            $normalized = strtolower($normalized);
        }

        if (strpos($normalized, 'moral') !== false) {
            return 'moral';
        }

        if (strpos($normalized, 'fisic') !== false || strpos($normalized, 'fisc') !== false) {
            return 'fisica';
        }

        return null;
    }
}

