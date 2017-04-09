<?php

use Behat\Behat\Context\Context;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements context
{
    /**
     * @var AppKernel
     */
    private $kernel;

    /**
     * @var Response
     */
    private $response;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct($kernel)
    {
        $this->kernel = $kernel;
        $kernel->boot();

        // Die if that's not a test environment
        if ($this->kernel->getEnvironment() !== 'test') {
            die('That feature can be only used on a test environment');
        };
    }

    /**
     * Drop and create the database for testing on void databases
     * @Given A void database
     */
    public function resetTestDatabase()
    {
        /**
         * TODO: delete the executeShellConsoleCommand and use the
         * function executeConsoleCommand after solve the problem
         * that he uses the prod environement.
         */
        $this->executeShellConsoleCommand('doctrine:database:drop --env=test --force');
        $this->executeShellConsoleCommand('doctrine:database:create --env=test');
        $this->executeShellConsoleCommand('doctrine:schema:create --env=test');
    }

    private function executeShellConsoleCommand($command)
    {
        $console = $this->kernel->getContainer()->getParameter('kernel.root_dir') . '/../bin/console ';
        shell_exec($console . $command);
    }


    private function executeConsoleCommand($command, $arguments = [])
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);


        $input = new ArrayInput(
            array_merge(
                [
                    'command' => $command,
                ],
                $arguments
            )
        );

        // You can use NullOutput() if you don't need the output
        $output = new BufferedOutput();
        $application->run($input, null);

        // return the output, don't use if you used NullOutput()
        $content = $output->fetch();

        return $content;
    }
}




