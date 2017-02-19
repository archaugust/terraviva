<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductPlantFinderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('soil_moisture', ChoiceType::class, array(
                'choices' => array(
                    'Quite Dry' => 'Quite Dry',
                    'Normal' => 'Normal',
                    'Quite Wet' => 'Quite Wet'
                ),
                'expanded' => true,
                'multiple' => false,
            	'required' => true
            ))
            ->add('soil_type', ChoiceType::class, array(
                'choices' => array(
                    'Soft/Sandy' => 'Soft/Sandy',
                    'Normal' => 'Normal',
                    'Hard/Clay' => 'Hard/Clay'
                ),
                'expanded' => true,
                'multiple' => false,
            ))
            ->add('light', ChoiceType::class, array(
                'choices' => array(
                    'Full Shade' => 'Full Shade',
                    'Part Shade' => 'Part Shade',
                    'Full Sun' => 'Full Sun'
                ),
                'expanded' => true,
                'multiple' => false,
            ))
            ->add('wind_exposure', ChoiceType::class, array(
                'choices' => array(
                    'Sheltered' => 'Sheltered',
                    'Part Sheltered' => 'Part Sheltered',
                    'Exposed' => 'Exposed'
                ),
                'expanded' => true,
                'multiple' => true,
                'label' => 'Wind/Exposure',
            ))
            ->add('plant_height', ChoiceType::class, array(
                'choices' => array(
                    '0-50cm' => '0-50cm',
                    '50cm-1m' => '50cm-1m',
                    '1m-3m' => '1m-3m',
                    '3m+' => '3m+',
                ),
                'expanded' => true,
                'multiple' => true,
                'label' => 'Plant Height',
            ))
            ->add('custom_height', TextType::class, array(
            		'label' => 'Custom Height',
            		'required' => false,
            		'empty_data' => '',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ProductPlantFinder',
        ));
    }
}