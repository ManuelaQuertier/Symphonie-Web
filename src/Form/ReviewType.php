<?php

namespace App\Form;

use App\Entity\Review;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('author', TextType::class, ['label' => 'Votre Nom'])
            ->add('program', null, ['choice_label' => 'title', 'label' => 'Série'])
            ->add('reviewText', TextareaType::class, ['label' => 'Votre Avis'])
            ->add('date', DateType::class, ['label' => 'Date d\'ajout'])
            ->add('note', IntegerType::class, ['label' => 'Votre Note sur 10'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
