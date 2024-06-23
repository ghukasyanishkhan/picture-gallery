<?php

namespace app\core;

use app\core\middlewares\BaseMiddleware;

class Controller
{
    public string $action='';
    public string $layout = 'main';
    /**
     * @var BaseMiddleware[]
     */
    protected array $middlewares = [];

    public function render($view, $params = [])
    {
        return Application::$app->view->renderView($view, $params);
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function registerMiddleware(BaseMiddleware $baseMiddleware)
    {
        $this->middlewares[] = $baseMiddleware;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

}