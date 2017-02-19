<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FM\ElfinderBundle\Form\Type\ElFinderType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class ContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'required' => true
            ))
            ->add('content', CKEditorType::class, array(
            		'required' => false,
            		'empty_data' => ''
            ))
            ->add('category', EntityType::class, array(
            		'class' => 'AppBundle:ContentCategory',
            		'query_builder' => function(EntityRepository $er) {
            			return $er->createQueryBuilder('c');
            		},
            		'choice_label' => 'title',
            		'choice_value' => 'id',
            		'placeholder' => 'Uncategorised',
            		'label' => 'Page Category',
            		'required' => false
            ))
            ->add('image', ElFinderType::class, array(
            		'instance' => 'form', 
            		'enable' => true,
            		'required' => false,
            		'empty_data' => ''
            ))
            ->add('save', SubmitType::class, array('label' => 'Save'))
            ->add('saveAndAdd', SubmitType::class, array('label' => 'Save and Add'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Content',
        ));
    }
}