<?php

declare(strict_types=1);

namespace App\Cadence\Action;

use App\Cadence\Cadence\CadenceSubjectInterface;

abstract class AbstractCadenceAction implements CadenceActionInterface
{
    abstract public function execute(CadenceSubjectInterface $subject): void;

    public function __construct(private CadenceSubjectInterface $subject)
    {
        $this->execute($this->subject);
    }
}
