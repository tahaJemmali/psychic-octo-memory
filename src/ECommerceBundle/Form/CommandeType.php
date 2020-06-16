<?php

namespace ECommerceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('paiment',ChoiceType::class,array(
            'multiple'=>false,
            'expanded' => false,
            'choices'=>array(
                'Paiment par chéque' => 'Paiement par Chéque',
                'Paiement en Especes' => 'Paiement en Espèces',
            )
        ))->add('etat',ChoiceType::class,array(
            'multiple'=>false,
            'expanded' => false,
            'choices'=>array(
                'Nouvelle Commande' => 'Nouvelle Commande',
                'En cours de livraison' => 'En cours de livraison',
                'Annulee' => 'Annulee',
            )
        ))->add('date',DateType::class,array('disabled' => 'true'))->add('description',TextareaType::class);
            //->add('commande_user');
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ECommerceBundle\Entity\Commande'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ecommercebundle_commande';
    }


}
