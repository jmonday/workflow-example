<?php

declare(strict_types=1);

namespace App\Cadence\Cadence;

use App\EventSubscriber\CadenceStatusSubscriber;
use App\Support\Str;
use ReflectionClass;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Workflow\Definition;
use Symfony\Component\Workflow\DefinitionBuilder;
use Symfony\Component\Workflow\Exception\LogicException;
use Symfony\Component\Workflow\Exception\NotEnabledTransitionException;
use Symfony\Component\Workflow\MarkingStore\MethodMarkingStore;
use Symfony\Component\Workflow\Transition;
use Symfony\Component\Workflow\Workflow;

abstract class AbstractCadence extends Workflow implements CadenceInterface
{
    private Definition $definition;

    /** @var string[] */
    private array $places = [];

    /** @var \Symfony\Component\Workflow\Transition[] */
    private array $transitions = [];

    abstract public function getInitialPlace(): string;

    /** @return string[] */
    abstract public function getPlaces(): array;

    /** @return \Symfony\Component\Workflow\Transition[] */
    abstract public function getTransitions(): array;

    public function __construct()
    {
        $this->config();
        $this->definition = $this->build();
        $markingStore = new MethodMarkingStore(singleState: true, property: CadenceSubjectInterface::CURRENT_STATE);
        $name = Str::snake((new ReflectionClass($this))->getShortName());

        parent::__construct($this->definition, $markingStore, $this->getDispatcher(), $name);
    }

    public function build(): Definition
    {
        $definitionBuilder = new DefinitionBuilder();

        $definitionBuilder
            ->setInitialPlaces($this->getInitialPlace())
            ->addPlaces($this->getPlaces())
        ;

        foreach ($this->getTransitions() as $transition) {
            $definitionBuilder->addTransition($transition);
        }

        return $definitionBuilder->build();
    }

    public function config(): void
    {
        foreach ($this->getPlaces() as $place) {
            $this->addPlace($place);
        }

        foreach ($this->getTransitions() as $transition) {
            $this->addTransition($transition);
        }
    }

    public function getDefinition(): Definition
    {
        return $this->definition;
    }

    public function getDispatcher(): EventDispatcher
    {
        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new CadenceStatusSubscriber());

        return $dispatcher;
    }

    public function addPlace(string $place): self
    {
        $this->places[$place] = $place;

        return $this;
    }

    public function removePlace(string $place): self
    {
        if (array_key_exists($place, $this->places)) {
            unset($this->places[$place]);
        }

        return $this;
    }

    public function addTransition(Transition $transition): self
    {
        $this->transitions[$transition->getName()] = $transition;

        return $this;
    }

    public function transition(CadenceSubjectInterface $subject, string $name): void
    {
        try {
            // Check can() prior? $this->can($name);

            $this->apply($subject, $name);
        } catch (NotEnabledTransitionException $exception) {
            // Could log, show errors to the user, etc.
            dump($exception->getTransitionBlockerList());
        } catch (LogicException $exception) {
            dump($exception);
        }
    }
}
