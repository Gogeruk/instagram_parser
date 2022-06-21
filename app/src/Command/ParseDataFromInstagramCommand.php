<?php

namespace App\Command;


use App\Parser\PrepareParseService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\YamlData\GetYaml;

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
        $this
            ->addArgument('usernames', InputArgument::IS_ARRAY)
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        echo 'Get data from Instagram...' . PHP_EOL;

        $usernames = $input->getArgument('usernames');
        if ($usernames == null) {

            // get usernames from ....
            $usernames = $this->getYaml->getArrayFromYaml
            (
                $this->parameterBag->get('kernel.project_dir') . "/src/YamlData/InstagramUsernames.yaml",
            );
        }

        $this->prepareParseService->parseDataFromInstagram
        (
            $this->parameterBag->get('kernel.project_dir') . "/drivers/geckodriver",
            $usernames
        );

        echo 'DONE' . PHP_EOL;
        return Command::SUCCESS;
    }
}
