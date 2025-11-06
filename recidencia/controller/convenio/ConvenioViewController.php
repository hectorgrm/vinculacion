<?php

declare(strict_types=1);

namespace Residencia\Controller\Convenio;

require_once __DIR__ . '/../../model/convenio/ConvenioViewModel.php';
require_once __DIR__ . '/../../common/functions/conveniofunction.php';

use DateTimeImmutable;
use PDOException;
use Residencia\Model\Convenio\ConvenioViewModel;
use RuntimeException;

use function convenioFormatDate;
use function convenioFormatDateTime;
use function convenioRenderBadgeClass;
use function convenioRenderBadgeLabel;
use function convenioValueOrDefault;

class ConvenioViewController
{
    private ConvenioViewModel $model;

    public function __construct(?ConvenioViewModel $model = null)
    {
        $this->model = $model ?? new ConvenioViewModel();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getConvenio(int $convenioId): ?array
    {
        try {
            return $this->model->findById($convenioId);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo obtener la informacion del convenio.', 0, $exception);
        }
    }

    /**
     * @param array<string, mixed> $input
     * @return array{
     *     convenioId: ?int,
     *     convenio: ?array<string, mixed>,
     *     machoteObservaciones: array<int, array<string, mixed>>,
     *     historial: array<int, array<string, mixed>>,
     *     controllerError: ?string,
     *     notFoundMessage: ?string,
     *     inputError: ?string
     * }
     */
    public function handle(array $input): array
    {
        $viewData = $this->defaults();

        $convenioId = $this->normalizeId($input['id'] ?? null);

        if ($convenioId === null) {
            $viewData['inputError'] = 'No se proporciono un identificador de convenio valido.';

            return $viewData;
        }

        $viewData['convenioId'] = $convenioId;

        try {
            $convenio = $this->getConvenio($convenioId);
        } catch (RuntimeException $exception) {
            $viewData['controllerError'] = $exception->getMessage();

            return $viewData;
        }

        if ($convenio === null) {
            $viewData['notFoundMessage'] = sprintf('No se encontro el convenio #%d.', $convenioId);

            return $viewData;
        }

        $viewData['convenio'] = $this->decorateConvenio($convenio);

        return $viewData;
    }

    /**
     * @return array{
     *     convenioId: ?int,
     *     convenio: ?array<string, mixed>,
     *     machoteObservaciones: array<int, array<string, mixed>>,
     *     historial: array<int, array<string, mixed>>,
     *     controllerError: ?string,
     *     notFoundMessage: ?string,
     *     inputError: ?string
     * }
     */
    private function defaults(): array
    {
        return [
            'convenioId' => null,
            'convenio' => null,
            'machoteObservaciones' => [],
            'historial' => [],
            'controllerError' => null,
            'notFoundMessage' => null,
            'inputError' => null,
        ];
    }

    private function normalizeId(mixed $value): ?int
    {
        if (is_int($value)) {
            return $value > 0 ? $value : null;
        }

        if (is_string($value)) {
            $value = trim($value);

            if ($value !== '' && preg_match('/^[0-9]+$/', $value) === 1) {
                $id = (int) $value;

                return $id > 0 ? $id : null;
            }
        }

        return null;
    }

    /**
     * @param array<string, mixed> $convenio
     * @return array<string, mixed>
     */
    private function decorateConvenio(array $convenio): array
    {
        $estatus = isset($convenio['estatus']) ? (string) $convenio['estatus'] : null;
        $fechaInicio = isset($convenio['fecha_inicio']) ? (string) $convenio['fecha_inicio'] : null;
        $fechaFin = isset($convenio['fecha_fin']) ? (string) $convenio['fecha_fin'] : null;
        $actualizadoEn = isset($convenio['actualizado_en']) ? (string) $convenio['actualizado_en'] : null;
        $creadoEn = isset($convenio['creado_en']) ? (string) $convenio['creado_en'] : null;
        $empresaCreadoEn = isset($convenio['empresa_creado_en']) ? (string) $convenio['empresa_creado_en'] : null;

        $diasRestantes = null;

        if ($fechaFin !== null && $fechaFin !== '') {
            try {
                $today = new DateTimeImmutable('today');
                $deadline = new DateTimeImmutable($fechaFin);
                $diff = (int) $today->diff($deadline)->format('%r%a');
                $diasRestantes = $diff;
            } catch (\Throwable) {
                $diasRestantes = null;
            }
        }

        $empresaId = isset($convenio['empresa_id']) ? (int) $convenio['empresa_id'] : null;
        $empresaUrl = $empresaId !== null
            ? '../empresa/empresa_view.php?id=' . urlencode((string) $empresaId)
            : null;

        $firmadoPath = $this->normalizePath($convenio['firmado_path'] ?? null);
        $borradorPath = $this->normalizePath($convenio['borrador_path'] ?? null);

        $parentId = $this->normalizeId($convenio['parent_id'] ?? $convenio['renovado_de'] ?? null);
        $parentEmpresaNombreRaw = isset($convenio['parent_empresa_nombre'])
            ? trim((string) $convenio['parent_empresa_nombre'])
            : '';
        $parentEmpresaNombreLabel = $parentEmpresaNombreRaw !== '' ? $parentEmpresaNombreRaw : null;
        $parentFechaInicio = isset($convenio['parent_fecha_inicio'])
            ? (string) $convenio['parent_fecha_inicio']
            : null;
        $parentFechaFin = isset($convenio['parent_fecha_fin'])
            ? (string) $convenio['parent_fecha_fin']
            : null;
        $parentEstatus = isset($convenio['parent_estatus']) ? (string) $convenio['parent_estatus'] : null;
        $parentUrl = $parentId !== null
            ? 'convenio_view.php?id=' . urlencode((string) $parentId)
            : null;

        return array_merge($convenio, [
            'empresa_nombre_label' => convenioValueOrDefault($convenio['empresa_nombre'] ?? null, 'Sin empresa'),
            'empresa_numero_control_label' => convenioValueOrDefault($convenio['empresa_numero_control'] ?? null, 'N/A'),
            'empresa_url' => $empresaUrl,
            'estatus_badge_class' => convenioRenderBadgeClass($estatus),
            'estatus_badge_label' => convenioRenderBadgeLabel($estatus),
            'fecha_inicio_label' => convenioFormatDate($fechaInicio),
            'fecha_fin_label' => convenioFormatDate($fechaFin),
            'creado_en_label' => convenioFormatDateTime($creadoEn),
            'actualizado_en_label' => convenioFormatDateTime($actualizadoEn),
            'empresa_creado_en_label' => convenioFormatDateTime($empresaCreadoEn),
            'dias_restantes' => $diasRestantes,
            'firma_public_url' => $firmadoPath,
            'borrador_public_url' => $borradorPath,
            'renovado_de' => $parentId,
            'parent_id' => $parentId,
            'parent_url' => $parentUrl,
            'parent_empresa_nombre_label' => $parentEmpresaNombreLabel,
            'parent_fecha_inicio_label' => convenioFormatDate($parentFechaInicio),
            'parent_fecha_fin_label' => convenioFormatDate($parentFechaFin),
            'parent_estatus_badge_class' => convenioRenderBadgeClass($parentEstatus),
            'parent_estatus_badge_label' => convenioRenderBadgeLabel($parentEstatus),
        ]);
    }

    private function normalizePath(mixed $value): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        $value = trim($value);

        if ($value === '') {
            return null;
        }

        $normalized = str_replace('\\', '/', $value);

        if (preg_match('/^(https?:\\/\\/|\\.\\.\\/|\\.\\/|\\/)/i', $normalized) === 1) {
            return $normalized;
        }

        $normalized = ltrim($normalized, '/');

        return '../../' . $normalized;
    }
}
