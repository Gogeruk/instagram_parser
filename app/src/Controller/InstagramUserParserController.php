<?php

namespace App\Controller;

use App\Form\InstagramUserType;
use App\Parser\InstagramParser;
use App\Parser\SaveParsedData;
use App\Repository\InstagramUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


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
    public function parseInstagramUser
    (
        Request                 $request,
        InstagramUserRepository $instagramUserRepository,
        ParameterBagInterface   $parameterBag,
        InstagramParser         $instagramParser,
        SaveParsedData          $saveParsedData
    ): Response
    {
        $form = null;
        if ($request->isMethod('POST')) {
            $form = $this->createForm(InstagramUserType::class);
            $form->handleRequest($request);
        }

        if ($form && $form->isSubmitted() && $form->isValid()) {

            $instagramUserName = $form->getData()->getUsername();
            $instagramUserInDatabase = $instagramUserRepository->findOne
            (
                'username',
                    $instagramUserName,
                '='
            )[0] ?? null;

            // if user exists redirect to table with data by id
            if (!is_null($instagramUserInDatabase)) {
                return $this->render('instagram_user_parser/index.html.twig', [
                    'users' => [$instagramUserInDatabase],
                ]);
            }

            //
            //
            //
            $this->render('instagram_user_parser/index.html.twig', [
                'users' => $instagramUserRepository->findAll(),
            ]);

            // parse new user
            $data = $instagramParser->getDataFromDumpor
            (
                $parameterBag->get('kernel.project_dir') . "/drivers/geckodriver",
                $instagramUserName
            );

            // save new user
            $instagramUser = $saveParsedData->saveParsedDataWithTransaction
            (
                $data['username'],
                $data['name'],
                $data['description'],
                $data['images'],
                $data['posts']['text'],
                $data['posts']['img']
            );

            //
            //
            //
            $this->render('instagram_user_parser/index.html.twig', [
                'users' => $instagramUserRepository->findAll(),
            ]);

            if ($instagramUser === false) {

                // dispakt stuff?????
            }

            // display user
            return $this->render('instagram_user_parser/index.html.twig', [
                'users' => [$instagramUser],
            ]);
        }

        return $this->renderForm('instagram_user_parser/new.html.twig', [
            'form' => $form,
        ]);
    }
}
