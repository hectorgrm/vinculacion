<?php

declare(strict_types=1);

namespace Residencia\Controller\Convenio;

require_once __DIR__ . '/../../model/convenio/ConvenioEditModel.php';
require_once __DIR__ . '/../../common/functions/conveniofunction.php';
require_once __DIR__ . '/../../common/functions/convenio/conveniofunctions_edit.php';

use Residencia\Model\Convenio\ConvenioEditModel;
use PDOException;
use RuntimeException;

class ConvenioEditController
{
    private ConvenioEditModel $model;

    public function __construct(?ConvenioEditModel $model = null)
    {
        $this->model = $model ?? new ConvenioEditModel();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getConvenioById(int $id): ?array
    {
        try {
            return $this->model->getConvenioById($id);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo obtener la informacion del convenio solicitado.', 0, $exception);
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    public function updateConvenio(int $id, array $data): bool
    {
        try {
            return $this->model->updateConvenio($id, $data);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo actualizar el convenio.', 0, $exception);
        }
    }

    public function folioExists(string $folio, ?int $excludeConvenioId = null): bool
    {
        try {
            return $this->model->folioExists($folio, $excludeConvenioId);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo validar el folio del convenio.', 0, $exception);
        }
    }

    /**
     * @param array<string, mixed> $query
     * @param array<string, mixed> $post
     * @param array<string, mixed> $files
     * @param array<string, mixed> $server
     * @return array{
     *     estatusOptions: array<int, string>,
     *     controllerError: ?string,
     *     controllerAvailable: bool,
     *     errors: array<int, string>,
     *     successMessage: ?string,
     *     convenio: array<string, mixed>|null,
     *     convenioId: int,
     *     formData: array<string, string>,
     *     empresaLabel: string,
     *     empresaNumeroControl: string,
     *     empresaLink: string,
     *     convenioListLink: string,
     *     machoteLink: string,
     *     cancelLink: string,
     *     borradorUrl: ?string,
     *     borradorFileName: ?string,
     *     folioLabel: string,
     *     formDisabled: bool,
     *     empresaEstatus: string,
     *     empresaIsCompletada: bool
     * }
     */
    public function handle(array $query, array $post, array $files, array $server): array
    {
        $estatusOptions = convenioStatusOptions();
        $convenioId = $this->resolveConvenioId($query, $post);
        $errors = [];
        $successMessage = null;
        $convenio = null;
        $formData = convenioFormDefaults();
        $controllerErrorMessage = null;
        $controllerAvailable = true;
        $method = isset($server['REQUEST_METHOD']) ? strtoupper((string) $server['REQUEST_METHOD']) : 'GET';
        $empresaEstatus = '';
        $empresaIsCompletada = false;
        $empresaIsInactiva = false;

        if ($convenioId <= 0) {
            $errors[] = 'El identificador del convenio no es valido.';
        } else {
            try {
                $convenio = $this->getConvenioById($convenioId);

                if ($convenio === null) {
                    $errors[] = 'El convenio solicitado no existe o fue eliminado.';
                } else {
                    $formData = convenioHydrateFormDataFromRecord($convenio);
                }
            } catch (RuntimeException $runtimeException) {
                $controllerAvailable = false;
                $controllerErrorMessage = $runtimeException->getMessage();
                $errors[] = $controllerErrorMessage;
            }
        }

        $convenioArchivado = false;

        if ($convenio !== null && isset($convenio['estatus'])) {
            $convenioArchivado = strcasecmp(trim((string) $convenio['estatus']), 'Archivado') === 0;
        }

        if ($convenio !== null && isset($convenio['estatus_general']) && !$convenioArchivado) {
            $convenioArchivado = strcasecmp(trim((string) $convenio['estatus_general']), 'Archivado') === 0;
        }

        if ($convenio !== null && isset($convenio['empresa_estatus'])) {
            $empresaEstatus = trim((string) $convenio['empresa_estatus']);
            $empresaIsCompletada = strcasecmp($empresaEstatus, 'Completada') === 0;
            $empresaIsInactiva = strcasecmp($empresaEstatus, 'Inactiva') === 0;
        }

        if ($method === 'POST') {
            if (!$controllerAvailable) {
                $errors[] = $controllerErrorMessage
                    ?? 'No se pudo procesar la solicitud. Intenta nuevamente mas tarde.';
            } elseif ($convenio === null) {
                $errors[] = 'El convenio solicitado no existe o fue eliminado.';
            } elseif ($empresaIsCompletada) {
                $errors[] = 'La empresa está en estatus Completada; este convenio está en modo de solo lectura.';
            } else {
                $handleResult = convenioHandleEditRequest(
                    $this,
                    isset($convenio['id']) ? (int) $convenio['id'] : $convenioId,
                    $post,
                    $files,
                    $convenio,
                    convenioUploadsAbsoluteDir(),
                    convenioUploadsRelativeDir()
                );

                $formData = $handleResult['formData'];
                $successMessage = $handleResult['successMessage'];
                $postErrors = $handleResult['errors'];

                if ($postErrors !== []) {
                    $errors = array_merge($errors, $postErrors);
                }

                if (is_array($handleResult['convenio'])) {
                    $convenio = $handleResult['convenio'];
                    if (isset($convenio['id']) && ctype_digit((string) $convenio['id'])) {
                        $convenioId = (int) $convenio['id'];
                    }
                }
            }
        }

        if ($errors !== []) {
            $errors = array_values(array_unique($errors));
        }

        if ($convenio !== null && isset($convenio['empresa_estatus'])) {
            $empresaEstatus = trim((string) $convenio['empresa_estatus']);
            $empresaIsCompletada = strcasecmp($empresaEstatus, 'Completada') === 0;
            $empresaIsInactiva = strcasecmp($empresaEstatus, 'Inactiva') === 0;
        }

        $empresaData = $this->resolveEmpresaData($convenio);
        $borradorData = $this->resolveBorradorData($convenio);
        $folioLabel = $convenio !== null
            ? convenioValueOrDefault($convenio['folio'] ?? null, 'Sin folio asignado')
            : 'Sin folio asignado';
        $links = $this->buildLinks($empresaData['id'], $convenioId);
        $formDisabled = !$controllerAvailable || $empresaIsCompletada || $empresaIsInactiva || $convenioArchivado;

        return [
            'estatusOptions' => $estatusOptions,
            'controllerError' => $controllerErrorMessage,
            'controllerAvailable' => $controllerAvailable,
            'errors' => $errors,
            'successMessage' => $successMessage,
            'convenio' => $convenio,
            'convenioId' => $convenioId,
            'formData' => $formData,
            'empresaLabel' => $empresaData['label'],
            'empresaNumeroControl' => $empresaData['numeroControl'],
            'empresaLink' => $links['empresa'],
            'convenioListLink' => $links['convenioList'],
            'machoteLink' => $links['machote'],
            'cancelLink' => $links['cancel'],
            'borradorUrl' => $borradorData['url'],
            'borradorFileName' => $borradorData['fileName'],
            'folioLabel' => $folioLabel,
            'formDisabled' => $formDisabled,
            'empresaEstatus' => $empresaEstatus,
            'empresaIsCompletada' => $empresaIsCompletada,
            'empresaIsInactiva' => $empresaIsInactiva,
            'convenioArchivado' => $convenioArchivado,
        ];
    }

    /**
     * @param array<string, mixed> $query
     * @param array<string, mixed> $post
     */
    private function resolveConvenioId(array $query, array $post): int
    {
        $convenioId = $this->filterNumericId($query['id'] ?? null);

        if ($convenioId <= 0) {
            $postedId = $this->filterNumericId($post['id'] ?? null);
            if ($postedId > 0) {
                $convenioId = $postedId;
            }
        }

        return $convenioId;
    }

    private function filterNumericId(mixed $value): int
    {
        if (!is_scalar($value)) {
            return 0;
        }

        $filtered = preg_replace('/[^0-9]/', '', (string) $value);

        return $filtered !== null && $filtered !== '' ? (int) $filtered : 0;
    }

    /**
     * @param array<string, mixed>|null $convenio
     * @return array{id: int, label: string, numeroControl: string}
     */
    private function resolveEmpresaData(?array $convenio): array
    {
        $empresaNombre = '';
        $empresaNumeroControl = '';
        $empresaId = 0;

        if ($convenio !== null) {
            if (isset($convenio['empresa_nombre'])) {
                $empresaNombre = trim((string) $convenio['empresa_nombre']);
            }

            if (isset($convenio['empresa_numero_control'])) {
                $empresaNumeroControl = trim((string) $convenio['empresa_numero_control']);
            }

            if (isset($convenio['empresa_id'])) {
                $empresaId = (int) $convenio['empresa_id'];
            }
        }

        $empresaLabel = $empresaNombre !== ''
            ? $empresaNombre
            : ($empresaId > 0 ? 'Empresa #' . $empresaId : 'Empresa no disponible');

        if ($empresaId > 0) {
            $empresaLabel .= $empresaNumeroControl !== ''
                ? sprintf(' (ID %d, NC %s)', $empresaId, $empresaNumeroControl)
                : sprintf(' (ID %d)', $empresaId);
        }

        return [
            'id' => $empresaId,
            'label' => $empresaLabel,
            'numeroControl' => $empresaNumeroControl,
        ];
    }

    /**
     * @param array<string, mixed>|null $convenio
     * @return array{url: ?string, fileName: ?string}
     */
    private function resolveBorradorData(?array $convenio): array
    {
        $borradorRelativePath = '';

        if ($convenio !== null && isset($convenio['borrador_path']) && $convenio['borrador_path'] !== null) {
            $borradorRelativePath = trim((string) $convenio['borrador_path']);
        }

        if ($borradorRelativePath === '') {
            return ['url' => null, 'fileName' => null];
        }

        $normalizedPath = str_replace('\\', '/', ltrim($borradorRelativePath, "\\/"));
        $borradorUrl = '../../' . $normalizedPath;
        $borradorFileName = basename($normalizedPath);

        return ['url' => $borradorUrl, 'fileName' => $borradorFileName !== '' ? $borradorFileName : null];
    }

    /**
     * @return array{empresa: string, convenioList: string, machote: string, cancel: string}
     */
    private function buildLinks(int $empresaId, int $convenioId): array
    {
        $empresaLink = $empresaId > 0
            ? '../empresa/empresa_view.php?id=' . urlencode((string) $empresaId)
            : '#';

        $convenioListLink = $empresaId > 0
            ? 'convenio_list.php?empresa=' . urlencode((string) $empresaId)
            : 'convenio_list.php';

        $machoteLink = ($empresaId > 0 && $convenioId > 0)
            ? '../machote/revisar.php?id_empresa=' . urlencode((string) $empresaId)
                . '&convenio=' . urlencode((string) $convenioId)
            : '#';

        $cancelLink = $convenioId > 0
            ? 'convenio_view.php?id=' . urlencode((string) $convenioId)
            : 'convenio_list.php';

        return [
            'empresa' => $empresaLink,
            'convenioList' => $convenioListLink,
            'machote' => $machoteLink,
            'cancel' => $cancelLink,
        ];
    }
}
