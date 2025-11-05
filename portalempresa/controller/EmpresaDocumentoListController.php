<?php

declare(strict_types=1);

namespace PortalEmpresa\Controller;

use PortalEmpresa\Model\EmpresaDocumentoListModel;

require_once __DIR__ . '/../model/EmpresaDocumentoListModel.php';
require_once __DIR__ . '/../helpers/empresadocumentofunction.php';
require_once __DIR__ . '/../common/functions/empresadocumentofunctions.php';

class EmpresaDocumentoListController
{
    private EmpresaDocumentoListModel $model;

    public function __construct(?EmpresaDocumentoListModel $model = null)
    {
        $this->model = $model ?? new EmpresaDocumentoListModel();
    }

    /**
     * @param array<string, mixed> $filters
     * @return array{
     *     empresa: array<string, mixed>,
     *     documentos: array<int, array<string, mixed>>,
     *     kpis: array{aprobado: int, pendiente: int, rechazado: int},
     *     filters: array{q: string, estatus: string},
     *     statusOptions: array<string, string>
     * }
     */
    public function buildViewData(int $empresaId, array $filters = []): array
    {
        $normalizedFilters = empresaDocumentoNormalizeFilters($filters);

        $empresa = $this->model->findEmpresaById($empresaId);

        if ($empresa === null) {
            throw new \RuntimeException('La empresa solicitada no existe.');
        }

        $tipoEmpresa = empresaDocumentoInferTipoEmpresa($empresa['regimen_fiscal'] ?? null);
        $rawDocuments = $this->model->fetchAssignedDocuments($empresaId, $tipoEmpresa);
        $documents = empresaDocumentoHydrateRecords($rawDocuments);

        $filteredDocuments = empresaDocumentoApplyFilters(
            $documents,
            $normalizedFilters['q'],
            $normalizedFilters['estatus'] === '' ? null : $normalizedFilters['estatus']
        );

        $kpis = empresaDocumentoComputeKpis($documents);

        return [
            'empresa' => $empresa,
            'documentos' => $filteredDocuments,
            'kpis' => $kpis,
            'filters' => $normalizedFilters,
            'statusOptions' => empresaDocumentoStatusOptions(),
            'uploadOptions' => empresaDocumentoUploadBuildOptions($documents),
        ];
    }
}
