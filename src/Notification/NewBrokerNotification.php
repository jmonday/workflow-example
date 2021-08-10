<?php

declare(strict_types=1);

namespace App\Notification;

use App\Cadence\Cadence\CadenceSubjectInterface;

/**
 * Stubs for demonstration purposes only.
 */
class NewBrokerNotification extends Notification
{
    /** @param \App\Model\Broker $subject */
    public function notify(CadenceSubjectInterface $subject): void
    {
        // Send a notification to select individuals...
    }
}
