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
     * @var SaveParsedData
     */
    private SaveParsedData $saveParsedData;


    /**
     * @param InstagramParser $instagramParser
     * @param SaveParsedData $saveParsedData
     */
    public function __construct
    (
        InstagramParser $instagramParser,
        SaveParsedData  $saveParsedData
    )
    {
        $this->instagramParser = $instagramParser;
        $this->saveParsedData = $saveParsedData;
    }


    /**
     * @param string $pathToDriver
     * @param array $instagramUsernames
     * @return void
     * @throws \Doctrine\DBAL\Exception
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function parseDataFromInstagram
    (
        string $pathToDriver,
        array  $instagramUsernames
    ) : void
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
            $check = false;
            if (is_string($data['username'])) {
                if ($this->saveParsedData->checkExistsInstagramUser($data['username']) === true) {
                    echo 'USER: ' . $data['username'] . ' EXISTS' . PHP_EOL;
                    continue;
                }

                $check = $this->saveParsedData->saveParsedDataWithTransaction
                (
                    $data['username'],
                    $data['name'],
                    $data['description'],
                    $data['images'],
                    $data['posts']['text'],
                    $data['posts']['img']
                );
            }

            if (is_object($check)) {
                echo 'SAVED USER: ' . $data['username'] . PHP_EOL;
            }
        }
    }
}