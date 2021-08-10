<?php

declare(strict_types=1);

namespace App\Model;

use App\Cadence\Cadence\CadenceSubjectInterface;
use JetBrains\PhpStorm\Pure;

class Broker implements CadenceSubjectInterface
{
    public const DEFAULT_STATUS = self::STATUS_NEEDS_DATA;

    public const STATUS_ACTIVE = 'ACTIVE';

    public const STATUS_CONNECTED = 'CONNECTED';

    public const STATUS_IN_CONTACT = 'IN_CONTACT';

    public const STATUS_LOST_CONTACT = 'LOST_CONTACT';

    public const STATUS_NEEDS_DATA = 'NEEDS_DATA';

    public const STATUS_NEW = 'NEW';

    public const STATUS_NOT_QUALIFIED = 'NOT_QUALIFIED';

    public const STATUS_REACHED_OUT = 'REACHED_OUT';

    public const STATUS_SCHEDULED_APPOINTMENT = 'SCHEDULED_APPOINTMENT';

    public const STATUS_SCHEDULED_NO_SHOW = 'STATUS_SCHEDULED_NO_SHOW';

    public const STATUS_MAP = [
        self::STATUS_ACTIVE => self::STATUS_ACTIVE,
        self::STATUS_CONNECTED => self::STATUS_CONNECTED,
        self::STATUS_IN_CONTACT => self::STATUS_IN_CONTACT,
        self::STATUS_LOST_CONTACT => self::STATUS_LOST_CONTACT,
        self::STATUS_NEEDS_DATA => self::STATUS_NEEDS_DATA,
        self::STATUS_NEW => self::STATUS_NEW,
        self::STATUS_NOT_QUALIFIED => self::STATUS_NOT_QUALIFIED,
        self::STATUS_REACHED_OUT => self::STATUS_REACHED_OUT,
        self::STATUS_SCHEDULED_APPOINTMENT => self::STATUS_SCHEDULED_APPOINTMENT,
        self::STATUS_SCHEDULED_NO_SHOW => self::STATUS_SCHEDULED_NO_SHOW,
    ];

    private ?string $email = null;

    private ?string $firstName = null;

    private ?string $lastName = null;

    public function __construct(private string $status = self::DEFAULT_STATUS)
    {
        $this->setStatus($this->status);
    }

    #[Pure] public function getCurrentState(): string
    {
        return $this->getStatus();
    }

    public function setCurrentState(string $state): self
    {
        $this->setStatus($state);

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): Broker
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = array_key_exists($status, self::STATUS_MAP)
            ? $status
            : self::DEFAULT_STATUS;

        return $this;
    }
}
