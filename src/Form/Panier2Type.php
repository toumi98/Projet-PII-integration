<?php

namespace App\Form;

use App\Entity\Panier;
use App\Entity\ProduitStore;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class Panier2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void

    {
        $builder 
            ->add('id_client', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('id_produit', EntityType::class, [
                'class' => ProduitStore::class,
                'choice_label' => function (ProduitStore $produit) {
                    return sprintf('%s - %s (%.2f €)', $produit->getNom(), $produit->getDescription(), $produit->getPrix());
                },
                'expanded' => false, // Dropdown list
                'multiple' => true, // Permettre la sélection multiple
                'by_reference' => false, // Important pour ManyToMany
            ])
            
            ->add('quantite', IntegerType::class, [
                'label' => 'Quantité',
                'attr' => ['min' => 1],
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Panier::class,
        ]);
    }
}
