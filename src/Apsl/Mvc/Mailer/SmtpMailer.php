<?php

namespace Apsl\Mvc\Mailer;

class SmtpMailer
{
    protected \Swift_Mailer $mailer;
    protected string $from;

    public function __construct(array $config)
    {
        $transport = (new \Swift_SmtpTransport($config['host'], $config['port'], $config['encryption']));
        $transport->setUsername($config['username']);
        $transport->setPassword($config['password']);

        $this->mailer = new \Swift_Mailer($transport);
        $this->from = $config['from'];
    }

    public function send(string $to, string $subject, string $body, ?string $bodyHtml = null): void
    {
        $message = (new \Swift_Message($subject))
            ->setFrom($this->from)
            ->setTo($to)
            ->setBody($body)
            ->addPart($bodyHtml, 'text/html');

        $this->mailer->send($message);
    }
}
