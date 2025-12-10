<?php
declare(strict_types=1);

function renderMachoteConEmpresa(string $html, array $empresa): string {
    $map = [
        '{{EMPRESA_NOMBRE}}'       => $empresa['nombre'] ?? '',
        '{{REPRESENTANTE_NOMBRE}}' => $empresa['representante'] ?? '',
        '{{REPRESENTANTE_CARGO}}'  => $empresa['cargo_representante'] ?? '',
        '{{EMPRESA_DIRECCION}}'    => $empresa['direccion'] ?? '',
        '{{EMPRESA_MUNICIPIO}}'    => $empresa['municipio'] ?? '',
        '{{EMPRESA_ESTADO}}'       => $empresa['estado'] ?? '',
        '{{EMPRESA_CP}}'           => $empresa['cp'] ?? '',
    ];

    return str_replace(array_keys($map), array_values($map), $html);
}
