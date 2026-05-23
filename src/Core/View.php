<?php declare(strict_types=1);

namespace App\Core;

class View
{
    public function __construct(
        private RendererInterface $renderer
    ) {

    }

    /**
     * @param string $template
     * @param array $data
     * @return void
     */
    public function render(string $template, array $data = []): void
    {
        $this->renderer->render($template, $data);
    }
}
