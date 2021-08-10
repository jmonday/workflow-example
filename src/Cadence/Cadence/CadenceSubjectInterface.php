<?php

declare(strict_types=1);

namespace App\Cadence\Cadence;

interface CadenceSubjectInterface
{
    public const CURRENT_STATE = 'currentState';

    public function getCurrentState(): string;

    public function setCurrentState(string $state): self;
}
