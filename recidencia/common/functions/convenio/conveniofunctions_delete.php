<?php

declare(strict_types=1);

use Residencia\Controller\ConvenioController;

if (!function_exists('convenioSanitizeDeleteRequest')) {
    /**
     * @param array<string, mixed> $input
     * @return array{
     *     id: int,
     *     empresa_id: int|null,
     *     confirmado: bool,
     *     motivo: ?string
     * }
     */
    function convenioSanitizeDeleteRequest(array $input): array
    {
        $idRaw = $input['id'] ?? null;
        $id = 0;

        if (is_scalar($idRaw)) {
            $filtered = preg_replace('/[^0-9]/', '', (string) $idRaw);
            $id = $filtered !== null && $filtered !== '' ? (int) $filtered : 0;
        }

        $empresaIdRaw = $input['empresa_id'] ?? null;
        $empresaId = null;

        if (is_scalar($empresaIdRaw)) {
            $filteredEmpresa = preg_replace('/[^0-9]/', '', (string) $empresaIdRaw);
            if ($filteredEmpresa !== null && $filteredEmpresa !== '') {
                $empresaId = (int) $filteredEmpresa;
            }
        }

        $confirmRaw = $input['confirm'] ?? null;
        $confirmado = false;

        if (is_bool($confirmRaw)) {
            $confirmado = $confirmRaw;
        } elseif (is_scalar($confirmRaw)) {
            $value = strtolower(trim((string) $confirmRaw));
            $confirmado = $value !== '' && $value !== '0' && $value !== 'false' && $value !== 'no';
        }

        $motivoRaw = $input['motivo'] ?? null;
        $motivo = null;

        if (is_string($motivoRaw)) {
            $motivoRaw = trim($motivoRaw);

            if ($motivoRaw !== '') {
                $motivo = $motivoRaw;
            }
        } elseif (is_scalar($motivoRaw)) {
            $motivoRaw = trim((string) $motivoRaw);

            if ($motivoRaw !== '') {
                $motivo = $motivoRaw;
            }
        }

        return [
            'id' => $id,
            'empresa_id' => $empresaId,
            'confirmado' => $confirmado,
            'motivo' => $motivo,
        ];
    }
}

if (!function_exists('convenioHandleDeleteRequest')) {
    /**
     * @param array<string, mixed> $request
     * @return array{
     *     errors: array<int, string>,
     *     successMessage: ?string,
     *     convenioId: int,
     *     sanitized: array{
     *         id: int,
     *         empresa_id: int|null,
     *         confirmado: bool,
     *         motivo: ?string
     *     }
     * }
     */
    function convenioHandleDeleteRequest(ConvenioController $controller, array $request): array
    {
        $sanitized = convenioSanitizeDeleteRequest($request);
        $errors = [];
        $successMessage = null;

        if ($sanitized['id'] <= 0) {
            $errors[] = 'La solicitud de desactivación no es válida.';
        }

        if ($sanitized['confirmado'] === false) {
            $errors[] = 'Debes confirmar la desactivación del convenio.';
        }

        if ($errors === []) {
            try {
                $controller->deactivateConvenio($sanitized['id'], $sanitized['motivo']);
                $successMessage = 'El convenio se desactivó correctamente.';
            } catch (\RuntimeException $runtimeException) {
                $errors[] = $runtimeException->getMessage();
            } catch (\Throwable) {
                $errors[] = 'Ocurrió un error al desactivar el convenio. Intenta nuevamente.';
            }
        }

        return [
            'errors' => $errors,
            'successMessage' => $successMessage,
            'convenioId' => $sanitized['id'],
            'sanitized' => $sanitized,
        ];
    }
}
