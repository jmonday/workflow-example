<?php

declare(strict_types=1);

namespace App\Command;

use App\Cadence\BrokerCadence;
use App\Model\Broker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class BrokerCadenceCommand extends Command
{
    protected static $defaultName = 'app:cadence:broker';

    private Broker $broker;

    /** @var \App\Model\Broker[] */
    private array $brokers = [];

    protected function configure(): void
    {
        $this->broker = new Broker();
        $this->setDescription('Demonstrate how a broker model would move throughout an example cadence.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $broker = new Broker();
        $cadence = new BrokerCadence();

        $helper = $this->getHelper('question');
        $this->writeHeading($output, 'Broker Cadence Interactive Demonstration');
        $output->writeln("\nFirst, we need to create a broker that could be moved through a cadence.");
        $output->writeln(' - Creating test broker...');
        $output->writeln(" - Empty broker model created. See below:\n");

        dump($broker);

        $output->writeln("\nAs we can see the broker is just a simple PHP object. It could be anything. The important");
        $output->writeln("aspects to notice currently is we're missing first and last name as well as their email ");
        $output->writeln(
            "address. Second, the broker is in \"<info>NEEDS_DATA</info>\" status. This is the default status for new ",
        );
        $output->writeln(
            "broker models. Brokers can only be moved to the \"<info>NEW</info>\" status once the broker object is ",
        );
        $output->writeln("successfully validated.");

        $output->writeln("\nLet's attempt to move the broker into the \"<info>NEW</info>\" status right now.\n");
        $helper->ask($input, $output, new Question('<question>Ok (press enter key to continue)</question>'));

        $output->writeln(
            "\nAttempting to move <info>\$broker</info> from \"<info>NEEDS_DATA</info>\" to \"<info>NEW</info>\" status.\n",
        );
        $output->writeln("<error>TRANSITION ERRORS!</error>");
        $cadence->transition($broker, BrokerCadence::TRANSITION_TO_NEW);

        $output->writeln("\nAs you can see we have received a <info>TransitionBlockerList</info>. This list can be ");
        $output->writeln("used for logging purposes, error messages, etc. Let's set the broker's name now.");
        $output->writeln(' - Setting <info>firstName</info> to "John"...');
        $output->writeln(" - Setting <info>lastName</info> to \"Doe\"...\n");

        $helper->ask($input, $output, new Question("<question>Ok (press enter)</question>\n"));
        $broker->setFirstName('John')->setLastName('Doe');
        dump($broker);

        $output->writeln(
            "\nAgain, we'll attempt to transition from \"<info>NEEDS_DATA</info>\" to \"<info>NEW</info>\". We should expect ",
        );
        $output->writeln("fewer errors this time.\n");
        $helper->ask($input, $output, new Question("<question>Ok (press enter)</question>\n"));

        $output->writeln("<error>TRANSITION ERRORS!</error>");
        $cadence->transition($broker, BrokerCadence::TRANSITION_TO_NEW);

        $output->writeln("\nLet's set the email address now:");
        $output->writeln(" - Setting <info>email</info> to \"john.doe@example.tld\"...\n");
        $helper->ask($input, $output, new Question("<question>Ok (press enter)</question>\n"));

        $broker->setEmail('john.doe@example.tld');
        dump($broker);

        $output->writeln("\nAnd attempt the transition again...\n");
        $helper->ask($input, $output, new Question("<question>Ok (press enter)</question>\n"));
        $cadence->transition($broker, BrokerCadence::TRANSITION_TO_NEW);

        $output->writeln("<info>Success! Applying the transition to the broker both validated the broker and ");
        $output->writeln("updated the status.</info>\n");
        dump($broker);

        $output->writeln("\nFrom here we could setup watchers on models to automatically attempt transitions, for ");
        $output->writeln(
            "example, if the email address was removed, automatically transition back to \"<info>NEEDS_DATA</info>\".",
        );

        return 0;
    }

    private function writeHeading(OutputInterface $output, string $heading): void
    {
        $output->writeln("<bg=green>" . str_repeat(' ', strlen($heading) + 2) . "</>");
        $output->writeln("<bg=green> $heading </>");
        $output->writeln("<bg=green>" . str_repeat(' ', strlen($heading) + 2) . "</>");
    }
}
