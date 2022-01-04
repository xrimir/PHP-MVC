<?php

namespace Bootcamp\App\Task;

use Apsl\Mvc\Database\Connection;
use Apsl\Mvc\Task\Task;
use Apsl\Mvc\Mailer\SmtpMailer;
use Bootcamp\App\Model\Contact;


class SendContactEmailsTask extends Task
{
    const DEFAULT_LIMIT = 10;

    protected Connection $pdo;
    protected SmtpMailer $mailer;
    protected int $limit = self::DEFAULT_LIMIT;

    public function __construct()
    {
        parent::__construct();

        if (isset($this->cliArgs[0])) {
            $this->limit = (int) $this->cliArgs[0];
        }

        $this->pdo = $this->services['database'];
        $this->mailer = $this->services['mailer'];
    }

    public function execute(): int
    {
        $contacts = Contact::fetchManyWithStatus($this->pdo, Contact::STATUS_NOT_SENT, $this->limit);
        foreach ($contacts as $contact) {
            if ($contact instanceof Contact) {
                $this->out("Sending contact e-mail with id #{$contact->getContactId()}.");

                try {
                    $body = $this->getEmailBodyText($contact);
                    $bodyHtml = $this->getEmailBodyHtml($contact);
                    $this->mailer->send(
                        to: $contact->getFromEmail(),
                        subject: 'Potwierdzenie wysłania wiadomości',
                        body: $body,
                        bodyHtml: $bodyHtml
                    );

                    $contact->setStatus(Contact::STATUS_SENT);
                    $contact->save($this->pdo);

                    $this->out("Contact e-mail with id #{$contact->getContactId()} sent.");
                } catch (\Exception $exception) {
                    $this->out("Can't send e-mail to '{$contact->getFromEmail()}' contactId = {$contact->getContactId()}. Exception: {$exception->getMessage()}.");
                }
            }
        }

        return self::RETURN_SUCCESS;
    }

    /**
     * @param Contact $contact
     * @return string
     */
    protected function getEmailBodyText(Contact $contact): string
    {
        return <<<EOTEXT
Dziękujemy za kontakt przez formularz kontaktowy.
Przesłana wiadomość to:
{$contact->getMessage()}
EOTEXT;
    }

    /**
     * @param Contact $contact
     * @return string
     */
    protected function getEmailBodyHtml(Contact $contact): string
    {
        return <<<EOHTML
<p>Dziękujemy za kontakt przez formularz kontaktowy.</p>
<p>Przesłana wiadomość to:
    <pre>{$contact->getMessage()}</pre>
</p>
EOHTML;
    }
}
