<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class ProductCalendarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', CKEditorType::class, array(
                'label' => 'Intro Text'
            ))
            ->add('items', CollectionType::class, array(
                'entry_type'    => ProductCalendarItemType::class,
                'by_reference' => false,
                'allow_add'     => true,
                'allow_delete' => true,
                'label' => 'Calendars',
            ))
            ->add('save', SubmitType::class, array('label' => 'Save'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ProductCalendar',
        ));
    }
}