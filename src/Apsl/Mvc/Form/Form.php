<?php

namespace Apsl\Mvc\Form;


abstract class Form
{
    protected array $data;
    protected array $cleanData;
    protected array $errors;
    protected array $fields;
    protected array $validators;

    public function __construct(protected string $name) {
        $this->configure();
    }

    abstract public function configure();

    public function validate(): bool
    {
        $this->errors = [];

        foreach ($this->validators as $name => $validator) {
            if ($validator->validate($this->getValue($name))) {
                $this->cleanData[$name] = $validator->getClean();
            } else {
                $this->errors[$name] = $validator->getErrors();
            }
        }

        return empty($this->errors);
    }

    public function render(): string
    {
        foreach ($this->fields as $name => $field) {
            $fieldsHtml[] = $field->render($this->name, $this->getValue($name));
        }

        return implode('', $fieldsHtml);
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    protected function getValue(string $name)
    {
        return $this->data[$name] ?? null;
    }

    public function getCleanData(): array
    {
        return $this->cleanData;
    }

    public function getCleanValue(string $name)
    {
        return $this->cleanData[$name] ?? null;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
