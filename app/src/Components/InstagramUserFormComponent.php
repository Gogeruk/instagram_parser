<?php

namespace App\Components;

use App\Entity\InstagramUser;
use App\Form\InstagramUserType;
use App\Repository\InstagramUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('instagram_user_form')]
class InstagramUserFormComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    /**
     * The initial data used to create the form.
     *
     * Needed so that the same form can be re-created
     * when the component is re-rendered via Ajax.
     */
    #[LiveProp(fieldName: 'initialFormData')]
    public ?InstagramUser $InstagramUser = null;

    #[LiveProp]
    public string $buttonLabel = 'Parse';

    private InstagramUserRepository $InstagramUserRepository;

    /**
     * Used to re-create the InstagramUserType form for re-rendering.
     */
    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(InstagramUserType::class, $this->InstagramUser);
    }


    #[LiveAction]
    public function parse()
    {
        echo 'LiveAction';
        return $this->redirectToRoute('app_instagram_index');

        $this->submitForm();

        echo 'aaaaaaaaaaa';

    }
}
