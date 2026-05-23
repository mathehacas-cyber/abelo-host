<?php declare(strict_types=1);

namespace App\Core;

class View
{

    public function render(string $view, array $data = []): void
    {
        echo $view;
    }
}
