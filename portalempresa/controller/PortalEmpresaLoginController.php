<?php
declare(strict_types=1);

namespace PortalEmpresa\Controller;

use DateTimeImmutable;
use Exception;
use PortalEmpresa\Model\PortalEmpresaLoginModel;

require_once __DIR__ . '/../model/PortalEmpresaLoginModel.php';

class PortalEmpresaLoginController
{
    private PortalEmpresaLoginModel $model;

    public function __construct(?PortalEmpresaLoginModel $model = null)
    {
        $this->model = $model ?? new PortalEmpresaLoginModel();
    }

    /**
     * @return array{success: bool, session?: array<string, mixed>, reason?: string}
     */
    public function authenticate(string $token, string $nip): array
    {
        $token = trim($token);
        $nip = trim($nip);

        if ($token === '' || $nip === '') {
            return [
                'success' => false,
                'reason' => 'missing_fields',
            ];
        }

        $record = $this->model->findByToken($token);

        if ($record === null) {
            return [
                'success' => false,
                'reason' => 'invalid_credentials',
            ];
        }

        $storedNip = isset($record['nip']) ? trim((string) $record['nip']) : '';

        if ($storedNip === '' || !hash_equals($storedNip, $nip)) {
            return [
                'success' => false,
                'reason' => 'invalid_credentials',
            ];
        }

        if ((int) ($record['activo'] ?? 0) !== 1) {
            return [
                'success' => false,
                'reason' => 'access_inactive',
            ];
        }

        $empresaStatus = trim((string) ($record['empresa_estatus'] ?? ''));
        $statusNormalized = $empresaStatus;

        if ($statusNormalized !== '') {
            $statusNormalized = function_exists('mb_strtolower')
                ? mb_strtolower($statusNormalized, 'UTF-8')
                : strtolower($statusNormalized);
        }

        $statusAllowed = ['activa', 'en revisión', 'en revision'];

        if (!in_array($statusNormalized, $statusAllowed, true)) {
            return [
                'success' => false,
                'reason' => 'empresa_inactiva',
            ];
        }

        $expirationRaw = $record['expiracion'] ?? null;

        if ($expirationRaw !== null) {
            try {
                $expiration = new DateTimeImmutable((string) $expirationRaw);
                if ($expiration <= new DateTimeImmutable('now')) {
                    return [
                        'success' => false,
                        'reason' => 'access_expired',
                    ];
                }
            } catch (Exception $exception) {
                return [
                    'success' => false,
                    'reason' => 'access_expired',
                ];
            }
        }

        $sessionData = [
            'acceso_id' => (int) $record['portal_id'],
            'empresa_id' => (int) $record['empresa_id'],
            'token' => (string) $record['token'],
            'empresa_nombre' => (string) ($record['empresa_nombre'] ?? ''),
            'empresa_numero_control' => (string) ($record['empresa_numero_control'] ?? ''),
            'empresa_estatus' => $empresaStatus,
            'autenticado_en' => (new DateTimeImmutable('now'))->format('Y-m-d H:i:s'),
        ];

        return [
            'success' => true,
            'session' => $sessionData,
        ];
    }
}
