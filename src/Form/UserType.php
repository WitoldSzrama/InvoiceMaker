<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserType extends AbstractType
{
    private $translator;
    private $security;

    public function __construct(TranslatorInterface $translator, Security $security)
    {
        $this->translator = $translator;
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $hasInvoices = !empty($this->security->getUser()->getInvoices()->getValues());
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
            ->add('street', null, [
                'label' => $this->translator->trans('company.street', [], 'labels'),
            ])
            ->add('stNumber', null, [
                'label' => $this->translator->trans('company.streetNumber', [], 'labels'),
            ])
            ->add('accountNumber', null, [
                'label' => $this->translator->trans('company.accountNumber', [], 'labels'),
            ])
            ->add('baseNumber', null, [
                'label' => $hasInvoices ? !$hasInvoices : $this->translator->trans('company.user.baseNumber', [], 'labels'),
                'required' => false,
                'attr' => [
                    'hidden' => $hasInvoices,
                    'disable' => $hasInvoices,
                    ],
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
            'data_class' => Users::class,
        ]);
    }
}
