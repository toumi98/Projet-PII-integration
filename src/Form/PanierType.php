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

class PanierType extends AbstractType
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
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('quantite', IntegerType::class, [ // ✅ Correction ici
                'label' => 'Quantité',
                'attr' => ['min' => 1],
            ])
            ->add('total', IntegerType::class, [ // ✅ Correction ici
                'label' => 'Total',
                'attr' => ['readonly' => true], // Empêcher la modification manuelle
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Panier::class,
        ]);
    }
}
