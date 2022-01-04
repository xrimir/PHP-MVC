<?php

namespace Bootcamp\App\Action;

use Apsl\Mvc\Controller\Action;
use Apsl\Mvc\Http\Response;
use Bootcamp\App\Form\ContactForm;
use Bootcamp\App\Model\Contact;


class ContactAction extends Action
{
    public function doExecute(): void
    {
        $templateParams = [];

        $form = new ContactForm();
        if ($this->request->isMethodPost() && $this->request->hasValue('contact')) {
            $form->setData($this->request->getValue('contact'));
            if ($form->validate()) {
                try {
                    $contact = new Contact();
                    $contact->setFromEmail($form->getCleanValue('email'));
                    $contact->setFromEmail($form->getCleanValue('message'));
                    $contact->save($this->services['database']);
                } catch (\Exception $exception) {
                }

                $this->session->set('success', 'Dziękujemy za wysłanie wiadomości.');

                $this->response->setStatus(Response::STATUS_302_FOUND);
                $this->response->addHeader('Location', '/contact');
                return;
            } else {
                $errors = $form->getErrors();
            }

            $templateParams['errors'] = $errors;
        }

        $templateParams['form'] = $form;
        $templateParams['metaTitle'] = 'Formularz kontaktowy blogaska';

        $this->response->setBody($this->applyTemplate(
            'templates/contact.html.php', $templateParams
        ));
    }
}
