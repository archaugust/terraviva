<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class ProductItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'required' => true
            ))
            ->add('subtitle', TextType::class, array(
                'required' => false,
                'empty_data' => ''
            ))
            ->add('code', TextType::class, array(
                'required' => true
            ))
            ->add('category', EntityType::class,
                array(
                    'class' => 'AppBundle:ProductCategory',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->where('c.publish <> 0 AND c.parent_id <> 1')
                            ;
                    },
                    'choice_label' => function ($category) {
                        $prefix = '';
                        $parent = $category->getParent();
                        while ($parent->getId() != 1) {
                            $prefix .= '--';
                            $parent = $parent->getParent();
                        }
                        return $prefix .' '. $category->getTitle();
                    },
                    'choice_value' => 'id',
                    'group_by' => 'parent.title',
                    'placeholder' => 'Select Category',
                    'label' => 'Category'
                )
            )
            ->add('description_short', TextareaType::class, array(
            		'label' => 'Short Description',
            		'empty_data' => ''
            ))
            ->add('description', CKEditorType::class, array(
                'empty_data' => ''
            ))
            ->add('related_tags', TextareaType::class, array(
            		'required' => false,
            		'label' => 'Related Tags',
            		'attr' => array(
            				'class' => 'tags',
            		),
            		'empty_data' => ''
            ))
            ->add('publish', CheckboxType::class, array(
            		'required' => false
            ))
            ->add('featured', CheckboxType::class, array(
            		'label' => 'Feature as Special',
            		'required' => false
            ))
            ->add('images', CollectionType::class, array(
                'entry_type'    => ProductItemImageType::class,
                'by_reference' => false,
                'allow_add'     => true,
                'allow_delete' => true,
                'label' => ' ',
            ))
            ->add('save', SubmitType::class, array('label' => 'Save Item'))
            ->add('saveAndAdd', SubmitType::class, array('label' => 'Save and Add'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ProductItem',
            'allow_extra_fields' => true
        ));
    }
}