<?php

namespace Apsl\Mvc\Form\Validator;


class EmailValidator
{
    protected array $errors = [];
    protected $clean = null;

    /**
     * @return array array of errors or empty array on success
     */
    public function validate($value): bool
    {
        $this->errors = [];
        $this->clean = null;

        $clean = trim($value);
        if ($clean == null) {
            $this->errors[] = 'Pole wymagane.';
        } elseif (filter_var($clean, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[] = 'Niepoprawny adres e-mail.';
        }

        if (empty($this->errors)) {
            $this->clean = $clean;
            return true;
        }

        return false;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getClean()
    {
        return $this->clean;
    }
}
