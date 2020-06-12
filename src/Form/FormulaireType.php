<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormulaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('libelle', TextType::class, array('label'=>'Libelle du produit', 'attr'=>array('require'=>'require','class'=>'form-control')))
        ->add('qtStock', TextType::class, array('label'=>'Quantité en stock', 'attr'=>array('require'=>'require','class'=>'form-control')))
        ->add('Valider', SubmitType::class, array('attr'=>array('class'=>'btn btn-success')));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
