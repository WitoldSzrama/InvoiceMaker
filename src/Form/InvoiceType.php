<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Invoice;
use App\Entity\Product;
use App\Repository\CompanyRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

class InvoiceType extends AbstractType
{

    private $security;
    private $translator;
    private $em;
    private $productRepository;

    public function __construct(Security $security, TranslatorInterface $translator, EntityManagerInterface $em, ProductRepository $productRepository)
    {
        $this->security = $security;
        $this->translator = $translator;
        $this->em = $em;
        $this->productRepository = $productRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('forCompany', EntityType::class, [
                'class' => Company::class,
                'required' => true,
                'label' => $this->translator->trans('invoice.company', [], 'labels'),
                'query_builder' => function (CompanyRepository $cr) {
                    return $cr->getQueryBuilderByUser($this->security->getUser());
                }
            ])
            ->add('salesDate', DateType::class, [
                'widget' => 'single_text',
                'label' => $this->translator->trans('invoice.salesDate', [], 'labels'),
            ])
            ->add('payTo', DateType::class, [
                'widget' => 'single_text',
                'label' => $this->translator->trans('invoice.payTo', [], 'labels'),
            ])
            ->add('comment',null, [
                'label' => $this->translator->trans('invoice.comment', [], 'labels')
            ])
            ->add('products', CollectionType::class, [
                'label' => $this->translator->trans('invoice.products', [], 'labels'),
                'entry_type' => ProductType::class,
                'entry_options' => [
                    'required' => true,
                ],
                'allow_add' => true,
                'by_reference' => false,
            ])
            ->add('existProduct', EntityType::class, [
                'mapped' => false,
                'required' => false,
                'placeholder' => $this->translator->trans('invoice.products.choose', [], 'labels'),
                'class' => Product::class,
                'query_builder' => function (ProductRepository $pr) {
                    return $pr->queryProductByUser($this->security->getUser());
                },
                'label' => false,
                // 'choice_label' => 'name',
                'required' =>false,
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event){
                if(array_key_exists('products', $event->getData())) {
                    $products = $event->getData()['products'];
                    foreach ($products as $product) {
                        if (is_numeric($product['id']) && $oldProduct = $this->productRepository->findOneBy(['id' => $product['id']])) {
                            $oldProduct->setUser(null);
                        }
                    }
                } else {
                    $event->getForm()->get('products')->addError(
                        new FormError($this->translator->trans('invoice.error', [], 'labels'))
                    );
                }
            })
        ;

        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
        ]);
    }
}
