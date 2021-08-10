<?php

declare(strict_types=1);

namespace App\Cadence\Action;

use App\Cadence\Cadence\CadenceSubjectInterface;

interface CadenceActionInterface
{
    public function execute(CadenceSubjectInterface $subject): void;
}
