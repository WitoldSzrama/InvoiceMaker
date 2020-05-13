<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserType extends AbstractType
{

    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => $this->translator->trans('company.name', [], 'labels')
            ])
            ->add('nip', null, [
                'label' => $this->translator->trans('company.nip', [], 'labels')
                ])
            ->add('regon', null, [
                'label' => $this->translator->trans('company.regon', [], 'labels')
            ])
            ->add('city', null, [
                'label' => $this->translator->trans('company.city', [], 'labels')
            ])
            ->add('postCode', null, [
                'label' => $this->translator->trans('company.postCode', [], 'labels')
            ])
            ->add('street', null, [
                'label' => $this->translator->trans('company.street', [], 'labels')
            ])
            ->add('stNumber', null, [
                'label' => $this->translator->trans('company.streetNumber', [], 'labels')
            ])
            ->add('accountNumber', null, [
                'label' => $this->translator->trans('company.accountNumber', [], 'labels')
            ])
            ->add('baseNumber', null, [
                'label' => $this->translator->trans('company.user.baseNumber', [], 'labels'),
                'required' => false,
            ])
            ->add('invoiceNumberTemplate', null, [
                'label' => $this->translator->trans('company.user.invoiceNumberTemplate', [], 'labels'),
                'help' => $this->translator->trans('help.invoiceNumberTemplate', [], 'message'),
                'required' => false,
            ])
            ->add('baseCurrency', null, [
                'label' => $this->translator->trans('company.user.baseCurrency', [], 'labels'),
                'help' => $this->translator->trans('help.baseCurrency', [], 'message'),
                'required' => false,
            ])
            ->add('baseVat', null, [
                'label' => $this->translator->trans('company.user.baseVat', [], 'labels'),
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
