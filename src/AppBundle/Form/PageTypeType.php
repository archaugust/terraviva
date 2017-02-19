<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PageTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('page_type_title', TextType::class, array(
                'required' => true
            ))
            ->add('page_type', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Save PageType'));
    }
}