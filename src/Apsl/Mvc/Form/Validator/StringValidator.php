<?php

namespace Apsl\Mvc\Form\Validator;


class StringValidator
{
    protected array $errors = [];
    protected $clean = null;

    public function __construct(protected int $maxLength, protected int $minLength = 0) {}

    /**
     * @return array array of errors or empty array on success
     */
    public function validate($value): bool
    {
        $this->errors = [];
        $this->clean = null;

        $clean = trim($value);
        $length = mb_strlen($clean);
        if ($length == 0) {
            $this->errors[] = "Pole wymagane.";
        } elseif ($length < $this->minLength) {
            $this->errors[] = "Minimalna ilość znaków to '{$this->minLength}'.";
        } elseif ($length > $this->maxLength) {
            $this->errors[] = "Dopuszczalna ilość znaków to '{$this->maxLength}'.";
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
