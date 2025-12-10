<?php

declare(strict_types=1);

if (!function_exists('emptyStateRender')) {
    /**
     * Renderiza un template de empty state ubicado en
     * recidencia/view/components/emptystates/{nombre}.php
     *
     * @param string $name Nombre del template sin la extensiA3n .php
     * @param array<string, mixed> $vars Variables a inyectar en el template
     */
    function emptyStateRender(string $name, array $vars = []): void
    {
        $baseDir = dirname(__DIR__, 2) . '/view/components/emptystates/';
        $file = $baseDir . $name . '.php';

        if (!is_file($file)) {
            return;
        }

        if ($vars !== []) {
            extract($vars, EXTR_SKIP);
        }

        include $file;
    }
}
