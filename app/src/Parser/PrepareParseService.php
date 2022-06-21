<?php

namespace App\Parser;

/**
 * Class PrepareParseService
 */
class PrepareParseService
{
    /**
     * @var string
     */
    public $border = '+-----------------------------------------------------------------------+';

    /**
     * @var InstagramParser
     */
    private InstagramParser $instagramParser;

    /**
     * @param InstagramParser $instagramParser
     */
    public function __construct
    (
        InstagramParser $instagramParser
    )
    {
        $this->instagramParser = $instagramParser;
    }


    /**
     * @param string $pathToDriver
     * @param array $instagramUsernames
     * @return void
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function parseDataFromInstagram
    (
        string $pathToDriver,
        array  $instagramUsernames
    )
    {
        echo 'Driver path: ' . $pathToDriver . PHP_EOL;
        echo $this->border . PHP_EOL . 'Starting...' . PHP_EOL . PHP_EOL;

        foreach ($instagramUsernames as $instagramUsername) {

            // get data form dumpor.com
            $data = $this->instagramParser->getDataFromDumpor
            (
                $pathToDriver,
                $instagramUsername
            );

            // save data
//            print_r($data);
//            exit();









        }
    }
}