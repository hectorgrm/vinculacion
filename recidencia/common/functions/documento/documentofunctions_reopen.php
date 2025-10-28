<?php

declare(strict_types=1);

require_once __DIR__ . '/documentofunctions_list.php';

if (!function_exists('documentoReopenSanitizeInput')) {
    /**
     * @param array<string, mixed> $input
     * @return array{id: ?int}
     */
    function documentoReopenSanitizeInput(array $input): array
    {
        return [
            'id' => documentoReopenNormalizeId($input['id'] ?? null),
        ];
    }
}

if (!function_exists('documentoReopenNormalizeId')) {
    function documentoReopenNormalizeId(mixed $value): ?int
    {
        return documentoNormalizePositiveInt($value);
    }
}

if (!function_exists('documentoReopenBuildRedirectUrl')) {
    /**
     * @param array<string, int|string|bool|null> $params
     */
    function documentoReopenBuildRedirectUrl(string $basePath, array $params = []): string
    {
        if ($params === []) {
            return $basePath;
        }

        $filtered = [];
        foreach ($params as $key => $value) {
            if ($value === null) {
                continue;
            }

            if (is_bool($value)) {
                $filtered[$key] = $value ? '1' : '0';
                continue;
            }

            if (is_scalar($value)) {
                $filtered[$key] = (string) $value;
            }
        }

        if ($filtered === []) {
            return $basePath;
        }

        $query = http_build_query($filtered);
        if ($query === '') {
            return $basePath;
        }

        return (strpos($basePath, '?') === false ? $basePath . '?' : $basePath . '&') . $query;
    }
}
