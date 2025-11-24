<?php

declare(strict_types=1);

require_once __DIR__ . '/conveniofunctions_auditoria.php';

use Residencia\Controller\Convenio\ConvenioAddController;

if (!function_exists('convenioHandleAddRequest')) {
    /**
     * @param array<string, mixed> $request
     * @param array<string, mixed> $files
     * @return array{
     *     formData: array<string, string>,
     *     errors: array<int, string>,
     *     successMessage: ?string,
     *     createdConvenioId: ?int,
     *     createdEmpresaId: ?int
     * }
     */
    function convenioHandleAddRequest(
        ConvenioAddController $controller,
        array $request,
        array $files,
        string $uploadDirAbsolute,
        string $uploadDirRelative = 'uploads/convenios'
    ): array {
        $formData = convenioSanitizeInput($request);
        $errors = convenioValidateData($formData);
        $successMessage = null;
        $createdConvenioId = null;
        $createdEmpresaId = null;

        $uploadRelativePath = null;
        $uploadAbsolutePath = null;

        if ($errors === []) {
            $empresaId = (int) $formData['empresa_id'];

            try {
                if ($controller->empresaTieneConvenioActivo($empresaId)) {
                    $errors[] = 'La empresa seleccionada ya tiene un convenio activo. Finaliza o desactivalo antes de crear uno nuevo.';
                }
            } catch (\Throwable) {
                $errors[] = 'No se pudo validar si la empresa ya tiene un convenio activo. Intenta nuevamente.';
            }
        }

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
            $payload = convenioPrepareForPersistence($formData, $uploadRelativePath);

            try {
                $convenioId = $controller->createConvenio($payload);
                $createdConvenioId = $convenioId;
                $createdEmpresaId = isset($payload['empresa_id'])
                    ? (int) $payload['empresa_id']
                    : null;
                $successMessage = 'El convenio se registró correctamente con el número #' . $convenioId . '.';
                $formData = convenioFormDefaults();

                $context = convenioCurrentAuditContext();
                convenioRegisterAuditEvent('crear', $convenioId, $context);

                $estatusActual = isset($payload['estatus'])
                    ? convenioNormalizeStatus((string) $payload['estatus'])
                    : 'En revisión';

                if ($estatusActual !== 'En revisión') {
                    $accionEstatus = convenioAuditoriaActionForStatusChange('En revisión', $estatusActual);
                    convenioRegisterAuditEvent($accionEstatus, $convenioId, $context);
                }
            } catch (\PDOException $pdoException) {
                if ($uploadAbsolutePath !== null && is_file($uploadAbsolutePath)) {
                    @unlink($uploadAbsolutePath);
                }

                $errorInfo = $pdoException->errorInfo;

                if (is_array($errorInfo) && isset($errorInfo[1]) && (int) $errorInfo[1] === 1062) {
                    $detail = isset($errorInfo[2]) && is_string($errorInfo[2]) ? $errorInfo[2] : '';

                    if ($detail !== '' && stripos($detail, 'folio') !== false) {
                        $errors[] = 'Ya existe un convenio registrado con el folio proporcionado.';
                    } else {
                        $errors[] = 'Los datos proporcionados ya están registrados en otro convenio.';
                    }
                } else {
                    $errors[] = 'Ocurrió un error al registrar el convenio. Intenta nuevamente.';
                }
            } catch (\Throwable) {
                if ($uploadAbsolutePath !== null && is_file($uploadAbsolutePath)) {
                    @unlink($uploadAbsolutePath);
                }

                $errors[] = 'Ocurrió un error al registrar el convenio. Intenta nuevamente.';
            }
        } elseif ($uploadAbsolutePath !== null && is_file($uploadAbsolutePath)) {
            @unlink($uploadAbsolutePath);
        }

        return [
            'formData' => $formData,
            'errors' => $errors,
            'successMessage' => $successMessage,
            'createdConvenioId' => $createdConvenioId,
            'createdEmpresaId' => $createdEmpresaId,
        ];
    }
}

if (!function_exists('convenioPrepareForPersistence')) {
    /**
     * @param array<string, string> $data
     * @return array<string, mixed>
     */
    function convenioPrepareForPersistence(array $data, ?string $borradorPath = null): array
    {
        return [
            'empresa_id' => (int) $data['empresa_id'],
            'folio' => $data['folio'] !== '' ? $data['folio'] : null,
            'estatus' => convenioNormalizeStatus($data['estatus']),
            'tipo_convenio' => $data['tipo_convenio'] !== '' ? $data['tipo_convenio'] : null,
            'responsable_academico' => $data['responsable_academico'] !== '' ? $data['responsable_academico'] : null,
            'fecha_inicio' => $data['fecha_inicio'] !== '' ? $data['fecha_inicio'] : null,
            'fecha_fin' => $data['fecha_fin'] !== '' ? $data['fecha_fin'] : null,
            'observaciones' => $data['observaciones'] !== '' ? $data['observaciones'] : null,
            'borrador_path' => $borradorPath,
            'firmado_path' => null,
        ];
    }
}
