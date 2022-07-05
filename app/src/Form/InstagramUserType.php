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
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options = [];
        if (empty($options)) {
            $options =
            [
                'error_bubbling' => true,
                'trim' => false,
                'label_attr' => ['class' => 'text-white'],
                'attr' => [
                    'class'  => 'form-control border border-primary form-control-sm bg-dark text-white m-1',
                    'placeholder' => '@bob'
                ],
                'constraints' => [
                    new NotBlank(),
                    new NotNull(),
                    new Regex('/^@.*$/', 'Instagram username has to begin from \'@\'.'),
                    new Length
                    (
                        null,
                        null,
                        30,
                        null,
                        null,
                        null,
                        null,
                        'instagram username cannot be longer than 30 characters.'
                    )
                ]
            ];
        }

        $builder
            ->add
            (
                'username',
                TextType::class,
                $options
            )
        ;
    }


    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InstagramUser::class,
        ]);
    }
}
