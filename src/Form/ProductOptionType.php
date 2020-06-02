<?php

namespace App\Form;

use App\Entity\ProductOption;
use App\Form\DataTransformer\OptionValuesToStringTransformer;
use App\Repository\ProductOptionValueRepository;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductOptionType extends AbstractType
{
    private $repository;

    public function __construct(ProductOptionValueRepository $repository)
    {
        $this->repository = $repository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'resource.product.option.name',
                'attr' => ['autofocus' => true],
                'help' => 'resource.product.option.name.help',
            ])
            ->add('values', TextType::class, [
                'label' => 'resource.product.option.value',
                'help' => 'resource.product.option.values.help',
            ])
        ;

        $builder->get('values')
            ->addModelTransformer(new CollectionToArrayTransformer(), true)
            ->addModelTransformer(new OptionValuesToStringTransformer($this->repository), true)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductOption::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
