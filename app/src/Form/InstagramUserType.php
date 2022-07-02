<?php


namespace App\Form;

use App\Entity\InstagramUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class InstagramUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add
            (
                'username',
                TextType::class,
                [
                    'trim' => false,
                    'constraints' => [
                        new NotBlank(),
                        new NotNull(),
                        new Length(null, 2, 255),
                    ]
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InstagramUser::class,
        ]);
    }
}
