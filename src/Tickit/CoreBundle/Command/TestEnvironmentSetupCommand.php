<?php

namespace Tickit\CoreBundle\Command;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;

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
        $schemaTool->dropDatabase();
        $output->writeln('Database dropped successfully!');

        $output->writeln('Re-creating database for test environment...');
        $schemaTool->createSchema($metaData);
        $output->writeln('Database created successfully!');

        $output->writeln('Loading data fixtures for test environment...');

        // let's load some fixtures...
        $paths = array();
        foreach ($this->getApplication()->getKernel()->getBundles() as $bundle) {
            $paths[] = $bundle->getPath().'/DataFixtures/ORM';
        }

        $loader = new ContainerAwareLoader($this->getContainer());
        foreach ($paths as $path) {
            if (is_dir($path)) {
                $loader->loadFromDirectory($path);
            }
        }
        $fixtures = $loader->getFixtures();

        $executor = new ORMExecutor($em);
        $executor->setLogger(function($message) use ($output) {
            $output->writeln(sprintf('  <comment>></comment> <info>%s</info>', $message));
        });
        $executor->execute($fixtures, true);
    }
}
