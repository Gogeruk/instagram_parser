<?php

namespace App\Parser;

use App\Entity\Post;
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
     * @param ParsedDataProcessor $parsedDataProcessor
     * @param EntityManagerInterface $em
     * @param ImageFilesystemService $imageFilesystemService
     * @param InstagramUserRepository $instagramUserRepository
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
     * @return bool
     */
    public function checkExistsInstagramUser
    (
        string $instagramUserUsername
    ) : bool
    {
        if (!empty($this->instagramUserRepository->findOneBy(['username' => $instagramUserUsername]))) {

            // exists
            return true;
        }

        // does not exist
        return false;
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
            $instagramUser = $this->getSaveInstagramUser
            (
                $instagramUserUsername,
                $this->parsedDataProcessor->removeEmojis($instagramUserName),
                $this->parsedDataProcessor->getCorrectStrLen($this->parsedDataProcessor->removeEmojis($instagramUserDescription), 500),
            );

            // save Instagram User Visual
            foreach ($instagramUserVisualUrls as $instagramUserVisualUrl) {

                // save visual to the box
                $visualSavedCheck = $this->imageFilesystemService->saveVisual($instagramUserVisualUrl);

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


            foreach ($postTexts as $key => $postText) {

                // save Instagram User Post
                $instagramUserPost = $this->getSaveInstagramUserPost
                (
                    $this->parsedDataProcessor->getCorrectStrLen($this->parsedDataProcessor->removeEmojis($postText), 500),
                );

                // save visual to the box
                $visualSavedCheck = $this->imageFilesystemService->saveVisual($postVisualUrls[$key]);

                // save visual to db
                if (!is_bool($visualSavedCheck)) {

                    // make visual
                    $instagramUserPostVisual = $this->getSavePostVisual
                    (
                        $visualSavedCheck['name'],
                        $visualSavedCheck['path']
                    );

                    // add visual
                    $instagramUserPost->addPostVisual($instagramUserPostVisual);

                    // add to Instagram User
                    $instagramUser->addPost($instagramUserPost);
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
    ) : PostVisual
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


    /**
     * @param string|null $instagramUserPostText
     * @return Post
     */
    public function getSaveInstagramUserPost
    (
        ?string $instagramUserPostText,
    ) : Post
    {
        // save Instagram User Post
        $post = new Post();
        $post->setText($instagramUserPostText);

        $this->em->persist($post);
        return $post;
    }
}