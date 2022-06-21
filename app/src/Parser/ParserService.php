<?php

namespace App\Parser;


use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\CssSelector\CssSelectorConverter;


/**
 * Class ParserService
 */
class ParserService
{
    /**
     * @var UrlParserService
     */
    private UrlParserService $urlService;

    /**
     * @param UrlParserService $urlService
     */
    public function __construct
    (
        UrlParserService $urlService
    )
    {
        $this->urlService = $urlService;
    }

    /**
     * @param $nodes
     * @param string $nodeName
     * @param string $childNodeName
     * @param string $attribute
     * @return array
     */
    public function getChildNodeAttributeByParentNodeNyArray(
        $nodes,
        string $nodeName,
        string $childNodeName,
        string $attribute
    ): array
    {
        $result = [];
        foreach ($nodes as $node) {
            if ($node != null) {
                $result[] = $this->getChildNodeAttributeByParentNode(
                    $node->children($nodeName),
                    $childNodeName,
                    $attribute
                );
            }
        }

        return $result;
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
        $node = $this->getChildNodes(
            $this->getHtmlElementsOfTargetByHtml(
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
     * @param array $filters
     * @param string $target
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getHtmlElementsOfTargetByFilters(array $filters, string $target): array
    {
        $filteredData = [];
        foreach ($filters as $filter) {
            $filteredData[] = $this->parseThroughHtmlByFilters
            (
                $this->urlService->getHtml($target),
                $filter
            );
        }

        return $filteredData;
    }

    /**
     * @param array $urls
     * @param string $filter
     * @return array
     */
    public function getHtmlElementsOfTargetByUrlsKey(array $urls, string $filter): array
    {
        $filteredData = [];
        foreach ($urls as $key) {
            $filteredData[] = $this->getHtmlElementsOfTargetByUrls($key, $filter);
        }

        return $filteredData;
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
        $node = $this->getChildNodes(
            $this->getHtmlElementsOfTargetByHtml(
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
        $node = $this->getChildNodes(
            $this->getHtmlElementsOfTargetByHtml(
                $html,
                $xPathFilter
            ),
            $targetParentDOMElementName
        );

        $attributes = [];
        foreach ($node as $key => $element) {
            $attributes[$key] = [];
            if ($element != null) {
                $attributes[$key] = $this->getChildNodeAttributeByParentNode
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
     * @param array $html
     * @param string $filter
     * @return array
     */
    public function getHtmlElementsOfTargetByHtml(array $html, string $filter): array
    {
        $filteredData = [];
        foreach ($html as $oneHtml) {
            if ($oneHtml != null) {
                $filteredData[] = $this->parseThroughHtmlByFilters
                (
                    $oneHtml,
                    $filter
                );
            }
        }

        return $filteredData;
    }

    /**
     * @param array $urls
     * @param string $filter
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getHtmlElementsOfTargetByUrls(array $urls, string $filter): array
    {
        $filteredData = [];
        foreach ($urls as $url) {
            $filteredData[] = $this->parseThroughHtmlByFilters
            (
                $this->urlService->getHtml($url),
                $filter
            );
        }

        return $filteredData;
    }

    /**
     * @param string $html
     * @param string $filter
     * @return object|Crawler|null
     */
    public function parseThroughHtmlByFilters(string $html, string $filter) : Crawler|null
    {
        $filterResult = null;

        $crawler = new Crawler($html);
        if ($crawler->filterXPath($filter)->count() > 0) {
            $filterResult = $crawler->filterXPath($filter);
        }

        return $filterResult;
    }


    /**
     * @param $node
     * @param string $childNodeName
     * @param string $attribute
     * @return array
     */
    public function getChildNodeAttributeByParentNode($node, string $childNodeName, string $attribute): array
    {
        $result = [];
        foreach ($node as $childNode) {
            if ($childNode != null) {
                $crawler = new Crawler($childNode);
                if ($crawler->filterXPath('//' . $childNodeName)->count() > 0) {
                    $result[] = $crawler->filterXPath('//' . $childNodeName)->attr($attribute);
                }
            }
        }

        return $result;
    }


    /**
     * @param array $nodes
     * @param string $childNodeName
     * @return array
     */
    public function getTextFromFirstChildNodeByArrayOfParentNodes(array $nodes, string $childNodeName): array
    {
        $result = [];
        foreach ($nodes as $key) {
            $result[] = $this->getTextFromFirstChildNodeByParentNode($key, $childNodeName);
        }

        return $result;
    }

    /**
     * @param $node
     * @param string $childNodeName
     * @return array
     */
    public function getTextFromFirstChildNodeByParentNode($node, string $childNodeName): array
    {
        $result = [];
        foreach ($node as $childNode) {
            if ($childNode == null) {
                $result[] = null;
            } else {
                $result[] = $childNode->filterXPath('//' . $childNodeName)->first()->text() ?? null;
            }
        }

        return $result;
    }

    /**
     * @param $nodes
     * @param string $childNodeName
     * @return array
     */
    public function getChildNodes($nodes, string $childNodeName): array
    {
        $result = [];
        foreach ($nodes as $node) {
            if ($node == null) {
                $result[] = null;
                continue;
            }
            $result[] = $node->children($childNodeName);
        }

        return $result;
    }

    /**
     * @param $CSS
     * @return string
     */
    public function getCSSToXpath($CSS): string
    {
        $converter = new CssSelectorConverter();
        return $converter->toXPath($CSS);
    }


    /**
     * @param $domElements
     * @param string $elemName
     * @return array
     */
    public function getCrawlerNodeFromDomElement($domElements, string $elemName): array
    {
        $elements = [];
        foreach ($domElements as $e) {
            $elements[] = $this->getChildNodes(
                $this->getHtmlElementsOfTargetByHtml(
                    [$e->ownerDocument->saveHTML($e)],
                    $this->getCSSToXpath($elemName)
                ),
                $elemName
            );
        }

        return $elements;
    }


    /**
     * @param $ekemebts
     * @param int $consecutiveNumber
     * @return mixed|void
     */
    public function getElementByItsConsecutiveNumber($ekemebts, int $consecutiveNumber)
    {
        $elemNumber = 1;
        foreach ($ekemebts as $e) {
            if ($elemNumber == $consecutiveNumber) {
                return $e;
            }

            $elemNumber++;
        }
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