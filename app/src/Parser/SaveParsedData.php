<?php

namespace App\Parser;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\InstagramUser
use App\Repository\InstagramUserRepository


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
     * @var InstagramUserRepository
     */
    private InstagramUserRepository $instagramUserRepository;


    /**
     * @param EntityManagerInterface $em
     * @param InstagramUserRepository $instagramUserRepository
     */
    public function __construct
    (
        EntityManagerInterface $em,
        InstagramUserRepository $instagramUserRepository
    )
    {
        $this->em = $em;
        $this->instagramUserRepository = $instagramUserRepository;
    }

    /**
     * @param string $instagramUserUsername
     * @param string|null $instagramUserName
     * @param string|null $instagramUserDescription
     * @param array|null $visualUrl
     * @param array|null $postText
     * @param array|null $postVisualUrl
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
                $instagramUserName,
                $instagramUserDescription
            );

            // save Instagram User Visual
//            foreach ($instagramUserVisualUrls as $instagramUserVisualUrl) {
//
//                // save visual
//
//                // save visual to db
//
//            }







            $this->em->flush();
            $this->em->getConnection()->commit();
        } catch (\Exception $e) {
            echo $e . PHP_EOL;

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
     * @return InstagramUser|mixed|null
     */
    public function getInstagramUser
    (
        string  $instagramUserUsername,
        ?string $instagramUserName,
        ?string $instagramUserDescription
    )
    {
        // does this InstagramUser exist in a db?
        $instagramUser = $this->instagramUserRepository->findOne
        (
            'username',
                $instagramUserUsername,
            '='
        )[0] ?? null;

        if ($instagramUser === null) {

            // save a new InstagramUser
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