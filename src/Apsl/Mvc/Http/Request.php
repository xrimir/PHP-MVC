<?php

namespace Apsl\Mvc\Http;


class Request
{
    const HEADER_USER_AGENT = 'User-Agent';
    const METHOD_GET = 'get';
    const METHOD_POST = 'post';
    const METHOD_PUT = 'put';
    const METHOD_DELETE = 'delete';

    public function getHeader(string $header): string
    {
        $headerKey = 'HTTP_' . strtoupper(str_replace('-', '_', $header));

        return $_SERVER[$headerKey] ?? '';
    }

    public function getGetValue(string $name, ?string $default = null): string|array|null
    {
        return $_GET[$name] ?? $default;
    }

    public function getPostValue(string $name, ?string $default = null): string|array|null
    {
        return $_POST[$name] ?? $default;
    }

    public function getCookieValue(string $name, ?string $default = null): string|array|null
    {
        return $_COOKIE[$name] ?? $default;
    }

    public function getValue(string $name, ?string $default = null): string|array|null
    {
        return $_REQUEST[$name] ?? $default;
    }

    public function hasValue(string $name): bool
    {
        return isset($_REQUEST[$name]);
    }

    public function getUri(bool $withoutQueryString = false): string
    {
        $uri = $_SERVER['REQUEST_URI'];

        if ($withoutQueryString) {
            $uriParts = explode('?', $uri);
            $uri = $uriParts[0];
        }

        return $uri;
    }

    public function isMethod(string $method): bool
    {
        return (strtolower($_SERVER['REQUEST_METHOD']) == strtolower($method));
    }

    public function isMethodPost(): bool
    {
        return $this->isMethod(self::METHOD_POST);
    }
}
