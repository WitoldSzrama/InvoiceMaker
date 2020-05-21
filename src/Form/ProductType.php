<?php

namespace App\Form;

use App\Entity\Product;
use App\Repository\UserRepository;
use App\Services\ProductFactory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductType extends AbstractType
{
    private $translator;
    private $productFactory;
    private $security;
    private $user;

    public function __construct(TranslatorInterface $translator, ProductFactory $productFactory, Security $security, UserRepository $user)
    {
        $this->translator = $translator;
        $this->productFactory = $productFactory;
        $this->security = $security;
        $this->user = $user;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', HiddenType::class, [
            ])
            ->add('name', null, [
                'label' => $this->translator->trans('product.name', [], 'labels'),
                'row_attr' => ['class' => 'col-12']
            ])
            ->add('quantity', null, [
                'label' => $this->translator->trans('product.quantity', [], 'labels'),
                'row_attr' => ['class' => 'col-2']
            ])
            ->add('netValue', MoneyType::class, [
                'label' => $this->translator->trans('product.netValue', [], 'labels'),
                'currency' => $this->productFactory::CURRENCY,
                'row_attr' => ['class' => 'col-4']
            ])
            ->add('grossValue', MoneyType::class, [
                'label' => $this->translator->trans('product.grossValue', [], 'labels'),
                'currency' => $this->productFactory::CURRENCY,
                'row_attr' => ['class' => 'col-4']
            ])
            ->add('vat', ChoiceType::class, [
                'choices' => $this->productFactory->getVatChoices($this->security->getUser()),
                'row_attr' => ['class' => 'col-2']
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $product = $event->getData();
                $product->setUser($this->security->getUser());
                $product->setCurrency($this->security->getUser()->getBaseCurrency());
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
