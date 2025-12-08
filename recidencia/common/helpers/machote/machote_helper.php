<?php
declare(strict_types=1);

function machote_format_date(?string $fecha): string
{
    if (!$fecha) {
        return '&mdash;';
    }

    $timestamp = strtotime($fecha);

    return $timestamp ? date('d M Y', $timestamp) : '&mdash;';
}

function machote_estado_badge(?string $estado): string
{
    $normalized = machote_normalize_estado($estado);

    switch ($normalized) {
        case 'aprobado':
            return '<span class="badge aprobado">Aprobado</span>';
        case 'en revision':
            return '<span class="badge en_revision">En revisi&oacute;n</span>';
        case 'con observaciones':
            return '<span class="badge warn">Con observaciones</span>';
        case 'archivado':
            return '<span class="badge archivado">Archivado</span>';
        case 'cancelado':
            return '<span class="badge err">Cancelado</span>';
        default:
            return '<span class="badge gris">&mdash;</span>';
    }
}

function machote_normalize_estado(?string $estado): string
{
    $estado = trim((string) ($estado ?? ''));

    if ($estado === '') {
        return '';
    }

    $estado = str_replace(
        ['á', 'é', 'í', 'ó', 'ú', 'ñ', 'Ã¡', 'Ã©', 'Ã­', 'Ã³', 'Ãº', 'Ã±', 'A­', 'Ac', 'A-', 'A3', 'A§'],
        ['a', 'e', 'i', 'o', 'u', 'n', 'a', 'e', 'i', 'o', 'u', 'n', 'a', 'e', 'i', 'o', 'u'],
        $estado
    );

    return function_exists('mb_strtolower')
        ? mb_strtolower($estado, 'UTF-8')
        : strtolower($estado);
}
