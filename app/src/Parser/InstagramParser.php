<?php

namespace App\Parser;


class InstagramParser
{
    /**
     * @var ParserService
     */
    private ParserService $parserService;

    /**
     * @var UrlParserService
     */
    private UrlParserService $urlService;

    /**
     * @param UrlParserService $urlService
     * @param ParserService $parserService
     */
    public function __construct
    (
        UrlParserService $urlService,
        ParserService    $parserService
    )
    {
        $this->urlService = $urlService;
        $this->parserService = $parserService;
    }


    /**
     * @param string $pathToDriver
     * @param string $instagramUsername
     * @return array
     */
    public function getDataFromDumpor
    (
        string $pathToDriver,
        string $instagramUsername
    ): array
    {


        // open browser and make a request








        return $data ?? [];
    }



















    /**
     * @param string $string
     * @param string $start
     * @param string $end
     * @return string
     */
    public function getStringBetween
    (
        string $string,
        string $start,
        string $end
    ): string
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
}