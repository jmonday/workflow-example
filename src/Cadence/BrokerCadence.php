<?php

declare(strict_types=1);

namespace App\Cadence;

use App\Cadence\Cadence\AbstractCadence;
use App\Model\Broker;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Workflow\Transition;

class BrokerCadence extends AbstractCadence
{
    public const TRANSITION_TO_ACTIVE = 'TO_' . Broker::STATUS_ACTIVE;

    public const TRANSITION_TO_CONNECTED = 'TO_' . Broker::STATUS_CONNECTED;

    public const TRANSITION_TO_LOST_CONTACT = 'TO_' . Broker::STATUS_LOST_CONTACT;

    public const TRANSITION_TO_NEEDS_DATA = 'TO_' . Broker::STATUS_NEEDS_DATA;

    public const TRANSITION_TO_NEW = 'TO_' . Broker::STATUS_NEW;

    public const TRANSITION_TO_NOT_QUALIFIED = 'TO_' . Broker::STATUS_NOT_QUALIFIED;

    public const TRANSITION_TO_REACHED_OUT = 'TO_' . Broker::STATUS_REACHED_OUT;

    public const TRANSITION_TO_SCHEDULED_APPOINTMENT = 'TO_' . Broker::STATUS_SCHEDULED_APPOINTMENT;

    public const TRANSITION_TO_SCHEDULED_NO_SHOW = 'TO_' . Broker::STATUS_SCHEDULED_NO_SHOW;

    #[Pure] public function getInitialPlace(): string
    {
        return Broker::DEFAULT_STATUS;
    }

    /** @inheritDoc */
    #[Pure] public function getPlaces(): array
    {
        $places = Broker::STATUS_MAP;
        unset($places[Broker::DEFAULT_STATUS]);

        // Ensure the default status is always at the start of the places.
        // When rendering the cadence to DOT/SVG, this ensures the default
        // places is highlighted as the "active" place.
        return [Broker::DEFAULT_STATUS => Broker::DEFAULT_STATUS] + $places;
    }

    /** @inheritDoc */
    #[Pure] public function getTransitions(): array
    {
        return [
            new Transition(
                self::TRANSITION_TO_NEW,
                [Broker::STATUS_NEEDS_DATA],
                Broker::STATUS_NEW,
            ),
            new Transition(
                self::TRANSITION_TO_NEEDS_DATA,
                [Broker::STATUS_NEW],
                Broker::STATUS_NEEDS_DATA,
            ),
            new Transition(
                self::TRANSITION_TO_ACTIVE,
                [Broker::STATUS_NEW],
                Broker::STATUS_ACTIVE,
            ),
            new Transition(
                self::TRANSITION_TO_REACHED_OUT,
                [Broker::STATUS_ACTIVE],
                Broker::STATUS_REACHED_OUT,
            ),
            new Transition(
                self::TRANSITION_TO_CONNECTED,
                [Broker::STATUS_REACHED_OUT],
                Broker::STATUS_CONNECTED,
            ),
            new Transition(
                self::TRANSITION_TO_NOT_QUALIFIED,
                [Broker::STATUS_CONNECTED],
                Broker::STATUS_NOT_QUALIFIED,
            ),
            new Transition(
                self::TRANSITION_TO_LOST_CONTACT,
                [Broker::STATUS_CONNECTED],
                Broker::STATUS_LOST_CONTACT,
            ),
            new Transition(
                self::TRANSITION_TO_SCHEDULED_APPOINTMENT,
                [Broker::STATUS_CONNECTED],
                Broker::STATUS_SCHEDULED_APPOINTMENT,
            ),
            new Transition(
                self::TRANSITION_TO_SCHEDULED_NO_SHOW,
                [Broker::STATUS_SCHEDULED_APPOINTMENT],
                Broker::STATUS_SCHEDULED_NO_SHOW,
            ),
        ];
    }
}
