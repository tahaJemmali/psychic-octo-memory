<?php

namespace ECommerceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /*->add('refrence',TextType::class, ['attr' =>['id'=>'Reftext']] )*/
            ->add('refrence',TextType::class,array('attr' => array('id' => 'Reftext')))
            ->add('name')->add('category',ChoiceType::class,array(
                'multiple'=>false,
                'expanded' => false,
                'choices'=>array(
                    /*
                'cat'=>function($key, $val, $index) {
                    $disabled = true;
                    //$val='azeaze';
                    // set disabled to true based on the value, key or index of the choice...
                    return $disabled ? ['disabled' => 'disabled'] : [];
                },*/
                    'Velo' => 'Velo',
                    'Accessoire' => 'Accessoire',
                    'Piece de rechange' => 'Piece de rechange',
                )
            ))->add('price')->add('stock')
            /*->add('date',DateType::class)*/
            ->add('description',TextareaType::class);
            /*->add('AddProduct',SubmitType::class,
                ['label'=> 'Add Product']);*/

       /* 'attr' => array(
        'id' => 'some_id',
        'class' => 'some_class'
    )*/


    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ECommerceBundle\Entity\Product'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ECommerceBundle_product';
    }


}
