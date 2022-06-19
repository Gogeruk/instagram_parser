<?php

namespace App\Parser;


class InstagramParser
{
    /**
     * @var ParserService
     */
    private $parserService;

    /**
     * @var UrlParserService
     */
    private $urlService;

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



    public function getDataFromInstagram
    (
        string $pathToDriver
    ): array
    {
        // open browser and make a request
        $client = $this->urlService->summonPantherClientAndMakeRequest
        (
            'https://www.tripadvisor.com/',
            $pathToDriver,
            true
        );

        print_r($client->getPageSource());
        exit();


        return $data ?? [];
    }






    /**
     * @param array $html
     * @param string $xPathFilter
     * @param string $targetDOMElementName
     * @return array
     */
    public function getTextFromTargetDDMElement
    (
        array  $html,
        string $xPathFilter,
        string $targetDOMElementName
    ): array
    {
        $node = $this->parserService->getChildNodes(
            $this->parserService->getHtmlElementsOfTargetByHtml(
                $html,
                $xPathFilter
            ),
            $targetDOMElementName
        );

        $text = [];
        foreach ($node as $key => $crawler) {
            $text[$key] = null;
            if ($crawler != null) {
                foreach ($crawler as $element) {
                    if ($element != null) {
                        $text[$key][] = $element->nodeValue;
                    }
                }
            }
        }

        return $text;
    }


    /**
     * @param array $html
     * @param string $xPathFilter
     * @param string $targetParentDOMElementName
     * @return array
     */
    public function getHTMLTextFromTargetDDMElementsFromWithinParentDDMElement
    (
        array  $html,
        string $xPathFilter,
        string $targetParentDOMElementName
    ): array
    {
        $node = $this->parserService->getChildNodes(
            $this->parserService->getHtmlElementsOfTargetByHtml(
                $html,
                $xPathFilter
            ),
            $targetParentDOMElementName
        );

        $text = [];
        foreach ($node as $key => $crawler) {
            $text[$key] = null;
            if ($crawler != null) {
                foreach ($crawler as $element) {
                    if ($element != null) {
                        $item = 1;
                        while (true) {
                            $child = $element->childNodes->item($item);
                            $item++;
                            if ($child == null) {
                                break;
                            }
                            $html = $child->ownerDocument->saveHTML($child);
                            if (mb_strlen($html) > 5) {
                                $text[$key][] = $html;
                            }
                        }
                    }
                }
            }
        }

        return $text;
    }


    /**
     * @param array $html
     * @param string $xPathFilter
     * @param string $targetParentDOMElementName
     * @param string $targetChildDOMElementName
     * @param string $targetChildDOMElementAttributesName
     * @return array
     */
    public function getAtributeTextFromTargetDDMElementsFromWithinParentDDMElement
    (
        array  $html,
        string $xPathFilter,
        string $targetParentDOMElementName,
        string $targetChildDOMElementName,
        string $targetChildDOMElementAttributesName
    ): array
    {
        $node = $this->parserService->getChildNodes(
            $this->parserService->getHtmlElementsOfTargetByHtml(
                $html,
                $xPathFilter
            ),
            $targetParentDOMElementName
        );

        $attributes = [];
        foreach ($node as $key => $element) {
            $attributes[$key] = [];
            if ($element != null) {
                $attributes[$key] = $this->parserService->getChildNodeAttributeByParentNode
                (
                    $element,
                    $targetChildDOMElementName,
                    $targetChildDOMElementAttributesName
                );
            }
        }

        return $attributes;
    }


    /**
     * @param string $html
     * @param string $regex
     * @return bool
     */
    public function htmlOfUrlRegexCheck
    (
        string $html,
        string $regex
    ) : bool
    {
        if (preg_match($regex, $html)) {
            return true;
        }
        return false;
    }


    /**
     * @param $client
     * @param $url
     * @param string $pathToDriver
     * @param bool $firefox
     * @return \Symfony\Component\Panther\Client
     */
    public function getClient
    (
        $client,
        $url,
        string $pathToDriver,
        bool $firefox = false
    ): \Symfony\Component\Panther\Client
    {
        // open browser and make a request
        if (!isset($client)) {
            $client = $this->urlService->summonPantherClientAndMakeRequest
            (
                $url,
                $pathToDriver,
                $firefox
            );
        } else {

            // try to make a new request to the next image url
            sleep(0.5); // sleep is important
            try {
                $client->request('GET', $url);
            } catch (\Exception $e) {
                $client = $this->urlService->summonPantherClientAndMakeRequest
                (
                    $url,
                    $pathToDriver,
                    true
                );
            }
        }
        return $client;
    }


    /**
     * @param $html
     * @return array
     */
    public function getAllUrls($html): array
    {
        preg_match_all
        (
            "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",
            html_entity_decode($html),
            $resultUrls,
            PREG_PATTERN_ORDER
        );
        return array_unique($resultUrls[0]);
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


    /**
     * @param $client
     * @param string $url
     * @param int $waitTime
     * @param string $pathToDriver
     * @param bool $firefox
     * @return array
     */
    public function openBrowserAndGetHtml
    (
        $client,
        string $url,
        int $waitTime,
        string $pathToDriver,
        bool $firefox = false
    ): array
    {
        // open browser and make a request
        $client = $this->getClient
        (
            $client,
            $url,
            $pathToDriver,
            $firefox
        );

        // wait a little for data to load
        $client->wait($waitTime);
        sleep($waitTime);

        // get html
        $html = html_entity_decode($client->getPageSource());

        // clean a browser
        $client = $this->cleanABrowser($client);

        return array($client, $html);
    }


    /**
     * @param string $html
     * @param string $filterStr
     * @param string $regex
     * @param string|null $removeStr
     * @return array
     */
    public function filterHtmlForUrls
    (
        string  $html,
        string  $filterStr,
        string  $regex,
        ?string $removeStr
    ) : array
    {
        preg_match_all
        (
            $regex,
            $html,
            $result,
            PREG_PATTERN_ORDER
        );
        $result = array_unique($result[0]);

        $resultStrs = [];
        foreach ($result as $resultStr) {
            if (strpos($resultStr, $filterStr) !== false) {
                if (!is_null($removeStr)) {
                    $resultStrs[] = str_replace
                    (
                        $removeStr,
                        '',
                        $resultStr
                    );
                } else {
                    $resultStrs[] = $resultStr;
                }
            }
        }

        return $resultStrs;
    }
}