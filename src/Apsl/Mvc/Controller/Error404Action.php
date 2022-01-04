<?php

namespace Apsl\Mvc\Controller;

use Apsl\Mvc\Http\Response;


class Error404Action extends Action
{
    public function doExecute(): void
    {
        $this->response->setStatus(Response::STATUS_404_NOT_FOUND);
        $this->response->setBody('Page not found.');
    }
}
