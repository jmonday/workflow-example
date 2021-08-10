<?php

declare(strict_types=1);

namespace App\Command;

use App\Cadence;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Workflow\Dumper\GraphvizDumper;

class RenderCadenceCommand extends Command
{
    protected static $defaultName = 'app:cadence:render';

    protected function configure(): void
    {
        $this->setDescription('Render cadence graph to SVG.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dumper = new GraphvizDumper();
        $cadences = [
            'broker-cadence' => new Cadence\BrokerCadence(),
            'example-cadence' => new Cadence\ExampleCadence(),
            'simple-cadence' => new Cadence\SimpleCadence(),
        ];

        foreach ($cadences as $key => $cadence) {
            $process = new Process(['dot', '-Tsvg']);
            $process->setInput($dumper->dump($cadence->getDefinition()));
            $process->mustRun();

            file_put_contents(sprintf('./assets/%s.svg', $key), $process->getOutput());
        }

        return 0;
    }
}
