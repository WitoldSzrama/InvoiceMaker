<?php

namespace App\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class CompanyType extends AbstractType
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
            ->add('name', null, [
                'label' => $this->translator->trans('company.name', [], 'labels'),
            ])
            ->add('nip', null, [
                'label' => $this->translator->trans('company.nip', [], 'labels'),
                'attr' => ['min' => 1000000000, 'max' => 9999999999],
                ])
            ->add('regon', null, [
                'label' => $this->translator->trans('company.regon', [], 'labels'),
                'attr' => ['min' => 1000000, 'max' => 99999999999999],
            ])
            ->add('city', null, [
                'label' => $this->translator->trans('company.city', [], 'labels'),
            ])
            ->add('postCode', null, [
                'label' => $this->translator->trans('company.postCode', [], 'labels'),
            ])
            ->add('houseNumber', null, [
                'label' => $this->translator->trans('company.houseNumber', [], 'labels'),
            ])
            ->add('localNumber', null, [
                'label' => $this->translator->trans('company.localNumber', [], 'labels'),
            ])
            ->add('street', null, [
                'label' => $this->translator->trans('company.street', [], 'labels'),
            ])
            ->add('accountNumber', null, [
                'label' => $this->translator->trans('company.accountNumber', [], 'labels'),
            ])
            ->addEventListener(FormEvents::SUBMIT, function(FormEvent $formEvent) {
                $user = $formEvent->getData();
                $user->setStNumber($user->getHouseNumber() . '/' . $user->getLocalNumber());
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
