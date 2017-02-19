<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProductCalendarItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'required' => true,
                'label' => 'Calendar Name',
            ))
            ->add('bg_color', TextType::class, array(
            	'required' => false,
            	'label' => false,
            	'attr' => array('class' => 'colorpicker')
            ))
            ->add('filename', FileType::class, array(
                'data_class' => null,
                'required' => false,
                'label' => 'Banner Image',
            ));
        for ($m = 1; $m <= 12; $m++)
        	$builder
	        	->add(strtolower(date('F', mktime(0,0,0,$m,1,1))), CKEditorType::class, array(
	                'empty_data' => ''
	            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ProductCalendarItem',
        ));
    }
}