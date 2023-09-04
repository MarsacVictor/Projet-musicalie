<?php

namespace App\Form;

use App\Entity\Artiste;
use App\Entity\Departement;
use App\Entity\Festival;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FestivalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('date')
            ->add('dateCreation')
            ->add('lieu')
            ->add('artiste', EntityType::class, ['class' => Artiste::class, 'multiple' => true, "choice_label"=>'nom', 'required' =>true])
            ->add('departement', EntityType::class, ['class' => Departement::class, "choice_label"=>'nom'])
            ->add('affiche')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Festival::class,
        ]);
    }
}
