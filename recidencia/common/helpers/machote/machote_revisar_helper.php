<?php
declare(strict_types=1);

namespace Residencia\Common\Helpers\Machote;

/**
 * Calcula métricas básicas de los comentarios del machote.
 *
 * @param array<int, array<string, mixed>> $comentarios
 * @return array<string, int|string>
 */
function resumenComentarios(array $comentarios): array
{
    $total = 0;
    $resueltos = 0;

    foreach ($comentarios as $comentario) {
        if (($comentario['respuesta_a'] ?? null) !== null) {
            // Solo los comentarios raíz cuentan para el avance general.
            continue;
        }

        $total++;
        if (($comentario['estatus'] ?? '') === 'resuelto') {
            $resueltos++;
        }
    }

    $pendientes = $total - $resueltos;
    $progreso = $total > 0 ? (int) round(($resueltos / $total) * 100) : 0;

    if ($total === 0) {
        $estado = 'En revisión';
    } elseif ($resueltos === $total) {
        $estado = 'Aprobado';
    } else {
        $estado = 'Con observaciones';
    }

    return [
        'total'      => $total,
        'resueltos'  => $resueltos,
        'pendientes' => max(0, $pendientes),
        'progreso'   => $progreso,
        'estado'     => $estado,
    ];
}
