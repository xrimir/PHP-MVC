<?php

use Apsl\Mvc\Http\Request;
use Apsl\Mvc\Controller\Controller;


class App
{
    public function run(): void
    {
        $request = new Request();

        $controller = new Controller($request);
        $response = $controller->handleRequest();

        $response->send();
    }
}
