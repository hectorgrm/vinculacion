<?php

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/common/auth.php';
require_once __DIR__ . '/../../controller/empresa/EmpresaLogoUploadController.php';

use Residencia\Controller\Empresa\EmpresaLogoUploadController;

if (!function_exists('empresaLogoUploadJsonResponse')) {
    /**
     * @param array<string, mixed> $payload
     */
    function empresaLogoUploadJsonResponse(int $statusCode, array $payload): void
    {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }

        http_response_code($statusCode);

        try {
            echo json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } catch (\JsonException) {
            echo json_encode(
                [
                    'success' => false,
                    'message' => 'No se pudo codificar la respuesta de la carga del logotipo.',
                ],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
        }

        exit;
    }
}

if (!function_exists('empresaLogoUploadNormalizeEmpresaId')) {
    function empresaLogoUploadNormalizeEmpresaId(mixed $value): ?int
    {
        if ($value === null) {
            return null;
        }

        if (is_int($value)) {
            return $value > 0 ? $value : null;
        }

        if (is_string($value) && preg_match('/^\d+$/', $value) === 1) {
            $intValue = (int) $value;

            return $intValue > 0 ? $intValue : null;
        }

        if (is_numeric($value)) {
            $intValue = (int) $value;

            return $intValue > 0 ? $intValue : null;
        }

        return null;
    }
}

if (!function_exists('empresaLogoUploadNormalizeFile')) {
    /**
     * @param mixed $file
     * @return array{name: string, type: string, tmp_name: string, error: int, size: int}|null
     */
    function empresaLogoUploadNormalizeFile(mixed $file): ?array
    {
        if (!is_array($file)) {
            return null;
        }

        return [
            'name' => isset($file['name']) ? (string) $file['name'] : '',
            'type' => isset($file['type']) ? (string) $file['type'] : '',
            'tmp_name' => isset($file['tmp_name']) ? (string) $file['tmp_name'] : '',
            'error' => isset($file['error']) ? (int) $file['error'] : UPLOAD_ERR_NO_FILE,
            'size' => isset($file['size']) ? (int) $file['size'] : 0,
        ];
    }
}

if (!function_exists('empresaLogoUploadErrorMessage')) {
    function empresaLogoUploadErrorMessage(\Throwable $exception): string
    {
        $message = trim((string) $exception->getMessage());

        return $message !== '' ? $message : 'No se pudo actualizar el logotipo de la empresa.';
    }
}

$allowedMethod = 'POST';
$method = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));

if ($method !== $allowedMethod) {
    header('Allow: ' . $allowedMethod);
    empresaLogoUploadJsonResponse(405, [
        'success' => false,
        'message' => 'Método no permitido. Usa una solicitud POST para cargar el logotipo.',
    ]);
}

$empresaId = empresaLogoUploadNormalizeEmpresaId($_POST['empresa_id'] ?? null);

if ($empresaId === null) {
    empresaLogoUploadJsonResponse(400, [
        'success' => false,
        'message' => 'No se recibió un identificador de empresa válido.',
    ]);
}

$fileInfo = empresaLogoUploadNormalizeFile($_FILES['logo'] ?? null);

if ($fileInfo === null) {
    empresaLogoUploadJsonResponse(400, [
        'success' => false,
        'message' => 'No se recibió el archivo del logotipo.',
    ]);
}

try {
    $controller = new EmpresaLogoUploadController();
} catch (\Throwable $exception) {
    empresaLogoUploadJsonResponse(500, [
        'success' => false,
        'message' => empresaLogoUploadErrorMessage($exception),
    ]);
}

try {
    $result = $controller->upload($empresaId, $fileInfo);
} catch (\Throwable $exception) {
    $statusCode = 500;

    if ($exception instanceof \InvalidArgumentException) {
        $statusCode = 400;
    } else {
        $exceptionCode = (int) $exception->getCode();

        if ($exceptionCode >= 400 && $exceptionCode < 600) {
            $statusCode = $exceptionCode;
        } elseif ($exception instanceof \RuntimeException) {
            $statusCode = 422;
        }
    }

    empresaLogoUploadJsonResponse($statusCode, [
        'success' => false,
        'message' => empresaLogoUploadErrorMessage($exception),
    ]);
}

empresaLogoUploadJsonResponse(200, [
    'success' => true,
    'message' => 'Logotipo actualizado correctamente.',
    'logoPath' => $result['logo_path'] ?? null,
    'previousLogoPath' => $result['previous_logo_path'] ?? null,
    'filename' => $result['filename'] ?? null,
]);
