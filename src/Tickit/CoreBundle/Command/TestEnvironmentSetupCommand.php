<?php

namespace Tickit\CoreBundle\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command for setting up the test environment.
 *
 * This command can be executed prior to running tests and will:
 *
 * - Drop the current test database
 * - Re-create the test database from the latest schema files
 * - Load fixtures into the test database
 *
 * @package Tickit\CoreBundle\Command
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class TestEnvironmentSetupCommand extends ContainerAwareCommand
{
    /**
     * Configures the command for execution.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('tickit:tests:setup')
             ->setDescription('Sets up the test environment for Tickit');
    }

    /**
     * Executes the command.
     *
     * @param InputInterface  $input  The console input interface
     * @param OutputInterface $output The console output interface
     *
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $env = $container->getParameter('kernel.environment');

        if ('test' !== $env) {
            $output->writeln('<error>You must execute this command with the "--env test" option.</error>');
            return;
        }

        /** @var EntityManager $em */
        $em = $container->get('doctrine')->getManager();
        $metaData = $em->getMetadataFactory()->getAllMetadata();

        $schemaTool = new SchemaTool($em);

        $output->writeln('Dropping database for test environment...');
        try {
            $schemaTool->dropDatabase();
            $output->writeln('Database dropped successfully!');
        } catch (\PDOException $e) {
            $dbInput = new ArrayInput(
                array(
                    'command' => 'doctrine:database:create',
                    '--env' => 'test'
                )
            );
            $command = $this->getApplication()->find('doctrine:database:create');
            $command->run($dbInput, $output);
        }

        $output->writeln('Re-creating schema for test environment...');
        $schemaTool->createSchema($metaData);
        $output->writeln('Database created successfully!');

        $output->writeln('Loading data fixtures for test environment...');
        $fixturesInput = new ArrayInput(
            array(
                'command' => 'doctrine:fixtures:load',
                '--env' => 'test',
                '--append' => true
            )
        );
        $fixturesCommand = $this->getApplication()->find('doctrine:fixtures:load');
        $fixturesCommand->run($fixturesInput, $output);
    }
}
