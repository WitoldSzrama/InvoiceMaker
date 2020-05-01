<?php

namespace App\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
