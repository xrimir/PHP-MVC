<?php

namespace Apsl\Mvc\Mailer;

abstract class Mailer
{
    public function __construct(protected array $config) {}

    abstract public function send(string $to, string $subject, string $body, ?string $bodyHtml = null);
}
