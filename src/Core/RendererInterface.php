<?php declare(strict_types=1);

namespace App\Core;

interface RendererInterface
{
    public function render(string $template, array $params = []): void;
}