<?php

namespace Bootcamp\App\Form;

use Apsl\Mvc\Form\Field\EmailInputField;
use Apsl\Mvc\Form\Field\InputField;
use Apsl\Mvc\Form\Form;
use Apsl\Mvc\Form\Validator\EmailValidator;
use Apsl\Mvc\Form\Validator\StringValidator;


class ContactForm extends Form
{
    public function __construct()
    {
        parent::__construct('contact');
    }

    public function configure()
    {
        $this->fields['email'] = new EmailInputField(
            name: 'email', label: 'E-mail', placeholder: 'john@example.com'
        );
        $this->validators['email'] = new EmailValidator();

        $this->fields['message'] = new InputField(
            name: 'message', label: 'Message', placeholder: 'Hi...'
        );
        $this->validators['message'] = new StringValidator(maxLength: 10);
    }
}
