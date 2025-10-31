<?php

declare(strict_types=1);

namespace Residencia\Controller\Documento;

require_once __DIR__ . '/../../model/documento/DocumentoUploadModel.php';
require_once __DIR__ . '/../../common/functions/documento/documentofunctions_list.php';

use Residencia\Model\Documento\DocumentoUploadModel;
use RuntimeException;
use PDOException;

class DocumentoUploadController
{
    private DocumentoUploadModel $model;

    public function __construct(?DocumentoUploadModel $model = null)
    {
        $this->model = $model ?? new DocumentoUploadModel();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getEmpresas(): array
    {
        return $this->model->fetchEmpresas();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getTiposGlobales(): array
    {
        return $this->model->fetchTiposGlobales();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getTiposPersonalizados(int $empresaId): array
    {
        return $this->model->fetchTiposPersonalizados($empresaId);
    }

    public function empresaExists(int $empresaId): bool
    {
        return $this->model->empresaExists($empresaId);
    }

    public function tipoGlobalExists(int $tipoId): bool
    {
        return $this->model->tipoGlobalExists($tipoId);
    }

    public function tipoPersonalizadoBelongsToEmpresa(int $tipoId, int $empresaId): bool
    {
        return $this->model->tipoPersonalizadoBelongsToEmpresa($tipoId, $empresaId);
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $file
     * @return array{id: int, ruta: string, filename: string}
     */
    public function upload(array $data, array $file): array
    {
        $empresaId = (int) ($data['empresa_id'] ?? 0);
        $tipoOrigen = isset($data['tipo_origen']) ? (string) $data['tipo_origen'] : 'global';
        $tipoGlobalId = isset($data['tipo_global_id']) ? $data['tipo_global_id'] : null;
        $tipoPersonalizadoId = isset($data['tipo_personalizado_id']) ? $data['tipo_personalizado_id'] : null;
        $estatus = (string) ($data['estatus'] ?? 'pendiente');
        $observacion = $data['observacion'] ?? null;

        $tipoReferencia = $tipoOrigen === 'personalizado'
            ? (int) ($tipoPersonalizadoId ?? 0)
            : (int) ($tipoGlobalId ?? 0);

        $uploadDirectory = $this->resolveUploadDirectory();
        $this->ensureDirectoryExists($uploadDirectory);

        $extension = strtolower((string) pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION));

        try {
            $uniqueFragment = bin2hex(random_bytes(4));
        } catch (\Exception $exception) {
            throw new RuntimeException('No se pudo preparar el nombre del archivo de carga.', 0, $exception);
        }

        $fileName = sprintf(
            'doc_%d_%d_%s_%s.%s',
            $empresaId,
            $tipoReferencia,
            date('Ymd_His'),
            $uniqueFragment,
            $extension
        );

        $destinationPath = $uploadDirectory . DIRECTORY_SEPARATOR . $fileName;
        $tmpName = (string) ($file['tmp_name'] ?? '');

        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            throw new RuntimeException('El archivo proporcionado no es un upload válido.');
        }

        if (!move_uploaded_file($tmpName, $destinationPath)) {
            throw new RuntimeException('No se pudo guardar el archivo en el servidor.');
        }

        $normalizedPath = 'uploads/documento/' . $fileName;

        try {
            $documentoId = $this->model->insertDocumento([
                'empresa_id' => $empresaId,
                'tipo_global_id' => $tipoOrigen === 'global'
                    ? (documentoNormalizePositiveInt($tipoGlobalId) ?? null)
                    : null,
                'tipo_personalizado_id' => $tipoOrigen === 'personalizado'
                    ? (documentoNormalizePositiveInt($tipoPersonalizadoId) ?? null)
                    : null,
                'ruta' => $normalizedPath,
                'estatus' => $estatus,
                'observacion' => $observacion,
            ]);
        } catch (PDOException $exception) {
            @unlink($destinationPath);
            throw new RuntimeException('No se pudo registrar el documento.', 0, $exception);
        } catch (\Throwable $exception) {
            @unlink($destinationPath);
            throw $exception;
        }

        return [
            'id' => $documentoId,
            'ruta' => $normalizedPath,
            'filename' => $fileName,
        ];
    }

    /**
     * @return array<string, string>
     */
    public function getStatusOptions(): array
    {
        return documentoStatusOptions();
    }

    private function resolveUploadDirectory(): string
    {
        return dirname(__DIR__, 2) . '/uploads/documento';
    }

    private function ensureDirectoryExists(string $directory): void
    {
        if (is_dir($directory)) {
            return;
        }

        if (!mkdir($directory, 0775, true) && !is_dir($directory)) {
            throw new RuntimeException('No se pudo crear el directorio de carga de documentos.');
        }
    }
}

