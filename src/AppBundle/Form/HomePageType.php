<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HomePageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('main_title', TextType::class, array(
            	'label' => 'Title',
            	'required' => true
            ))
            ->add('main_subtitle', TextType::class, array(
            		'label' => 'Subtitle',
            		'required' => true
            ))
            ->add('main_url', TextType::class, array(
            		'label' => 'Link URL',
            		'required' => true
            ))
            ->add('main_img', FileType::class, array(
            		'data_class' => null,
            		'required' => false,
            		'label' => 'Image',
            ))
            
            ->add('thumb_1_title', TextType::class, array(
            	'label' => 'Title',
            	'required' => true
            ))
            ->add('thumb_1_subtitle', TextType::class, array(
            		'label' => 'Subtitle',
            		'required' => true
            ))
            ->add('thumb_1_url', TextType::class, array(
            		'label' => 'Link URL',
            		'required' => true
            ))
            ->add('thumb_1_img', FileType::class, array(
            		'data_class' => null,
            		'required' => false,
            		'label' => 'Image',
            ))

            ->add('thumb_2_title', TextType::class, array(
            		'label' => 'Title',
            		'required' => true
            ))
            ->add('thumb_2_subtitle', TextType::class, array(
            		'label' => 'Subtitle',
            		'required' => true
            ))
            ->add('thumb_2_url', TextType::class, array(
            		'label' => 'Link URL',
            		'required' => true
            ))
            ->add('thumb_2_img', FileType::class, array(
            		'data_class' => null,
            		'required' => false,
            		'label' => 'Image',
            ))
            
            ->add('thumb_3_title', TextType::class, array(
            		'label' => 'Title',
            		'required' => true
            ))
            ->add('thumb_3_subtitle', TextType::class, array(
            		'label' => 'Subtitle',
            		'required' => true
            ))
            ->add('thumb_3_url', TextType::class, array(
            		'label' => 'Link URL',
            		'required' => true
            ))
            ->add('thumb_3_img', FileType::class, array(
            		'data_class' => null,
            		'required' => false,
            		'label' => 'Image',
            ))

            ->add('content', CKEditorType::class, array(
            		'label' => 'Main Body Text',
            		'empty_data' => ''
            ))
            
            ->add('save', SubmitType::class, array('label' => 'Save Changes'))
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\HomePage',
        ));
    }
}