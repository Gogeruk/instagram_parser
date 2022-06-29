<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InstagramUserParserController extends AbstractController
{


    #[Route('/parsers', name: 'app_instagram_user_parser')]
    public function index(): Response
    {
        return $this->render('instagram_user_parser/index.html.twig', [
            'controller_name' => 'InstagramUserParserController',
        ]);
    }








}
