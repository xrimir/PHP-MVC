<?php

namespace Apsl\Mvc\Form\Field;

class EmailInputField extends InputField
{
    public function __construct(protected $name, string $label, ?string $placeholder = null)
    {
        parent::__construct($name, $label, $placeholder, self::TYPE_EMAIL);
    }
}
