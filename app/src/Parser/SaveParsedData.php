<?php

namespace App\Parser;

use App\Entity\PostVisual;
use App\Entity\Visual;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\InstagramUser;
use App\Repository\InstagramUserRepository;


/**
 * SaveParsedData
 */
class SaveParsedData
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    /**
     * @var ImageFilesystemService
     */
    private ImageFilesystemService $imageFilesystemService;

    /**
     * @var InstagramUserRepository
     */
    private InstagramUserRepository $instagramUserRepository;

    /**
     * @var ParsedDataProcessor
     */
    private ParsedDataProcessor $parsedDataProcessor;


    /**
     */
    public function __construct
    (
        ParsedDataProcessor  $parsedDataProcessor,
        EntityManagerInterface  $em,
        ImageFilesystemService  $imageFilesystemService,
        InstagramUserRepository $instagramUserRepository
    )
    {
        $this->em = $em;
        $this->parsedDataProcessor = $parsedDataProcessor;
        $this->imageFilesystemService = $imageFilesystemService;
        $this->instagramUserRepository = $instagramUserRepository;
    }


    /**
     * @param string $instagramUserUsername
     * @param string|null $instagramUserName
     * @param string|null $instagramUserDescription
     * @param array|null $instagramUserVisualUrls
     * @param array|null $postTexts
     * @param array|null $postVisualUrls
     * @return bool
     * @throws \Doctrine\DBAL\Exception
     */
    public function saveParsedDataWithTransaction
    (
        string  $instagramUserUsername,
        ?string $instagramUserName,
        ?string $instagramUserDescription,
        ?array  $instagramUserVisualUrls,
        ?array  $postTexts,
        ?array  $postVisualUrls
    ) : bool
    {
        // start transaction
        $this->em->getConnection()->beginTransaction();

        try {

            // save Instagram User
            $instagramUser = $this->getInstagramUser
            (
                $instagramUserUsername,
                $this->parsedDataProcessor->removeEmojis($instagramUserName),
                $this->parsedDataProcessor->getCorrectStrLen($this->parsedDataProcessor->removeEmojis($instagramUserDescription), 500),
            );

            // save Instagram User Visual
            foreach ($instagramUserVisualUrls as $instagramUserVisualUrl) {

                // save visual to the box
                $visualSavedCheck = $this->imageFilesystemService->saveVisual($instagramUserVisualUrl);

                print_r($visualSavedCheck);

                // save visual to db
                if (!is_bool($visualSavedCheck)) {

                    // make visual
                    $instagramUserVisual = $this->getSaveVisual
                    (
                        $visualSavedCheck['name'],
                        $visualSavedCheck['path']
                    );

                    // add visual
                    $instagramUser->addVisual($instagramUserVisual);
                }
            }






            $this->em->flush();
            $this->em->getConnection()->commit();
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;

            $this->em->getConnection()->rollBack();
            return false;
        }

        $this->em->clear();
        return true;
    }


    /**
     * @param string $visualName
     * @param string $visualPath
     * @return Visual
     */
    public function getSaveVisual
    (
        string  $visualName,
        string  $visualPath,
    ) : Visual
    {
        // save Instagram User
        $visual = new Visual();
        $visual->setName($visualName);
        $visual->setPath($visualPath);

        $this->em->persist($visual);
        return $visual;
    }


    /**
     * @param string $visualName
     * @param string $visualPath
     * @return Visual
     */
    public function getSavePostVisual
    (
        string  $visualName,
        string  $visualPath,
    ) : Visual
    {
        // save Instagram User
        $visual = new PostVisual();
        $visual->setName($visualName);
        $visual->setPath($visualPath);

        $this->em->persist($visual);
        return $visual;
    }


    /**
     * @param string $instagramUserUsername
     * @param string|null $instagramUserName
     * @param string|null $instagramUserDescription
     * @return InstagramUser|null
     */
    public function getInstagramUser
    (
        string  $instagramUserUsername,
        ?string $instagramUserName,
        ?string $instagramUserDescription
    ) : InstagramUser|null
    {
        // does this Instagram User exist in a db?
        $instagramUser = $this->instagramUserRepository->findOne
        (
            'username',
                $instagramUserUsername,
            '='
        )[0] ?? null;

        if ($instagramUser === null) {

            // save a new Instagram User
            $instagramUser = $this->getSaveInstagramUser
            (
                $instagramUserUsername,
                $instagramUserName,
                $instagramUserDescription
            );
        }

        return $instagramUser;
    }


    /**
     * @param string $instagramUserUsername
     * @param string|null $instagramUserName
     * @param string|null $instagramUserDescription
     * @return InstagramUser
     */
    public function getSaveInstagramUser
    (
        string  $instagramUserUsername,
        ?string $instagramUserName,
        ?string $instagramUserDescription
    ): InstagramUser
    {
        // save Instagram User
        $instagramUser = new InstagramUser();
        $instagramUser->setUsername($instagramUserUsername);
        if ($instagramUserName !== null) {
            $instagramUser->setName($instagramUserName);
        }
        if ($instagramUserDescription !== null) {
            $instagramUser->setDescription($instagramUserDescription);
        }

        $this->em->persist($instagramUser);
        return $instagramUser;
    }










}