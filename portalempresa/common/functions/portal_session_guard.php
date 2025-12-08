<?php

declare(strict_types=1);

require_once __DIR__ . '/../../model/PortalEmpresaLoginModel.php';

use PortalEmpresa\Model\PortalEmpresaLoginModel;

if (!function_exists('portalEmpresaNormalizeStatus')) {
    function portalEmpresaNormalizeStatus(?string $status): string
    {
        $status = trim((string) $status);

        if ($status === '') {
            return '';
        }

        $normalized = function_exists('mb_strtolower')
            ? mb_strtolower($status, 'UTF-8')
            : strtolower($status);

        if (function_exists('iconv')) {
            $transliterated = @iconv('UTF-8', 'ASCII//TRANSLIT', $normalized);
            if ($transliterated !== false) {
                $normalized = $transliterated;
            }
        }

        $normalized = preg_replace('/[^a-z ]/', '', $normalized) ?? $normalized;

        return match ($normalized) {
            'activa' => 'activa',
            'en revision' => 'en revision',
            'completada' => 'completada',
            'inactiva' => 'inactiva',
            'suspendida' => 'suspendida',
            default => $normalized,
        };
    }
}

if (!function_exists('portalEmpresaIsReadOnlyStatus')) {
    function portalEmpresaIsReadOnlyStatus(string $normalizedStatus): bool
    {
        return in_array($normalizedStatus, ['completada', 'inactiva'], true);
    }
}

if (!function_exists('portalEmpresaReadOnlyMessage')) {
    /**
     * @param array<string, mixed> $session
     */
    function portalEmpresaReadOnlyMessage(array $session): ?string
    {
        $status = portalEmpresaNormalizeStatus($session['empresa_estatus_normalizado'] ?? ($session['empresa_estatus'] ?? ''));

        return match ($status) {
            'inactiva' => 'Empresa en estatus Inactiva: el portal esta en modo solo lectura.',
            'completada' => 'Empresa en estatus Completada: el portal esta en modo solo lectura.',
            default => null,
        };
    }
}

if (!function_exists('portalEmpresaIsReadOnly')) {
    /**
     * @param array<string, mixed> $session
     */
    function portalEmpresaIsReadOnly(array $session): bool
    {
        if (!empty($session['empresa_readonly'])) {
            return true;
        }

        $status = portalEmpresaNormalizeStatus($session['empresa_estatus'] ?? '');

        return portalEmpresaIsReadOnlyStatus($status);
    }
}

if (!function_exists('portalEmpresaRequireSession')) {
    /**
     * @return array<string, mixed>
     */
    function portalEmpresaRequireSession(string $loginPath = '../view/login.php'): array
    {
        if (!isset($_SESSION['portal_empresa']) || !is_array($_SESSION['portal_empresa'])) {
            portalEmpresaRedirectToLogin($loginPath, 'session');
        }

        $session = $_SESSION['portal_empresa'];
        $empresaId = isset($session['empresa_id']) ? (int) $session['empresa_id'] : 0;
        $token = isset($session['token']) ? (string) $session['token'] : '';

        if ($empresaId <= 0 || $token === '') {
            unset($_SESSION['portal_empresa']);
            portalEmpresaRedirectToLogin($loginPath, 'session');
        }

        static $model = null;

        if ($model === null) {
            $model = new PortalEmpresaLoginModel();
        }

        $record = $model->findByToken($token);

        if ($record === null || (int) ($record['empresa_id'] ?? 0) !== $empresaId) {
            unset($_SESSION['portal_empresa']);
            portalEmpresaRedirectToLogin($loginPath, 'session');
        }

        if ((int) ($record['activo'] ?? 0) !== 1) {
            unset($_SESSION['portal_empresa']);
            portalEmpresaRedirectToLogin($loginPath, 'inactive');
        }

        $status = trim((string) ($record['empresa_estatus'] ?? ''));
        $statusNormalized = portalEmpresaNormalizeStatus($status);
        $statusAllowed = ['activa', 'en revision', 'completada', 'inactiva'];

        if ($statusNormalized === '' || !in_array($statusNormalized, $statusAllowed, true)) {
            unset($_SESSION['portal_empresa']);
            portalEmpresaRedirectToLogin($loginPath, 'inactive');
        }

        $session['empresa_nombre'] = (string) ($record['empresa_nombre'] ?? $session['empresa_nombre'] ?? '');
        $session['empresa_numero_control'] = (string) ($record['empresa_numero_control'] ?? $session['empresa_numero_control'] ?? '');
        $session['empresa_estatus'] = $status;
        $session['empresa_estatus_normalizado'] = $statusNormalized;
        $session['empresa_readonly'] = portalEmpresaIsReadOnlyStatus($statusNormalized);
        $session['empresa_readonly_message'] = $session['empresa_readonly']
            ? portalEmpresaReadOnlyMessage(['empresa_estatus' => $status, 'empresa_estatus_normalizado' => $statusNormalized])
            : null;
        $session['empresa_logo_path'] = isset($record['empresa_logo_path'])
            ? (string) $record['empresa_logo_path']
            : (string) ($session['empresa_logo_path'] ?? '');

        $_SESSION['portal_empresa'] = $session;

        return $session;
    }
}

if (!function_exists('portalEmpresaRedirectToLogin')) {
    function portalEmpresaRedirectToLogin(string $loginPath, string $errorCode): void
    {
        $separator = strpos($loginPath, '?') !== false ? '&' : '?';
        header('Location: ' . $loginPath . $separator . 'error=' . $errorCode);
        exit;
    }
}
