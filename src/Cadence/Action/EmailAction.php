<?php

declare(strict_types=1);

namespace App\Cadence\Action;

use App\Cadence\Cadence\CadenceSubjectInterface;
use App\Notification\Notification;

class EmailAction extends AbstractCadenceAction
{
    public function __construct(private CadenceSubjectInterface $subject, private Notification $notification)
    {
        parent::__construct($this->subject);
    }

    public function execute(CadenceSubjectInterface $subject): void
    {
        $this->notification->notify($subject);
    }
}
