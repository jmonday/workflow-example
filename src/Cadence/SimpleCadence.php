<?php

declare(strict_types=1);

namespace App\Cadence;

use App\Cadence\Cadence\AbstractCadence;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Workflow\Transition;

class SimpleCadence extends AbstractCadence
{
    #[Pure] public function getInitialPlace(): string
    {
        return 'A';
    }

    /** @inheritDoc */
    #[Pure] public function getPlaces(): array
    {
        return ['A', 'B', 'C'];
    }

    /** @inheritDoc */
    #[Pure] public function getTransitions(): array
    {
        return [
            new Transition('to_b', 'A', 'B'),
            new Transition('to_c', 'B', 'C'),
        ];
    }
}
