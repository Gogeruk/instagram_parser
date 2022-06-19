<?php

namespace App;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * GetYaml
 */
class GetYaml
{
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;


    /**
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct
    (
        ParameterBagInterface $parameterBag
    )
    {
        $this->parameterBag = $parameterBag;
    }

    /**
     * @param $path
     * @return mixed
     */
    public function getArrayFromYaml
    (
        $path = null
    )
    {
        if ($path === null) {
            $path =
                $this->parameterBag->get('kernel.project_dir') .
                "/src/Service/UrlPlacesParserServices/cities.yaml"
            ;
        }

        return Yaml::parseFile($path);
    }
}