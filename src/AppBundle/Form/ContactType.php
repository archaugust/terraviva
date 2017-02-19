<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fname', TextType::class, array('label' => 'First Name*'))
            ->add('lname', TextType::class, array(
            		'label' => 'Last Name',
            		'required' => false,
            		'empty_data' => ''
            ))
            ->add('email', EmailType::class, array('label' => 'Email*'))
            ->add('phone', TextType::class, array(
            		'label' => 'Phone',
            		'required' => false,
            		'empty_data' => ''
            ))
            ->add('message', TextareaType::class, array('label' => 'Message*'))
            ->add('send', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Contact',
        ));
    }
}