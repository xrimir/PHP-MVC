<?php

namespace Bootcamp\App\Model;

use Apsl\Mvc\Database\Connection;


class Contact
{
    const STATUS_NOT_SENT = 0;
    const STATUS_SENT = 1;

    protected int $contactId;
    protected string $fromEmail;
    protected string $message;
    protected int $status;
    protected \DateTime $createdAt;

    public static function fetchOne(Connection $connection, int $contactId): ?self
    {
        $stmt = $connection->prepare("SELECT * FROM contact WHERE contact_id = :contactId");
        $stmt->bindValue('contactId', $contactId, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!empty($result)) {
            $contact = new Contact();
            $contact->fromArray($result);

            return $contact;
        }

        return null;
    }

    public static function fetchMany(Connection $connection, int $limit, int $offset = 0): array
    {
        $stmt = $connection->prepare("SELECT * FROM contact LIMIT :limit OFFSET :offset");
        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, \PDO::PARAM_INT);
        return self::executeFetchAndPopulateResults($stmt);
    }

    public static function fetchManyWithStatus(Connection $connection, int $status, int $limit): array
    {
        $stmt = $connection->prepare("SELECT * FROM contact  WHERE status = :status LIMIT :limit");
        $stmt->bindValue('status', $status, \PDO::PARAM_INT);
        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        return self::executeFetchAndPopulateResults($stmt);
    }

    /**
     * @param \PDOStatement $stmt
     * @return array
     */
    protected static function executeFetchAndPopulateResults(\PDOStatement $stmt): array
    {
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (!empty($results)) {
            $contacts = [];
            foreach ($results as $result) {
                $contact = new Contact();
                $contact->fromArray($result);

                $contacts[] = $contact;
            }

            return $contacts;
        }

        return [];
    }

    public function save(Connection $connection): void
    {
        if (isset($this->contactId)) {
            $stmt = $connection->prepare($this->getSqlForUpdate());
            $stmt->bindValue('contactId', $this->contactId);
            $stmt->bindValue('status', $this->status, \PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $stmt = $connection->prepare($this->getSqlForInsert());
            $stmt->bindValue('from', $this->fromEmail);
            $stmt->bindValue('message', $this->message);
            $stmt->bindValue('status', self::STATUS_NOT_SENT, \PDO::PARAM_INT);
            $stmt->execute();

            $contact = self::fetchOne($connection, $connection->lastInsertId());
            if ($contact instanceof self) {
                $this->contactId = $contact->getContactId();
                $this->createdAt = $contact->getCreatedAt();
            }
        }
    }

    public function delete(): void
    {
        if (isset($this->contactId)) {
            // TODO: UPDATE
        }
    }

    public function fromArray(array $data)
    {
        if (isset($data['contact_id'])) {
            $this->setContactId($data['contact_id']);
        }
        $this->setFromEmail($data['from_email'] ?? '');
        $this->setMessage($data['message'] ?? '');
        if (isset($data['status'])) {
            $this->setStatus($data['status']);
        }
        if (isset($data['created_at'])) {
            $this->setCreatedAt(new \DateTime($data['created_at']));
        }
    }

    public function getSqlForInsert(): string
    {
        return <<<EOSQL
INSERT INTO
    contact (from_email, message, status)
VALUES
    (:from, :message, :status)
EOSQL;
    }

    public function getSqlForUpdate(): string
    {
        return <<<EOSQL
UPDATE contact
SET
    status = :status
WHERE
    contact_id = :contactId
EOSQL;
    }

    /**
     * @return int
     */
    public function getContactId(): int
    {
        return $this->contactId;
    }

    /**
     * @param int $contactId
     */
    public function setContactId(int $contactId): void
    {
        $this->contactId = $contactId;
    }

    /**
     * @return string
     */
    public function getFromEmail(): string
    {
        return $this->fromEmail;
    }

    /**
     * @param string $fromEmail
     */
    public function setFromEmail(string $fromEmail): void
    {
        $this->fromEmail = $fromEmail;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
