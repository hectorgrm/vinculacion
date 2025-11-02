<?php

declare(strict_types=1);

namespace Residencia\Controller\Convenio;

require_once __DIR__ . '/../../model/convenio/ConvenioViewModel.php';
require_once __DIR__ . '/../../common/functions/conveniofunction.php';
require_once __DIR__ . '/ConvenioDocumentosController.php';

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
    private ConvenioDocumentosController $documentosController;

    public function __construct(?ConvenioViewModel $model = null, ?ConvenioDocumentosController $documentosController = null)
    {
        $this->model = $model ?? new ConvenioViewModel();
        $this->documentosController = $documentosController ?? new ConvenioDocumentosController();
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
     *     documentosAsociados: array<int, array<string, mixed>>,
     *     historial: array<int, array<string, mixed>>,
     *     documentosError: ?string,
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

        try {
            $viewData['documentosAsociados'] = $this->documentosController->getDocumentos($convenioId);
        } catch (RuntimeException $exception) {
            $viewData['documentosAsociados'] = [];
            $viewData['documentosError'] = $exception->getMessage();
        }

        return $viewData;
    }

    /**
     * @return array{
     *     convenioId: ?int,
     *     convenio: ?array<string, mixed>,
     *     machoteObservaciones: array<int, array<string, mixed>>,
     *     documentosAsociados: array<int, array<string, mixed>>,
     *     historial: array<int, array<string, mixed>>,
     *     documentosError: ?string,
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
            'documentosAsociados' => [],
            'historial' => [],
            'documentosError' => null,
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
            'dias_restantes' => $diasRestantes,
            'firma_public_url' => $firmadoPath,
            'borrador_public_url' => $borradorPath,
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
