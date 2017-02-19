<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class ProductCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        global $conditions;
        $id = $options['data']->getParentId();
        if (is_null($id) || $id == 0)
            $conditions = 'c.id <> 0';
        else 
        	$conditions = 'c.id = '. $id .' OR c.parent_id = '. $id;

        $builder
            ->add('title', TextType::class, array(
                'required' => true
            ))
            ->add('parent', EntityType::class,
                array(
                    'class' => 'AppBundle:ProductCategory',
                    'query_builder' => function(EntityRepository $er) {
                        global $conditions;
                        return $er->createQueryBuilder('c')
                            ->where($conditions)
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
                    'label' => 'Parent Category'
                )
            )
            ->add('description', TextareaType::class, array(
            	'required' => false,
                'empty_data' => ''
            ))
            ->add('image', FileType::class, array(
                'data_class' => null,
                'required' => false,
            ))
            ->add('filters', CollectionType::class, array(
                'entry_type'    => ProductFilterType::class,
                'by_reference' => false,
                'allow_add'     => true,
                'allow_delete' => true,
                'label' => ' ',
            ))
            ->add('plant_finder', ProductPlantFinderType::class, array(
            		'label' => ' '
            ))
            ->add('category_info', ProductCategoryInfoType::class, array(
            		'label' => ' '
            ))
            ->add('ordering',HiddenType::class, array(
                'empty_data' => 0
            ))
            ->add('save', SubmitType::class, array('label' => 'Save Category'))
            ->add('saveAndAdd', SubmitType::class, array('label' => 'Save and Add'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ProductCategory',
        ));
    }
}