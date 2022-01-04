<?php

namespace Apsl\Mvc\Form\Field;

class InputField
{
    const TYPE_TEXT = 'text';
    const TYPE_PASSWORD = 'password';
    const TYPE_EMAIL = 'email';

    public function __construct(
        protected $name,
        protected string $label,
        protected ?string $placeholder = null,
        protected string $type = self::TYPE_TEXT
    ) {}

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }

    public function render(string $prefix, $value): string // contact
    {
        $formFieldId = "{$prefix}_{$this->name}";
        $formFieldName = "{$prefix}[{$this->name}]";

        return <<<EOHTML
<label for="{$formFieldId}">
    {$this->label}
</label>
<input
    id="{$formFieldId}"
    type="{$this->type}"
    name="{$formFieldName}"
    value="{$value}"
    placeholder="{$this->placeholder}"
>
EOHTML;
    }
}
