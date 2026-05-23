<?php declare(strict_types=1);

namespace App\Core;

use Smarty\Smarty;

class SmartyRenderer implements RendererInterface
{
    private Smarty $smarty;

    public function __construct()
    {
        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir(__DIR__ . '/../../templates/');
        $this->smarty->setCompileDir(__DIR__ . '/../../templates_cache/');
    }

    /**
     * @param string $template
     * @param array $params
     * @return void
     */
    public function render(string $template, array $params = []): void
    {
        foreach ($params as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->display($template . '.tpl');
    }
}
