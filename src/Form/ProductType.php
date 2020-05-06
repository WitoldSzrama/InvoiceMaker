<?php

namespace App\Form;

use App\Entity\Product;
use App\Services\ProductFactory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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

    public function __construct(TranslatorInterface $translator, ProductFactory $productFactory)
    {
        $this->translator = $translator;
        $this->productFactory = $productFactory;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
