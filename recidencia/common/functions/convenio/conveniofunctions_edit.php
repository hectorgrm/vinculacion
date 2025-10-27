<?php

declare(strict_types=1);

use Residencia\Controller\Convenio\ConvenioEditController;

if (!function_exists('convenioHandleEditRequest')) {
    /**
     * @param array<string, mixed> $request
     * @param array<string, mixed> $files
     * @param array<string, mixed> $currentConvenio
     * @return array{
     *     formData: array<string, string>,
     *     errors: array<int, string>,
     *     successMessage: ?string,
     *     convenio: array<string, mixed>|null
     * }
     */
    function convenioHandleEditRequest(
        ConvenioEditController $controller,
        int $convenioId,
        array $request,
        array $files,
        array $currentConvenio,
        string $uploadDirAbsolute,
        string $uploadDirRelative = 'uploads/convenios'
    ): array {
        $formData = convenioSanitizeInput($request);
        $formData['empresa_id'] = isset($currentConvenio['empresa_id'])
            ? (string) $currentConvenio['empresa_id']
            : '';

        $errors = convenioValidateData($formData);
        $successMessage = null;
        $updatedConvenio = $currentConvenio;

        $postedIdRaw = $request['id'] ?? null;
        $postedId = 0;
        if (is_scalar($postedIdRaw)) {
            $filtered = preg_replace('/[^0-9]/', '', (string) $postedIdRaw);
            $postedId = $filtered !== null && $filtered !== '' ? (int) $filtered : 0;
        }

        if ($postedId !== $convenioId) {
            $errors[] = 'La solicitud enviada no es válida.';
        }

        $previousRelativePath = isset($currentConvenio['borrador_path']) && is_string($currentConvenio['borrador_path'])
            ? trim($currentConvenio['borrador_path'])
            : '';

        $uploadRelativePath = null;
        $uploadAbsolutePath = null;

        if ($errors === [] && array_key_exists('borrador_path', $files)) {
            $fileData = $files['borrador_path'];

            if (is_array($fileData)) {
                $uploadResult = convenioProcessFileUpload($fileData, $uploadDirAbsolute, $uploadDirRelative);

                if ($uploadResult['error'] !== null) {
                    $errors[] = $uploadResult['error'];
                } else {
                    $uploadRelativePath = $uploadResult['path'];

                    if ($uploadRelativePath !== null) {
                        $uploadAbsolutePath = rtrim($uploadDirAbsolute, DIRECTORY_SEPARATOR)
                            . DIRECTORY_SEPARATOR
                            . basename($uploadRelativePath);
                    }
                }
            } else {
                $errors[] = 'El archivo del convenio no se recibió correctamente.';
            }
        }

        if ($errors === []) {
            $payload = convenioPrepareUpdatePayload($formData, $currentConvenio, $uploadRelativePath);

            try {
                $controller->updateConvenio($convenioId, $payload);

                $refreshedConvenio = $controller->getConvenioById($convenioId);

                if ($refreshedConvenio !== null) {
                    $updatedConvenio = $refreshedConvenio;
                    $formData = convenioHydrateFormDataFromRecord($refreshedConvenio);
                    $successMessage = 'Los cambios del convenio se guardaron correctamente.';

                    if ($uploadRelativePath !== null && $previousRelativePath !== '' && $previousRelativePath !== $uploadRelativePath) {
                        $previousAbsolutePath = rtrim($uploadDirAbsolute, DIRECTORY_SEPARATOR)
                            . DIRECTORY_SEPARATOR
                            . basename($previousRelativePath);

                        if (is_file($previousAbsolutePath)) {
                            @unlink($previousAbsolutePath);
                        }
                    }
                } else {
                    $errors[] = 'No se pudo obtener la información actualizada del convenio.';
                }
            } catch (\RuntimeException $runtimeException) {
                if ($uploadAbsolutePath !== null && is_file($uploadAbsolutePath)) {
                    @unlink($uploadAbsolutePath);
                }

                $previous = $runtimeException->getPrevious();

                if ($previous instanceof \PDOException) {
                    $errorInfo = $previous->errorInfo;

                    if (is_array($errorInfo) && isset($errorInfo[1]) && (int) $errorInfo[1] === 1062) {
                        $detail = isset($errorInfo[2]) && is_string($errorInfo[2]) ? $errorInfo[2] : '';

                        if ($detail !== '' && stripos($detail, 'folio') !== false) {
                            $errors[] = 'Ya existe un convenio registrado con el folio proporcionado.';
                        } else {
                            $errors[] = 'Los datos proporcionados ya están registrados en otro convenio.';
                        }
                    } else {
                        $errors[] = $runtimeException->getMessage();
                    }
                } else {
                    $errors[] = $runtimeException->getMessage();
                }
            } catch (\Throwable $throwable) {
                if ($uploadAbsolutePath !== null && is_file($uploadAbsolutePath)) {
                    @unlink($uploadAbsolutePath);
                }

                $errors[] = 'Ocurrió un error al actualizar el convenio. Intenta nuevamente.';
            }
        } elseif ($uploadAbsolutePath !== null && is_file($uploadAbsolutePath)) {
            @unlink($uploadAbsolutePath);
        }

        return [
            'formData' => $formData,
            'errors' => $errors,
            'successMessage' => $successMessage,
            'convenio' => $updatedConvenio,
        ];
    }
}

if (!function_exists('convenioPrepareUpdatePayload')) {
    /**
     * @param array<string, string> $data
     * @param array<string, mixed> $currentConvenio
     * @return array<string, mixed>
     */
    function convenioPrepareUpdatePayload(array $data, array $currentConvenio, ?string $borradorPath = null): array
    {
        $existingBorrador = isset($currentConvenio['borrador_path']) && $currentConvenio['borrador_path'] !== null
            ? trim((string) $currentConvenio['borrador_path'])
            : '';

        $existingFirmado = isset($currentConvenio['firmado_path']) && $currentConvenio['firmado_path'] !== null
            ? trim((string) $currentConvenio['firmado_path'])
            : '';

        return [
            'empresa_id' => isset($currentConvenio['empresa_id'])
                ? (int) $currentConvenio['empresa_id']
                : (int) $data['empresa_id'],
            'folio' => $data['folio'] !== '' ? $data['folio'] : null,
            'estatus' => convenioNormalizeStatus($data['estatus']),
            'machote_version' => $data['machote_version'] !== '' ? $data['machote_version'] : null,
            'version_actual' => $data['version_actual'] !== '' ? $data['version_actual'] : null,
            'fecha_inicio' => $data['fecha_inicio'] !== '' ? $data['fecha_inicio'] : null,
            'fecha_fin' => $data['fecha_fin'] !== '' ? $data['fecha_fin'] : null,
            'observaciones' => $data['observaciones'] !== '' ? $data['observaciones'] : null,
            'borrador_path' => $borradorPath !== null
                ? $borradorPath
                : ($existingBorrador !== '' ? $existingBorrador : null),
            'firmado_path' => $existingFirmado !== '' ? $existingFirmado : null,
        ];
    }
}
