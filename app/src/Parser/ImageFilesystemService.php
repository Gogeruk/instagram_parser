<?php

namespace App\Parser;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\Finder;

/**
 * ImageFilesystemService
 */
class ImageFilesystemService
{

    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $parameterBag;

    /**
     * @param ParameterBagInterface $parameterBag\
     */
    public function __construct
    (
        ParameterBagInterface  $parameterBag
    )
    {
        $this->parameterBag = $parameterBag;
    }


    /**
     * @param string $dirName
     * @param $dirPath
     * @param int $permission
     * @return void
     */
    public function makeDirectory
    (
        string $dirName,
        $dirPath = false,
        int $permission = 0775
    ) : void
    {
        if ($dirPath === false) {

            // set a default path
            $dirPath = $this->parameterBag->get('kernel.project_dir') . '/public/data/';
        }


        $filesystem = new Filesystem();

        // check if a dir exists
        if (!$filesystem->exists($dirPath . $dirName)) {

            // create a dir
            $filesystem->mkdir
            (
                $dirPath . $dirName,
                $permission
            );
        }
    }


    /**
     * @param string $path
     * @return array
     */
    public function getDirNamesRecursive
    (
        string $path
    ) : array
    {
        $dirNames = [];

        // get dir names from a path
        $finder = Finder::create();
        $finder
            ->directories()
            ->in($path)
            ->depth(2)
        ;

        foreach ($finder as $dir) {
            $dirNames[] = $dir->getRelativePathname();
        }

        return $dirNames;
    }
}