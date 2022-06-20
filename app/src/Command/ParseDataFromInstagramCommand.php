<?php

namespace App\Command;


use App\Parser\PrepareParseService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\GetYaml;

/**
 * Class ParseDataFromInstagramCommand
 * @package App\Command
 */
class ParseDataFromInstagramCommand extends Command
{
    protected static $defaultName = 'app:parse';


    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $parameterBag;

    /**
     * @var PrepareParseService
     */
    protected PrepareParseService $prepareParseService;

    /**
     * @var GetYaml
     */
    protected GetYaml $getYaml;

    /**
     * @param PrepareParseService $prepareParseService
     * @param GetYaml $getYaml
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct
    (
        PrepareParseService   $prepareParseService,
        GetYaml               $getYaml,
        ParameterBagInterface $parameterBag
    )
    {
        $this->prepareParseService = $prepareParseService;
        $this->getYaml = $getYaml;
        $this->parameterBag = $parameterBag;
        parent::__construct();
    }

    protected function configure(): void
    {
    }


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        echo 'Get data from Instagram...' . PHP_EOL;




        $this->prepareParseService->parseDataFromInstagram
        (
            $this->parameterBag->get('kernel.project_dir') . "/drivers/geckodriver",
            ['Gogeruk']
        );

        echo 'DONE' . PHP_EOL;
        return Command::SUCCESS;
    }
}
