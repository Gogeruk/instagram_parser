<?php

namespace App\Controller;

use App\Repository\InstagramUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class InstagramUserParserController extends AbstractController
{
    public function __construct
    (
        private EntityManagerInterface $em
    )
    {
    }

    /**
     * @Route("/intagram/users", name="app_post_index")
     */
    public function index(InstagramUserRepository $instagramUserRepository): Response
    {
        return $this->render('instagram_user_parser/index.html.twig', [
            'users' => $instagramUserRepository->findAll(),
        ]);
    }



}
