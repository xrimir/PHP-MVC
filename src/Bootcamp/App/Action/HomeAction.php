<?php

namespace Bootcamp\App\Action;

use Apsl\Mvc\Controller\Action;


class HomeAction extends Action
{
    public function doExecute(): void
    {
        $this->response->setBody($this->applyTemplate(
            'templates/home.html.php', [
                'metaTitle' => 'Mój blogasek',
            ]
        ));
    }
}
