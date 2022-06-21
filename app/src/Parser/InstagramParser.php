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

        // load more posts
        $start = 1500;
        for ($i = 0; $i < 1500; $i++) {
            $client->executeScript('window.scrollTo(1, ' . $start . ');');
            $client->executeScript('window.scrollTo(1, 1);');
            $start = $start + 250;
        }

        // get html
        $html = html_entity_decode($client->getPageSource());


        // get data
        // get user's name
        $data['name'] = $this->parserService->getTextFromTargetDDMElement
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
        )[0][0] ?? null;

        // get username
        $data['username'] = $this->parserService->getTextFromTargetDDMElement
        (
            [$html],
            $this->parserService->getCSSToXpath(
                'html > ' .
                'body > ' .
                'div[id^="user-page"] > ' .
                'div[class^="user"] > ' .
                'div[class^="row"] > ' .
                'div[class^="col-md-4 col-8 my-3"] > ' .
                'div[class^="user__title"]'
            ),
            'h4'
        )[0][0] ?? null;

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

        // get description
        $data['description'] = $this->parserService->getTextFromTargetDDMElement
            (
                [$html],
                $this->parserService->getCSSToXpath(
                    'div[class^="col-md-5 my-3"]'
                ),
                'div'
            )[0][0] ?? null;

        // get posts
        $data['posts']['img'] = $this->parserService->getAtributeTextFromTargetDDMElementsFromWithinParentDDMElement
            (
            [$html],
            $this->parserService->getCSSToXpath(
                'div[class^="content__list grid infinite_scroll cards profile_posts"] > ' .
                'div[class^="content__item grid-item card"] > ' .
                'div[class^="content__img-wrap"]'
            ),
            'a',
            'img',
            'src'
        )[0] ?? null;

        $data['posts']['text'] = $this->parserService->getTextFromTargetDDMElement
        (
            [$html],
            $this->parserService->getCSSToXpath(
                'div[class^="content__list grid infinite_scroll cards profile_posts"] > ' .
                'div[class^="content__item grid-item card"] > ' .
                'div[class^="content__img-wrap"] > ' .
                'div[class^="content__text"]'
            ),
            'p'
        )[0] ?? null;

        // clean a browser
        // without this firefox might die
        $client->quit();
        $client = null;

        return $data;
    }
}