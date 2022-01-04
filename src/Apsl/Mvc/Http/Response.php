<?php

namespace Apsl\Mvc\Http;


class Response
{
    const HEADER_CONTENT_LENGTH = 'Content-Length';
    const HEADER_CONTENT_TYPE = 'Content-Type';
    const STATUS_200_OK = 200;
    const STATUS_301_MOVED_PERMANENTLY = 301;
    const STATUS_302_FOUND = 302;
    const STATUS_403_FORBIDDEN = 403;
    const STATUS_404_NOT_FOUND = 404;

    protected array $headers = [];
    protected string $body = '';
    protected int $status = self::STATUS_200_OK;

    public function send(): void
    {
        http_response_code($this->status);

        $length = strlen($this->getBody());
        $this->addHeader(self::HEADER_CONTENT_LENGTH, $length);

        foreach ($this->headers as $header => $value) {
            header("{$header}: {$value}");
        }

        echo $this->body;
    }

    public function addHeader(string $header, string $value, bool $allowOverwrite = true): void
    {
        if (!isset($this->headers[$header]) || $allowOverwrite) {
            $this->headers[$header] = $value;
        }
    }

    public function removeHeader(string $header): void
    {
        unset($this->headers[$header]);
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function fetchTemplate(string $file, array $params = []): string
    {
        foreach ($params as $paramName => $paramValue) {
            $$paramName = $paramValue;
        }

        ob_start();
        include $file;

        return ob_get_clean();
    }
}
