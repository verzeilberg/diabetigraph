<?php

namespace App\Command;

use App\Service\Route\RouteService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends Command
{


    private $routeService;

    public function __construct(RouteService $routeService)
    {
        $this->routeService = $routeService;
        parent::__construct();
    }

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:add-routes-to-database';

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Adds routes to database')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to add all the routes to the database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Route saver',
            '============',
            '',
        ]);

        // outputs a message without adding a "\n" at the end of the line
        $output->write('You are about to ');
        $output->writeln('add the routes to the database.');

        $routes = $this->routeService->getAllRoutes();

        foreach ($routes as $index => $route) {

            $routeExist = $this->routeService->repository->findOneBy(['route' => $route]);
            if(is_object($routeExist)) {
                $output->writeln('<fg=yellow>Route: ' . $route . ' already exist</>');
            } else {
                $newRoute = $this->routeService->newRoute();
                $newRoute->setRoute(trim($route));
                $result = $this->routeService->repository->save($newRoute);
                if ($result) {
                    $output->writeln('<fg=green>Saving route: ' . $route . '</>');
                } else {
                    $output->writeln('<fg=red>' . $route . ' not saved!' . '</>');
                }
            }
        }

        // the value returned by someMethod() can be an iterator (https://secure.php.net/iterator)
        // that generates and returns the messages with the 'yield' PHP keyword
        //$output->writeln($this->someMethod());

        // outputs a message followed by a "\n"
        $output->writeln('Done saving routes!');

        return 0;
    }
}
