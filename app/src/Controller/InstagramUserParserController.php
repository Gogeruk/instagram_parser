<?php

namespace App\Controller;

use App\Form\InstagramUserType;
use App\Repository\InstagramUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class InstagramUserParserController extends AbstractController
{
    /**
     * @Route("/instagram/users", name="app_instagram_index")
     */
    public function index(InstagramUserRepository $instagramUserRepository): Response
    {
        return $this->render('instagram_user_parser/index.html.twig', [
            'users' => $instagramUserRepository->findAll(),
        ]);
    }


    /**
     * @Route("/instagram/new", name="app_instagram_new")
     */
    public function InstagramParseUser(Request $request): Response
    {
        $form = null;
        if ($request->isMethod('POST')) {
            $form = $this->createForm(InstagramUserType::class);
            $form->handleRequest($request);
        }

        if ($form && $form->isSubmitted() && $form->isValid()) {

            // if yser exists redirect to his table by id


            // get user


            // save user


            // display user by id
            return $this->redirectToRoute('app_instagram_index');
        }

        return $this->renderForm('instagram_user_parser/new.html.twig', [
            'form' => $form,
        ]);
    }
}
