<?php

namespace App\Form;

use App\Entity\Program;
use App\Entity\Actor;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProgramType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Nom'])
            ->add('summary', TextareaType::class, ['label' => 'Synopsis'])
            ->add('poster', TextType::class,['label' => 'Image'])
            ->add('country', TextType::class, ['label' => 'Pays'])
            ->add('year', IntegerType::class, ['label' => 'Année de sortie'])
            ->add('Category', null, ['choice_label' => 'name', 'label' => 'Genre'])
            ->add('actors', EntityType::class, [
                'class' => Actor::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
                'label' => 'Casting'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Program::class,
        ]);
    }
}
