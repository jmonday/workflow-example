<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Cadence\Action\EmailAction;
use App\Cadence\BrokerCadence;
use App\Model\Broker;
use App\Model\CadenceAction;
use App\Notification\NewBrokerNotification;
use DateInterval;
use DateTime;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\EnteredEvent;
use Symfony\Component\Workflow\Event\GuardEvent;

use function assert;

class CadenceStatusSubscriber implements EventSubscriberInterface
{
    /**
     * Automatically called prior to the broker entering "NEW" status.
     */
    public function guardNew(GuardEvent $event): void
    {
        $broker = $event->getSubject();

        assert($broker instanceof Broker);

        // A custom validator class could be used here instead...
        if (!is_string($broker->getFirstName()) || '' === $broker->getFirstName()) {
            $event->setBlocked(true, 'Broker is missing first name.');
        }

        if (!is_string($broker->getLastName()) || '' === $broker->getLastName()) {
            $event->setBlocked(true, 'Broker is missing last name.');
        }

        if (is_string($broker->getEmail()) && '' !== $broker->getEmail()) {
            return;
        }

        $event->setBlocked(true, 'Broker is missing email address.');
    }

    /**
     * Demonstrates how we can automatically perform various action(s) when the subject is moved into a specific state.
     */
    public function onEnteredNew(EnteredEvent $event): void
    {
        // In this case, since we're listening to a very specific event, we know the event's subject is a broker. We
        // could instead listen to a more broad event to perform actions for many types of cadence subjects, for
        // example, logging, error reporting, etc.
        $broker = $event->getSubject();

        if (! $broker instanceof Broker) {
            return;
        }

        // Demonstrate how we could perform some action. In this case, we're creating a generic "cadence action" model
        // which could perform some action at a later date (ie: a queue of models to execute via scheduled cron task).
        // For example, send a notification, execute a database query, call an API endpoint, etc.
        $action = new EmailAction($broker, new NewBrokerNotification());

        (new CadenceAction($broker, $action))
            ->setLabel('New Broker Action')
            ->setUid('ABC123')
            ->setExecuteAt((new DateTime())->add(new DateInterval('P7D')))
        ;
    }

    /**
     * @see https://symfony.com/doc/current/workflow.html#using-events
     * @return array<string, array<string>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'workflow.broker_cadence.entered.' . Broker::STATUS_NEW => ['onEnteredNew'],
            'workflow.broker_cadence.guard.' . BrokerCadence::TRANSITION_TO_NEW => ['guardNew'],
        ];
    }
}
