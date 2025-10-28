<?php

declare(strict_types=1);

require_once __DIR__ . '/documentofunction_view.php';

if (!function_exists('documentoReviewDefaults')) {
    /**
     * @return array{
     *     documentId: ?int,
     *     document: ?array<string, mixed>,
     *     fileMeta: array{
     *         exists: bool,
     *         absolutePath: ?string,
     *         publicUrl: ?string,
     *         filename: ?string,
     *         sizeBytes: ?int,
     *         sizeLabel: ?string,
     *         extension: ?string,
     *         canPreview: bool
     *     },
     *     formData: array{estatus: string, observacion: string},
     *     statusOptions: array<string, string>,
     *     errors: array<int, string>,
     *     successMessage: ?string,
     *     controllerError: ?string,
     *     notFoundMessage: ?string
     * }
     */
    function documentoReviewDefaults(): array
    {
        return [
            'documentId' => null,
            'document' => null,
            'fileMeta' => [
                'exists' => false,
                'absolutePath' => null,
                'publicUrl' => null,
                'filename' => null,
                'sizeBytes' => null,
                'sizeLabel' => null,
                'extension' => null,
                'canPreview' => false,
            ],
            'formData' => documentoReviewFormDefaults(),
            'statusOptions' => documentoStatusOptions(),
            'errors' => [],
            'successMessage' => null,
            'controllerError' => null,
            'notFoundMessage' => null,
        ];
    }
}

if (!function_exists('documentoReviewFormDefaults')) {
    /**
     * @return array{estatus: string, observacion: string}
     */
    function documentoReviewFormDefaults(): array
    {
        return [
            'estatus' => '',
            'observacion' => '',
        ];
    }
}

if (!function_exists('documentoReviewNormalizeId')) {
    function documentoReviewNormalizeId(mixed $value): ?int
    {
        return documentoNormalizePositiveInt($value);
    }
}

if (!function_exists('documentoReviewIsPostRequest')) {
    function documentoReviewIsPostRequest(): bool
    {
        return (isset($_SERVER['REQUEST_METHOD'])
            ? strtoupper((string) $_SERVER['REQUEST_METHOD'])
            : '') === 'POST';
    }
}

if (!function_exists('documentoReviewSanitizeInput')) {
    /**
     * @param array<string, mixed> $input
     * @return array{estatus: string, observacion: string}
     */
    function documentoReviewSanitizeInput(array $input): array
    {
        $formData = documentoReviewFormDefaults();

        if (isset($input['estatus'])) {
            $formData['estatus'] = trim((string) $input['estatus']);
        }

        if (isset($input['observacion'])) {
            $formData['observacion'] = documentoReviewNormalizeObservation($input['observacion']);
        }

        return $formData;
    }
}

if (!function_exists('documentoReviewNormalizeObservation')) {
    function documentoReviewNormalizeObservation(mixed $value): string
    {
        $text = (string) $value;
        $text = str_replace(["\r\n", "\r"], "\n", $text);

        return trim($text);
    }
}

if (!function_exists('documentoReviewValidateForm')) {
    /**
     * @param array{estatus: string, observacion: string} $formData
     * @param array<string, string> $statusOptions
     * @return array<int, string>
     */
    function documentoReviewValidateForm(array &$formData, array $statusOptions): array
    {
        $errors = [];

        $normalizedStatus = documentoNormalizeStatus($formData['estatus'] ?? null);
        if ($normalizedStatus === null) {
            $errors[] = 'Selecciona un estatus valido.';
        } elseif (!array_key_exists($normalizedStatus, $statusOptions)) {
            $errors[] = 'Selecciona un estatus valido.';
            $normalizedStatus = null;
        } else {
            $formData['estatus'] = $normalizedStatus;
        }

        $observacion = documentoReviewNormalizeObservation($formData['observacion'] ?? '');
        $formData['observacion'] = $observacion;

        $maxLength = 500;
        $length = documentoReviewStringLength($observacion);
        if ($length > $maxLength) {
            $errors[] = 'Las observaciones no pueden exceder los ' . $maxLength . ' caracteres.';
        }

        if ($normalizedStatus === 'rechazado' && $observacion === '') {
            $errors[] = 'Para rechazar el documento debes indicar una observacion.';
        }

        return $errors;
    }
}

if (!function_exists('documentoReviewStringLength')) {
    function documentoReviewStringLength(string $value): int
    {
        if (function_exists('mb_strlen')) {
            return mb_strlen($value, 'UTF-8');
        }

        return strlen($value);
    }
}

if (!function_exists('documentoReviewControllerErrorMessage')) {
    function documentoReviewControllerErrorMessage(\Throwable $exception): string
    {
        $message = trim((string) $exception->getMessage());

        return $message !== ''
            ? $message
            : 'No se pudo procesar la solicitud. Intenta nuevamente mas tarde.';
    }
}

if (!function_exists('documentoReviewNotFoundMessage')) {
    function documentoReviewNotFoundMessage(int $documentId): string
    {
        return 'No se encontro el documento solicitado (#' . $documentId . ').';
    }
}

if (!function_exists('documentoReviewDecorateDocument')) {
    /**
     * @param array<string, mixed> $document
     * @return array<string, mixed>
     */
    function documentoReviewDecorateDocument(array $document): array
    {
        return documentoViewDecorateDocument($document);
    }
}

if (!function_exists('documentoReviewBuildFileMeta')) {
    /**
     * @return array{
     *     exists: bool,
     *     absolutePath: ?string,
     *     publicUrl: ?string,
     *     filename: ?string,
     *     sizeBytes: ?int,
     *     sizeLabel: ?string,
     *     extension: ?string,
     *     canPreview: bool
     * }
     */
    function documentoReviewBuildFileMeta(?string $ruta, string $projectRoot): array
    {
        return documentoViewBuildFileMeta($ruta, $projectRoot);
    }
}

if (!function_exists('documentoReviewSuccessMessage')) {
    function documentoReviewSuccessMessage(string $estatus): string
    {
        $options = documentoStatusOptions();
        $label = $options[$estatus] ?? ucfirst($estatus);

        return 'Se actualizo el documento al estatus "' . $label . '".';
    }
}
