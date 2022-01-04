<?php

namespace Apsl\Mvc\Controller;

use Apsl\Mvc\Http\Request;
use Apsl\Mvc\Http\Response;


class Controller
{
    const PATH_ERROR_404 = 'ERROR_404';
    const PATH_HOME = '/';

    public function __construct(protected Request $request) {}

    public function handleRequest(): Response
    {
        $action = $this->createAction();

        return $action->execute();
    }

    protected function createAction(): Action
    {
        $uri = $this->request->getUri(withoutQueryString: true);
        $routing = require 'config/routing.php';
        $services = require 'config/services.php';

        foreach ($routing as $path => $class) {
            if ($path === self::PATH_HOME) {
                if ($uri === self::PATH_HOME) {
                    if (class_exists($class)) {
                        return new $class($this->request, $services);
                    }
                } else {
                    continue;
                }
            }

            if (str_starts_with($uri, $path)) {
                if (class_exists($class)) {
                    return new $class($this->request, $services);
                }
            }
        }

        if (isset($routing[self::PATH_ERROR_404])) {
            $class = $routing[self::PATH_ERROR_404];
            if (class_exists($class)) {
                return new $class($this->request, $services);
            }
        }

        return new Error404Action($this->request, $services);
    }
}
