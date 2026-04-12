<?php

namespace App\Form;

use App\Entity\ProduitTaille;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitTailleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('taille', ChoiceType::class, [
                'choices' => [
                    'XS'  => 'XS',
                    'S'   => 'S',
                    'M'   => 'M',
                    'L'   => 'L',
                    'XL'  => 'XL',
                    'XXL' => 'XXL',
                ],
            ])
            ->add('stock', IntegerType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProduitTaille::class,
        ]);
    }
}