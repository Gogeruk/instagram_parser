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
use Symfony\Component\Validator\Constraints\Regex;

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
                        new Regex('/^@.*$/', 'Instagram username has to begin from \'@\''),
                        new Length(null, null, 30)
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
