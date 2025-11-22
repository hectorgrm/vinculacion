<?php

declare(strict_types=1);

namespace Residencia\Controller\Empresa;

require_once __DIR__ . '/../../model/empresa/EmpresaViewModel.php';
require_once __DIR__ . '/../../common/functions/empresa/empresa_functions_view.php';

use PDOException;
use Residencia\Model\Empresa\EmpresaViewModel;
use RuntimeException;

use function empresaViewBuildGestionDocumentosUrl;
use function empresaViewDecorate;
use function empresaViewDecorateConvenios;
use function empresaViewDecorateEstudiantes;
use function empresaViewDecorateDocumentos;
use function empresaViewDefaults;
use function empresaViewInferTipoEmpresa;
use function empresaViewInputErrorMessage;
use function empresaViewNormalizeId;
use function empresaViewNotFoundMessage;

class EmpresaViewController
{
    private EmpresaViewModel $model;

    public function __construct(?EmpresaViewModel $model = null)
    {
        $this->model = $model ?? new EmpresaViewModel();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getEmpresa(int $empresaId): ?array
    {
        try {
            return $this->model->findById($empresaId);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo obtener la informacion de la empresa.', 0, $exception);
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getConveniosActivos(int $empresaId): array
    {
        try {
            return $this->model->findActiveConveniosByEmpresaId($empresaId);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudieron obtener los convenios activos de la empresa.', 0, $exception);
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getEstudiantes(int $empresaId): array
    {
        try {
            return $this->model->findEstudiantesByEmpresa($empresaId);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudieron obtener los estudiantes vinculados a la empresa.', 0, $exception);
        }
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getPortalAccess(int $empresaId): ?array
    {
        try {
            return $this->model->findPortalAccessByEmpresaId($empresaId);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo obtener la información del portal de acceso.', 0, $exception);
        }
    }

    /**
     * @return array{global: array<int, array<string, mixed>>, custom: array<int, array<string, mixed>>}
     */
    public function getDocumentos(int $empresaId, ?string $tipoEmpresa): array
    {
        try {
            return $this->model->findDocumentosResumen($empresaId, $tipoEmpresa);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo obtener la documentacion de la empresa.', 0, $exception);
        }
    }

    /**
     * @param array<string, mixed> $input
     * @return array{
     *     empresaId: ?int,
     *     empresa: ?array<string, mixed>,
     *     conveniosActivos: array<int, array<string, mixed>>,
     *     controllerError: ?string,
     *     notFoundMessage: ?string,
     *     inputError: ?string,
     *     machoteData: ?array<string, mixed>
     * }
     */
    public function handle(array $input): array
    {
        $viewData = empresaViewDefaults();

        $empresaId = empresaViewNormalizeId($input['id'] ?? null);

        if ($empresaId === null) {
            $viewData['inputError'] = empresaViewInputErrorMessage();

            return $viewData;
        }

        $viewData['empresaId'] = $empresaId;

        $empresa = $this->getEmpresa($empresaId);

        if ($empresa === null) {
            $viewData['notFoundMessage'] = empresaViewNotFoundMessage($empresaId);

            return $viewData;
        }

        $viewData['empresa'] = empresaViewDecorate($empresa);

        $tipoEmpresaInferido = $viewData['empresa']['tipo_empresa_inferido'] ?? empresaViewInferTipoEmpresa($empresa['regimen_fiscal'] ?? null);

        $documentos = $this->getDocumentos($empresaId, is_string($tipoEmpresaInferido) ? $tipoEmpresaInferido : null);
        $gestionUrl = empresaViewBuildGestionDocumentosUrl($empresaId);
        $documentosDecorated = empresaViewDecorateDocumentos(
            $documentos['global'],
            $documentos['custom'],
            $gestionUrl,
            $empresaId
        );

        $viewData['documentos'] = $documentosDecorated['items'];
        $viewData['documentosStats'] = $documentosDecorated['stats'];
        $viewData['documentosGestionUrl'] = $gestionUrl;

        $convenios = $this->getConveniosActivos($empresaId);
        $viewData['conveniosActivos'] = empresaViewDecorateConvenios($convenios);

        $estudiantes = $this->getEstudiantes($empresaId);
        $viewData['estudiantes'] = empresaViewDecorateEstudiantes($estudiantes);

        $viewData['machoteData'] = $this->getMachoteResumen($empresaId);
        $viewData['portalAccess'] = $this->getPortalAccess($empresaId);

        return $viewData;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getMachoteResumen(int $empresaId): ?array
    {
        try {
            $machote = $this->model->findLatestMachoteResumen($empresaId);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo obtener la información del machote asociado.', 0, $exception);
        }

        if ($machote === null) {
            return null;
        }

        $total = max(0, (int) ($machote['comentarios_total'] ?? 0));
        $resueltos = max(0, (int) ($machote['comentarios_resueltos'] ?? 0));
        $pendientes = max(0, $total - $resueltos);
        $progreso = $total > 0 ? (int) round(($resueltos / $total) * 100) : 0;
        $estadoLabel = $this->normalizeMachoteEstado((string) ($machote['estatus'] ?? ''), $total, $resueltos);

        return [
            'id' => (int) $machote['id'],
            'version' => $machote['version_local'] ?? null,
            'estado' => $estadoLabel,
            'pendientes' => $pendientes,
            'resueltos' => $resueltos,
            'total' => $total,
            'progreso' => $progreso,
            'convenio_id' => isset($machote['convenio_id']) ? (int) $machote['convenio_id'] : null,
        ];
    }

    private function normalizeMachoteEstado(string $estadoRaw, int $total, int $resueltos): string
    {
        $estadoTrimmed = trim($estadoRaw);
        $estadoLower = function_exists('mb_strtolower') ? mb_strtolower($estadoTrimmed, 'UTF-8') : strtolower($estadoTrimmed);

        $ascii = $estadoLower;

        if (function_exists('iconv')) {
            $converted = @iconv('UTF-8', 'ASCII//TRANSLIT', $estadoLower);
            if ($converted !== false) {
                $ascii = $converted;
            }
        }

        $comparable = preg_replace('/[^a-z]/', '', $ascii) ?? '';

        if (str_contains($comparable, 'revision') || str_contains($comparable, 'reabri')) {
            return 'En revisión';
        }

        if (str_contains($comparable, 'observ')) {
            return 'Con observaciones';
        }

        if (str_contains($comparable, 'aprob')) {
            return 'Aprobado';
        }

        if (str_contains($comparable, 'pendient')) {
            return 'Pendiente';
        }

        if ($total === 0) {
            return 'En revisión';
        }

        if ($resueltos >= $total) {
            return 'Aprobado';
        }

        return 'Con observaciones';
    }
}
