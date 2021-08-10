<?php

declare(strict_types=1);

namespace App\Cadence;

use App\Cadence\Cadence\AbstractCadence;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Workflow\Transition;

class ExampleCadence extends AbstractCadence
{
    public const PLACE_A = 'Place_A';
    public const PLACE_B = 'Place_B';
    public const PLACE_BA = 'Place_BA';
    public const PLACE_BB = 'Place_BB';
    public const PLACE_END = 'Place_End';
    public const PLACE_INIT = 'Init';

    #[Pure] public function getInitialPlace(): string
    {
        return self::PLACE_INIT;
    }

    /** @inheritDoc */
    #[Pure] public function getPlaces(): array
    {
        return [
            self::PLACE_INIT,
            self::PLACE_A,
            self::PLACE_B,
            self::PLACE_BA,
            self::PLACE_BB,
            self::PLACE_END,
        ];
    }

    /** @inheritDoc */
    #[Pure] public function getTransitions(): array
    {
        return [
            new Transition('to_a', self::PLACE_INIT, self::PLACE_A),
            new Transition('to_b', [
                self::PLACE_A,
                self::PLACE_B,
                self::PLACE_BA,
                self::PLACE_BB,
            ], self::PLACE_B),
            new Transition('to_ba', self::PLACE_B, self::PLACE_BA),
            new Transition('to_bb', self::PLACE_B, self::PLACE_BB),
            new Transition('to_end', [
                self::PLACE_A,
                self::PLACE_BA,
                self::PLACE_BB,
            ], self::PLACE_END),
        ];
    }
}
