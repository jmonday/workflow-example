<?php

declare(strict_types=1);

namespace App\Cadence\Cadence;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Workflow\Definition;
use Symfony\Component\Workflow\Transition;

interface CadenceInterface
{
    public function build(): Definition;

    public function config(): void;

    public function getDefinition(): Definition;

    public function getDispatcher(): EventDispatcher;

    public function getInitialPlace(): string;

    public function addPlace(string $place): self;

    /** @return string[] */
    public function getPlaces(): array;

    public function removePlace(string $place): self;

    public function addTransition(Transition $transition): self;

    /** @return \Symfony\Component\Workflow\Transition[] */
    public function getTransitions(): array;

    public function transition(CadenceSubjectInterface $subject, string $name): void;
}
