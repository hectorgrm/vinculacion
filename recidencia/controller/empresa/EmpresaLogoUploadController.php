<?php

declare(strict_types=1);

namespace Residencia\Controller\Empresa;

require_once __DIR__ . '/../../model/empresa/EmpresaLogoUploadModel.php';

use InvalidArgumentException;
use Residencia\Model\Empresa\EmpresaLogoUploadModel;
use RuntimeException;
use Throwable;

class EmpresaLogoUploadController
{
    private EmpresaLogoUploadModel $model;

    public function __construct(?EmpresaLogoUploadModel $model = null)
    {
        $this->model = $model ?? new EmpresaLogoUploadModel();
    }

    /**
     * @param array{name?: string, type?: string, tmp_name?: string, error?: int, size?: int} $fileInfo
     * @return array{logo_path: string, previous_logo_path: ?string, filename: string}
     */
    public function upload(int $empresaId, array $fileInfo): array
    {
        if ($empresaId <= 0) {
            throw new InvalidArgumentException('El identificador de la empresa no es válido.');
        }

        $empresaInfo = $this->model->findLogoInfo($empresaId);

        if ($empresaInfo === null) {
            throw new RuntimeException('La empresa seleccionada no existe.', 404);
        }

        $normalizedFile = $this->normalizeFileInfo($fileInfo);
        $this->assertUploadIsValid($normalizedFile);

        $extension = strtolower((string) pathinfo($normalizedFile['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $this->allowedExtensions(), true)) {
            throw new RuntimeException('Solo se permiten archivos PNG o JPG para el logotipo.');
        }

        $maxFileSize = $this->maxFileSize();
        if ($normalizedFile['size'] > $maxFileSize) {
            $maxMegabytes = number_format($maxFileSize / 1048576, 1, '.', '');
            throw new RuntimeException('El logotipo supera el tamaño máximo permitido de ' . $maxMegabytes . ' MB.');
        }

        $imageInfo = @getimagesize($normalizedFile['tmp_name']);
        if ($imageInfo === false) {
            throw new RuntimeException('El archivo seleccionado no es una imagen válida.');
        }

        $mimeType = isset($imageInfo['mime']) ? (string) $imageInfo['mime'] : '';
        if ($mimeType !== '' && !in_array($mimeType, $this->allowedMimeTypes(), true)) {
            throw new RuntimeException('El tipo de archivo seleccionado no es válido.');
        }

        $storageDirectory = $this->storageDirectoryForEmpresa($empresaId);
        if (!is_dir($storageDirectory)) {
            $created = mkdir($storageDirectory, 0775, true);
            if ($created === false && !is_dir($storageDirectory)) {
                throw new RuntimeException('No se pudo crear el directorio para almacenar el logotipo.');
            }
        }

        $filename = $this->buildFilename($empresaId, $extension);
        $destinationPath = $storageDirectory . DIRECTORY_SEPARATOR . $filename;

        $this->moveUploadedFile($normalizedFile['tmp_name'], $destinationPath);

        $relativePath = $this->relativePathForFile($empresaId, $filename);

        try {
            $this->model->updateLogoPath($empresaId, $relativePath);
        } catch (Throwable $exception) {
            @unlink($destinationPath);

            throw new RuntimeException('No se pudo guardar el logotipo en la base de datos.', 0, $exception);
        }

        $previousPath = isset($empresaInfo['logo_path']) ? (string) $empresaInfo['logo_path'] : null;

        if (is_string($previousPath) && $previousPath !== '' && $previousPath !== $relativePath) {
            $previousAbsolute = $this->absoluteFromRelative($previousPath);

            if ($previousAbsolute !== null && is_file($previousAbsolute)) {
                @unlink($previousAbsolute);
            }
        }

        return [
            'logo_path' => $relativePath,
            'previous_logo_path' => is_string($previousPath) && $previousPath !== '' ? $previousPath : null,
            'filename' => $filename,
        ];
    }

    /**
     * @param array{name?: string, type?: string, tmp_name?: string, error?: int, size?: int} $fileInfo
     * @return array{name: string, type: string, tmp_name: string, error: int, size: int}
     */
    private function normalizeFileInfo(array $fileInfo): array
    {
        return [
            'name' => isset($fileInfo['name']) ? (string) $fileInfo['name'] : '',
            'type' => isset($fileInfo['type']) ? (string) $fileInfo['type'] : '',
            'tmp_name' => isset($fileInfo['tmp_name']) ? (string) $fileInfo['tmp_name'] : '',
            'error' => isset($fileInfo['error']) ? (int) $fileInfo['error'] : UPLOAD_ERR_NO_FILE,
            'size' => isset($fileInfo['size']) ? (int) $fileInfo['size'] : 0,
        ];
    }

    /**
     * @param array{name: string, type: string, tmp_name: string, error: int, size: int} $fileInfo
     */
    private function assertUploadIsValid(array $fileInfo): void
    {
        $errorCode = (int) $fileInfo['error'];

        if ($errorCode !== UPLOAD_ERR_OK) {
            throw new RuntimeException($this->uploadErrorMessage($errorCode));
        }

        $tmpName = trim($fileInfo['tmp_name']);
        if ($tmpName === '') {
            throw new RuntimeException('No se recibió el archivo a cargar.');
        }

        $originalName = trim($fileInfo['name']);
        if ($originalName === '') {
            throw new RuntimeException('El archivo seleccionado no es válido.');
        }

        if (!is_uploaded_file($tmpName) && !file_exists($tmpName)) {
            throw new RuntimeException('El archivo cargado no es válido.');
        }
    }

    private function uploadErrorMessage(int $errorCode): string
    {
        return match ($errorCode) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'El archivo excede el tamaño máximo permitido.',
            UPLOAD_ERR_PARTIAL => 'La carga del archivo se interrumpió. Inténtalo nuevamente.',
            UPLOAD_ERR_NO_FILE => 'No se recibió el archivo a cargar.',
            UPLOAD_ERR_NO_TMP_DIR => 'No se encontró el directorio temporal para la carga.',
            UPLOAD_ERR_CANT_WRITE => 'No se pudo guardar el archivo en el disco.',
            UPLOAD_ERR_EXTENSION => 'La carga del archivo fue detenida por la configuración del servidor.',
            default => 'No se pudo cargar el archivo seleccionado.',
        };
    }

    /**
     * @return array<int, string>
     */
    private function allowedExtensions(): array
    {
        return ['png', 'jpg', 'jpeg'];
    }

    /**
     * @return array<int, string>
     */
    private function allowedMimeTypes(): array
    {
        return ['image/png', 'image/jpeg', 'image/pjpeg'];
    }

    private function maxFileSize(): int
    {
        return 5 * 1024 * 1024; // 5 MB
    }

    private function buildFilename(int $empresaId, string $extension): string
    {
        try {
            $random = bin2hex(random_bytes(8));
        } catch (Throwable) {
            $random = substr(md5((string) microtime(true) . mt_rand()), 0, 16);
        }

        $normalizedExtension = $extension !== '' ? $extension : 'png';

        return sprintf('logo_%d_%s.%s', $empresaId, $random, $normalizedExtension);
    }

    private function storageDirectoryForEmpresa(int $empresaId): string
    {
        $relative = $this->logoBaseRelativeDirectory() . '/' . $empresaId;
        $absolute = $this->absoluteFromRelative($relative);

        if ($absolute === null) {
            throw new RuntimeException('No se pudo resolver la ruta de almacenamiento para el logotipo.');
        }

        return $absolute;
    }

    private function relativePathForFile(int $empresaId, string $filename): string
    {
        return $this->logoBaseRelativeDirectory() . '/' . $empresaId . '/' . $filename;
    }

    private function logoBaseRelativeDirectory(): string
    {
        return 'uploads/empresalogos';
    }

    private function absoluteFromRelative(string $relativePath): ?string
    {
        $relativePath = trim($relativePath);

        if ($relativePath === '') {
            return null;
        }

        if (preg_match('/^https?:\/\//i', $relativePath) === 1) {
            return null;
        }

        $sanitized = str_replace('\\', '/', $relativePath);
        $sanitized = ltrim($sanitized, '/');

        if ($sanitized === '' || str_contains($sanitized, '..')) {
            return null;
        }

        $baseDir = dirname(__DIR__, 2);

        return $baseDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $sanitized);
    }

    private function moveUploadedFile(string $tmpName, string $destination): void
    {
        if (!is_uploaded_file($tmpName) && !file_exists($tmpName)) {
            throw new RuntimeException('No se recibió un archivo cargado válido.');
        }

        $moved = @move_uploaded_file($tmpName, $destination);

        if ($moved === false) {
            throw new RuntimeException('No se pudo guardar el archivo cargado.');
        }

        @chmod($destination, 0644);
    }
}
