<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, array(
                'label' => 'Codigo',
                'attr' => array('class' => 'input-group'),
                'required' => true))

            ->add('name', TextType::class, array(
                'label' => 'Nombre del producto',
                'attr' => array('class' => 'input-group'),
                'required' => true))

            ->add('description', TextType::class, array(
                'label' => 'Descripcion',
                'attr' => array('class' => 'input-group'),
                'required' => true))

            ->add('brand', TextType::class, array(
                'label' => 'Marca',
                'attr' => array('class' => 'input-group'),
                'required' => true))

            ->add('price', MoneyType::class, array(
                'label' => 'Precio',
                'attr' => array('class' => 'input-group'),
                'required' => true))

            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ])

            ->add('Guardar', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            /* 'category' => false, */
        ]);
    }
}
