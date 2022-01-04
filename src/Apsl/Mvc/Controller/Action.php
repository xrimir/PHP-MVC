<?php

namespace Apsl\Mvc\Controller;

use Apsl\Mvc\Http\Request;
use Apsl\Mvc\Http\Response;
use Apsl\Mvc\Session\Session;


abstract class Action
{
    const DEFAULT_LAYOUT_FILE = 'templates/layout.html.php';

    protected Request $request;
    protected Response $response;
    protected Session $session;
    protected string $layout = self::DEFAULT_LAYOUT_FILE;
    protected array $services;

    public function __construct(Request $request, array $services = [])
    {
        $this->request = $request;
        $this->response = new Response();
        $this->session = new Session();
        $this->services = $services;
    }

    public function execute(): Response
    {
        $this->doExecute();

        return $this->response;
    }

    protected function applyTemplate(string $template, array $params, bool $withLayout = true)
    {
        $params['session'] = $this->session;

        $output = $this->response->fetchTemplate($template, $params);

        if ($withLayout) {
            $layoutParams = $params;
            $layoutParams['body'] = $output;

            $output = $this->response->fetchTemplate($this->layout, $layoutParams);
        }

        return $output;
    }

    abstract public function doExecute(): void;
}
