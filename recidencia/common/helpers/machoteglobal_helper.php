<?php

declare(strict_types=1);

/**
 * Devuelve la clase CSS correspondiente al estado del machote.
 */
function machote_global_estado_badge(string $estado): string
{
    return match ($estado) {
        'vigente' => 'badge vigente',
        'archivado' => 'badge archivado',
        default => 'badge borrador',
    };
}

/**
 * Traduce el estado almacenado en BD a una etiqueta legible.
 */
function machote_global_estado_label(string $estado): string
{
    return match ($estado) {
        'vigente' => 'Vigente',
        'archivado' => 'Archivado',
        default => 'Borrador',
    };
}

/**
 * Formatea un valor de fecha/hora para mostrarlo en la tabla.
 */
function machote_global_format_datetime(?string $value): string
{
    if ($value === null || trim($value) === '') {
        return '—';
    }

    $timestamp = strtotime($value);
    if ($timestamp === false) {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    return date('Y-m-d H:i', $timestamp);
}
