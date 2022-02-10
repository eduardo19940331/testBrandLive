<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\GroupCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $optionsGroup = $options['groupsc'];
        $builder
            ->add('id', HiddenType::class, [
                'required' => false,
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Nombre',
                'invalid_message' => 'asdasdsd',
                'required' => true
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Apellido',
                'required' => true
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Observaciones',
                'required' => true
            ])
            ->add('clientGroup', ChoiceType::class, [
                'choices' => $optionsGroup,
                'label' => 'Grupo del Cliente',
                'multiple' => true,
                'expanded' => true,
                'required' => true,
                'choice_value' => 'name',
                'choice_label' => function (GroupCategory $category) {
                    return $category ? $category->getName() : '';
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
            'groupsc' => [],
            'id' => 0
        ]);
    }
}
