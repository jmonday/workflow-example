<?php

declare(strict_types=1);

namespace App\Model;

use App\Cadence\Action\CadenceActionInterface;
use App\Cadence\Cadence\CadenceSubjectInterface;
use DateTime;

class CadenceAction
{
    private DateTime $executeAt;

    private string $label;

    private string $uid;

    public function __construct(private CadenceSubjectInterface $subject, private CadenceActionInterface $action)
    {
    }

    public function getAction(): CadenceActionInterface
    {
        return $this->action;
    }

    public function setAction(CadenceActionInterface $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getExecuteAt(): DateTime
    {
        return $this->executeAt;
    }

    public function setExecuteAt(DateTime $executeAt): self
    {
        $this->executeAt = $executeAt;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getSubject(): CadenceSubjectInterface
    {
        return $this->subject;
    }

    public function setSubject(CadenceSubjectInterface $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getUid(): string
    {
        return $this->uid;
    }

    public function setUid(string $uid): self
    {
        $this->uid = $uid;

        return $this;
    }
}
