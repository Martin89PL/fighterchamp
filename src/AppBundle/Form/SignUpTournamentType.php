<?php

namespace AppBundle\Form;

use AppBundle\Entity\Ruleset;
use AppBundle\Entity\SignUpTournament;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SignUpTournamentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('formula', ChoiceType::class, [
                'choices'  => [
                    'A' => 'A',
                    'B' => 'B',
                    'C' => 'C'
                ]])
            ->add('trainingTime', IntegerType::class)
            ->add('weight', ChoiceType::class, [
                'choices'  => $options['trait_choices'],
                'choice_value' => function ($ruleset = null) {
                    return $ruleset ? $ruleset : '';
                },
                'choice_label' => function (Ruleset $ruleset) {
                    return $ruleset->getWeight();
                }
                ])
            ->add('isLicence', CheckboxType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => SignUpTournament::class,
            'trait_choices' => null,
            'user_id' => null,
            'csrf_protection'   => false,
        ));
    }
}
