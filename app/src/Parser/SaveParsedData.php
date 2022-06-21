<?php

namespace App\Parser;

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
     * @param EntityManagerInterface $em
     * @param ImageFilesystemService $imageFilesystemService
     * @param InstagramUserRepository $instagramUserRepository
     */
    public function __construct
    (
        EntityManagerInterface  $em,
        ImageFilesystemService  $imageFilesystemService,
        InstagramUserRepository $instagramUserRepository
    )
    {
        $this->em = $em;
        $this->imageFilesystemService = $imageFilesystemService;
        $this->instagramUserRepository = $instagramUserRepository;
    }



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
                $instagramUserName,
                $instagramUserDescription
            );

            // save Instagram User Visual
            foreach ($instagramUserVisualUrls as $instagramUserVisualUrl) {

                // save visual to the box
                $visualSavedCheck = $this->imageFilesystemService->saveVisual($instagramUserVisualUrl);


                // save visual to db
                // make visual

                // add visual
                $instagramUser->addVisual();
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