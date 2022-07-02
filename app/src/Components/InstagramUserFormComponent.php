<?php

namespace App\Components;

use App\Entity\InstagramUser;
use App\Form\InstagramUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('instagram_user_form')]
class InstagramUserFormComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    /**
     * @var InstagramUser|null
     */
    #[LiveProp(fieldName: 'initialFormData')]
    public ?InstagramUser $InstagramUser = null;

    #[LiveProp]
    public string $buttonLabel = 'Parse';

    /**
     * Used to re-create the InstagramUserType form for re-rendering.
     */
    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(InstagramUserType::class, $this->InstagramUser);
    }
}
