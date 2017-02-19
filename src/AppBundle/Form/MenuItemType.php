<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class MenuItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        global $menu_id, $id;
        $id = $options['data']->getId();
        if (empty($id))
            $id = 0;
        $menu_id = $options['data']->getMenuId();

        $builder
            ->add('title', TextType::class, array(
                'required' => true
            ))
            ->add('page_type',EntityType::class,
                array(
                    'class' => 'AppBundle:PageType',
                    'choice_label' => 'pageTypeTitle',
                    'choice_value' => 'pageType',
                    'placeholder' => 'Choose one'
                )
            )
            ->add('parent_id',EntityType::class,
                array(
                    'class' => 'AppBundle:MenuItem',
                    'query_builder' => function(EntityRepository $er) {
                        global $menu_id, $id;
                        return $er->createQueryBuilder('m')
                            ->where('m.id <> '. $id .' AND m.menuId = '. $menu_id .' AND m.parentId = 0')
                            ;
                    },
                    'choice_label' => 'title',
                    'choice_value' => 'id',
                    'placeholder' => 'Root',
                    'data' => $id,
                    'required' => false
                )
            )
            ->add('page_type_id', TextType::class)
            ->add('sort_order',HiddenType::class)
            ->add('save', SubmitType::class, array('label' => 'Save Menu Item'))
            ->add('saveAndAdd', SubmitType::class, array('label' => 'Save and Add'));
    }
}