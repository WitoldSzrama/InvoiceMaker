<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Invoice;
use App\Entity\Product;
use App\Repository\CompanyRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
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
            ->add('forCompany', null, [
                'label' => $this->translator->trans('invoice.company', [], 'labels')
            ])
            ->add('comment',null, [
                'label' => $this->translator->trans('invoice.comment', [], 'labels')
            ])
            ->add('products', CollectionType::class, [
                'label' => $this->translator->trans('invoice.products', [], 'labels'),
                'entry_type' => ProductType::class,
                'allow_add' => true,
            ])
            ->add('existProduct', EntityType::class, [
                'mapped' => false,
                'placeholder' => $this->translator->trans('invoice.products.choose', [], 'labels'),
                'class' => Product::class,
                'query_builder' => function (ProductRepository $pr) {
                    return $pr->queryProductByUser($this->security->getUser());
                },
                'label' => false,
                'choice_label' => 'name',
                'required' =>false,
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event){
                $products = $event->getData()['products'];
                foreach ($products as $product) {
                    if ($product['id']) {
                        $oldProduct = $this->productRepository->findOneBy(['id' => $product['id']]);
                        $this->em->remove($oldProduct);
                    }
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
