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
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getDataFromDumpor
    (
        string $pathToDriver,
        string $instagramUsername
    ): array
    {
        $profileUrl = 'https://dumpor.com/v/'. urlencode($instagramUsername);

        // open a browser and make a request
        $client = $this->urlService->getClient
        (
            null,
            $profileUrl,
            $pathToDriver
        );

        // wait a little for the profile to load
        sleep(0.5);

        // get html
        $html = html_entity_decode($client->getPageSource());

        // clean a browser
        $client->quit();
        $client = null;


        // get data
        // get an user's name
        $artistName = $this->parserService->getTextFromTargetDDMElement
            (
                [$html],
                $this->parserService->getCSSToXpath(
                    'html > ' .
                    'body > ' .
                    'div[id^="user-page"] > ' .
                    'div[class^="user"] > ' .
                    'div[class^="row"] > ' .
                    'div[class^="col-md-4 col-8 my-3"] > ' .
                    'div[class^="user__title"] > ' .
                    'a'
                ),
                'h1'
            )[0][0] ?? 'NONE';

        // get user's image url
        preg_match_all
        (
            "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",
            html_entity_decode($this->parserService->getAtributeTextFromTargetDDMElementsFromWithinParentDDMElement
                (
                    [$html],
                    $this->parserService->getCSSToXpath(
                        'html > ' .
                        'body > ' .
                        'div[id^="user-page"] > ' .
                        'div[class^="user"] > ' .
                        'div[class^="row"] > ' .
                        'div[class^="col-md-3 col-4 my-3"]'
                    ),
                    'div',
                    'div',
                    'style'
                )[0][0] ?? ''),
            $artistImageUrl,
            PREG_PATTERN_ORDER
        );



        $data[] = $artistName;
        $data[] = $artistImageUrl;

        print_r($data);
        exit();



        return $data;
    }
}