<?php declare(strict_types=1);

namespace App\Controllers;

use App\Core\Container;
use App\Core\View;

abstract class BaseController
{
    public function __construct(
        protected Container $container
    ) {

    }

    /**
     * @param string $template
     * @param array $data
     * @return void
     * @throws \Exception
     */
    protected function view(string $template, array $data = []): void
    {
        $view = $this->container->make(View::class);
        $view->render($template, $data);
    }
}