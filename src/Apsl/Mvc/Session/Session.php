<?php

namespace Apsl\Mvc\Session;


class Session
{
    const DEFAULT_CONFIG = [
        'autostart' => false,
        'name' => 'session',
        'lifetime' => 0
    ];
    protected array $config = self::DEFAULT_CONFIG;

    public function __construct()
    {
        if (file_exists('config/session.php')) {
            $this->config = require_once 'config/session.php';
        }

        if ($this->config['autostart'] === true) {
            $this->start();
        }
    }

    public function start(): void
    {
        if ($this->isActive() === false) {
            session_name($this->config['name'] ?? self::DEFAULT_CONFIG['name']);

            $lifetime = (int) $this->config['lifetime'];
            session_set_cookie_params([
                'lifetime' => $lifetime
            ]);
            ini_set('session.gc_maxlifetime', ($lifetime === 0 ? (60 * 60 * 24) : $lifetime));

            session_start();
        }
    }

    public function isActive(): bool
    {
        return (session_status() === PHP_SESSION_ACTIVE);
    }

    public function set(string $name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function get(string $name, $default = null)
    {
        return ($_SESSION[$name] ?? $default);
    }

    public function getFlash(string $name, $default = null)
    {
        $value = $this->get($name, $default);
        $this->unset($name);

        return $value;
    }

    public function has(string $name): bool
    {
        return isset($_SESSION[$name]);
    }

    public function unset(string $name)
    {
        unset($_SESSION[$name]);
    }

    public function __get(string $name)
    {
        return $this->get($name);
    }

    public function __set(string $name, $value): void
    {
        $this->set($name, $value);
    }

    public function __isset(string $name): bool
    {
        return $this->has($name);
    }

    public function __call(string $name, array $arguments)
    {
        if (str_starts_with($name, 'set')) {
            $attribute = lcfirst(substr($name, 3));
            $this->set($attribute, array_shift($arguments));
        } elseif (str_starts_with($name, 'get')) {
            $attribute = lcfirst(substr($name, 3));
            $this->get($attribute, array_shift($arguments));
        }
    }
}
