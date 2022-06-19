<?php

namespace App\Parser;

/**
 * Class PrepareParseService
 * @package App\Service\UrlPlacesParserServices
 */
class PrepareParseService
{
    /**
     * @var string
     */
    public $border = '+-----------------------------------------------------------------------+';

    /**
     * @var ParsedDataProcessor
     */
    private $parsedDataProcessor;

    /**
     * @var InstagramParser
     */
    private $instagramParser;


    /**
     * @param ParsedDataProcessor $parsedDataProcessor
     */
    public function __construct
    (
        ParsedDataProcessor $parsedDataProcessor,
        InstagramParser     $instagramParser
    )
    {
        $this->parsedDataProcessor = $parsedDataProcessor;
        $this->instagramParser = $instagramParser;
    }




    public function parseDataFromInstagram
    (
        string $pathToDriver
    )
    {
        echo 'Driver path: ' . $pathToDriver . PHP_EOL;
        echo $this->border . PHP_EOL . 'Starting...' . PHP_EOL;


        // get data form tripadvisor
        $data = $this->instagramParser->getDataFromInstagram
        (
            $pathToDriver
        );

    }



}