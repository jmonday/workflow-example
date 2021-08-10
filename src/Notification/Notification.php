<?php

declare(strict_types=1);

namespace App\Notification;

use App\Cadence\Cadence\CadenceSubjectInterface;

/**
 * Stubs for demonstration purposes only.
 */
abstract class Notification
{
    abstract public function notify(CadenceSubjectInterface $subject): void;
}
