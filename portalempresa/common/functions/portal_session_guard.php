<?php

declare(strict_types=1);

require_once __DIR__ . '/../../model/PortalEmpresaLoginModel.php';

use PortalEmpresa\Model\PortalEmpresaLoginModel;

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
        $statusNormalized = $status;

        if ($statusNormalized !== '') {
            $statusNormalized = function_exists('mb_strtolower')
                ? mb_strtolower($statusNormalized, 'UTF-8')
                : strtolower($statusNormalized);
        }

        if ($statusNormalized !== 'activa') {
            unset($_SESSION['portal_empresa']);
            portalEmpresaRedirectToLogin($loginPath, 'inactive');
        }

        $session['empresa_nombre'] = (string) ($record['empresa_nombre'] ?? $session['empresa_nombre'] ?? '');
        $session['empresa_numero_control'] = (string) ($record['empresa_numero_control'] ?? $session['empresa_numero_control'] ?? '');
        $session['empresa_estatus'] = $status;
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
