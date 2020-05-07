<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\User;
use App\Services\ProductFactory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductType extends AbstractType
{
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var ProductFactory
     */
    private $productFactory;
    /**
     * @var Security
     */
    private $security;

    public function __construct(TranslatorInterface $translator, ProductFactory $productFactory, Security $security)
    {
        $this->translator = $translator;
        $this->productFactory = $productFactory;
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', HiddenType::class, [

            ])
            ->add('name', null, [
                'label' => $this->translator->trans('product.name', [], 'labels')
            ])
            ->add('quantity', null, [
                'label' => $this->translator->trans('product.quantity', [], 'labels')
            ])
            ->add('netValue', null, [
                'label' => $this->translator->trans('product.netValue', [], 'labels')
            ])
            ->add('grossValue', null, [
                'label' => $this->translator->trans('product.grossValue', [], 'labels')
            ])
            ->add('vat', ChoiceType::class, [
                'choices' => $this->productFactory->getVatChoices(),
            ])
            ->add('forPeriod', null, [
                'label' => $this->translator->trans('product.forPeriod', [], 'labels')
            ])
            ->add('currency',HiddenType::class, [
                'data' => $this->productFactory::CURRENCY,
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'data' => $this->security->getUser()->getId(),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
