<?php

namespace App\Form;

use App\Entity\User;
use App\Utilities\InvoiceMenagerutilities;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationFormType extends AbstractType
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {

        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('name', null, [
                'help' => $this->translator->trans('help.lengthName', [], 'message')
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => [
                    'attr' => ['pattern' => InvoiceMenagerutilities::PASSWORD_REGEX],
                    'label' => $this->translator->trans('password', [], 'labels'),
                    'help' => $this->translator->trans('help.password', [], 'message'),
                ],
                'second_options' => ['label' => $this->translator->trans('passwordRepeat', [], 'labels')],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => $this->translator->trans('agreeTerms', [], 'labels'),
                'constraints' => [
                    new IsTrue([
                        'message' => $this->translator->trans('agreeTermsMessage', [], 'message'),
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
