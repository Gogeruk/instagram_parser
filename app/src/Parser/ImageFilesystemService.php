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
     * @param string $url
     * @param string $path
     * @return array|bool
     */
    public function saveVisual
    (
        string $url,
        string $path = '/images/'
    ) : array|bool
    {
        try {

            // data for file name and path
            $ran = rand(1, 99999999999999);
            $pathParts = pathinfo($url);
            $projectDir = $this->parameterBag->get('kernel.project_dir');

            // find names for dirs
            $year = date('Y');
            $month = date('m');
            $day = date('d');

            // make new dirs
            $this->makeDirectory($year, $projectDir . $path);
            $this->makeDirectory($month, $projectDir . $path . $year . '/');
            $this->makeDirectory($day, $projectDir . $path . $year . '/' . $month . '/');

            // make a path where an image will be saved
            $fullPath =
                $projectDir.
                $path .
                $year . '/' .
                $month . '/' .
                $day . '/'
            ;

            if (
                preg_match("/http/i", $pathParts['basename']) or
                mb_strlen($pathParts['basename']) > 150
            ) {
                $pathParts['basename'] = 'image';
            }

            // download
            $getImage = file_get_contents($url);

            if (!preg_match('/\.(jpg|png|jpeg)$/', $pathParts['basename'])) {

                $finfo = new \finfo(FILEINFO_MIME);
                $mimetype = $finfo->buffer($getImage);

                $extension = explode("/", $mimetype);
                $extension = explode(";", $extension[1])[0];

                $pathParts['basename'] = $pathParts['basename'] . '.' . $extension;
            }

            file_put_contents(
                $fullPath . $ran . $pathParts['basename'],
                $getImage
            );


            return [
                'path' => substr($path . $year . '/' . $month . '/' . $day . '/', 8) . $ran . $pathParts['basename'],
                'name' => $ran . $pathParts['basename']
            ];

        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;

            return false;
        }
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
            $dirPath = $this->parameterBag->get('kernel.project_dir') . '/images/';
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