<?php

use Apsl\Mvc\Controller\Controller;
use Bootcamp\App\Action\ContactAction;
use Bootcamp\App\Action\HomeAction;


return [
    '/contact' => ContactAction::class,
    Controller::PATH_HOME => HomeAction::class
];
