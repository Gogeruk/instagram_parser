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
     * @param array $names
     * @return void
     */
    public function parseDataFromInstagram
    (
        string $pathToDriver,
        array  $instagramUsernames
    )
    {
        echo 'Driver path: ' . $pathToDriver . PHP_EOL;
        echo $this->border . PHP_EOL . 'Starting...' . PHP_EOL;

        foreach ($instagramUsernames as $instagramUsername) {

            // get data form dumpor.com
            $data = $this->instagramParser->getDataFromDumpor
            (
                $pathToDriver,
                $instagramUsername
            );



        }
    }
}