<?php
declare(strict_types=1);

namespace PortalEmpresa\Controller\Machote;

require_once __DIR__ . '/../helpers/empresaconveniofunction.php';
require_once __DIR__ . '/../model/MachoteViewModel.php';
require_once __DIR__ . '/../model/MachoteComentarioModel.php';
require_once __DIR__ . '/../../recidencia/common/helpers/machote/machote_revisar_helper.php';

use DateTimeImmutable;
use PortalEmpresa\Helpers\EmpresaConvenioHelper;
use PortalEmpresa\Model\Machote\MachoteViewModel;
use PortalEmpresa\Model\Machote\MachoteComentarioModel as MachoteComentarioThreadModel;
use RuntimeException;
use function Residencia\Common\Helpers\Machote\resumenComentarios;

class MachoteViewController
{
    private MachoteViewModel $model;

    public function __construct(?MachoteViewModel $model = null)
    {
        $this->model = $model ?? new MachoteViewModel();
    }

    /**
     * @return array<string, mixed>
     */
    public function handleView(int $machoteId, int $empresaId): array
    {
        if ($machoteId <= 0) {
            throw new RuntimeException('El machote solicitado no es válido.');
        }

        if ($empresaId <= 0) {
            throw new RuntimeException('La sesión de la empresa no es válida.');
        }

        $record = $this->model->getMachoteForEmpresa($machoteId, $empresaId);

        if ($record === null) {
            throw new RuntimeException('No se encontró el machote solicitado.');
        }

        $comentarioModel = new MachoteComentarioThreadModel();
        $comentarios = $comentarioModel->getComentariosConRespuestas($machoteId);
        $resumen = resumenComentarios($comentarios);

        $documento = $this->buildDocumentoMeta($record);
        $estadoMachote = (string) ($record['machote_estatus'] ?? ($resumen['estado'] ?? 'En revisión'));

        $machote = [
            'id' => $machoteId,
            'version_local' => (string) ($record['version_local'] ?? 'v1.0'),
            'confirmado' => (int) ($record['confirmacion_empresa'] ?? 0) === 1,
            'contenido_html' => (string) ($record['contenido_html'] ?? ''),
            'actualizado_en' => $record['machote_actualizado_en'] ?? null,
            'estatus' => $estadoMachote,
        ];

        $empresa = [
            'id' => (int) ($record['empresa_id'] ?? 0),
            'nombre' => (string) ($record['empresa_nombre'] ?? ''),
            'logo_path' => $record['empresa_logo'] ?? null,
        ];

        $convenio = [
            'id' => (int) ($record['convenio_id'] ?? 0),
            'estatus' => $record['convenio_estatus'] ?? null,
            'folio' => $record['convenio_folio'] ?? null,
            'fecha_inicio' => $record['convenio_fecha_inicio'] ?? null,
            'fecha_fin' => $record['convenio_fecha_fin'] ?? null,
            'observaciones' => $record['convenio_observaciones'] ?? null,
        ];

        $puedeComentar = $this->puedeAgregarComentarios(
            $convenio['estatus'] ?? null,
            $machote['confirmado'],
            $estadoMachote
        );
        $puedeConfirmar = !$machote['confirmado'] && (int) ($resumen['pendientes'] ?? 0) === 0;

        return [
            'empresa' => $empresa,
            'machote' => $machote,
            'convenio' => $convenio,
            'documento' => $documento,
            'comentarios' => $comentarios,
            'stats' => [
                'total' => (int) ($resumen['total'] ?? 0),
                'pendientes' => (int) ($resumen['pendientes'] ?? 0),
                'resueltos' => (int) ($resumen['resueltos'] ?? 0),
                'progreso' => (int) ($resumen['progreso'] ?? 0),
                'estado' => $estadoMachote,
            ],
            'permisos' => [
                'puede_comentar' => $puedeComentar,
                'puede_confirmar' => $puedeConfirmar,
            ],
        ];
    }

    /**
     * @param array<string, mixed> $record
     * @return array<string, mixed>
     */
    private function buildDocumentoMeta(array $record): array
    {
        $contenidoHtml = (string) ($record['contenido_html'] ?? '');
        $pdfPath = EmpresaConvenioHelper::normalizePath($record['machote_pdf_path'] ?? null);
        $embed = $pdfPath;

        if ($embed !== null && strpos($embed, '#') === false) {
            $embed .= '#view=FitH';
        }

        return [
            'has_html' => $contenidoHtml !== '',
            'html' => $contenidoHtml,
            'has_pdf' => $pdfPath !== null,
            'pdf_url' => $pdfPath,
            'pdf_embed_url' => $embed,
            'fuente' => $pdfPath !== null ? 'machote_hijo' : null,
        ];
    }

    private function formatFecha(?string $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        try {
            $date = new DateTimeImmutable($value);
            return $date->format('d/m/Y H:i');
        } catch (\Throwable) {
            return $value;
        }
    }

    private function puedeAgregarComentarios(?string $estatusConvenio, bool $machoteConfirmado, ?string $estatusMachote = null): bool
    {
        if ($machoteConfirmado) {
            return false;
        }

        return $this->isEstatusEnRevision($estatusConvenio) || $this->isEstatusEnRevision($estatusMachote);
    }

    private function isEstatusEnRevision(?string $estatus): bool
    {
        $estatusNormalizado = $this->normalizeEstatus($estatus);

        if ($estatusNormalizado === null) {
            return false;
        }

        if (str_contains($estatusNormalizado, 'revisi')) {
            return true;
        }

        return str_contains($estatusNormalizado, 'reabiert');
    }

    private function normalizeEstatus(?string $estatus): ?string
    {
        if ($estatus === null) {
            return null;
        }

        $estatus = trim((string) $estatus);

        if ($estatus === '') {
            return null;
        }

        return function_exists('mb_strtolower')
            ? mb_strtolower($estatus, 'UTF-8')
            : strtolower($estatus);
    }
}
