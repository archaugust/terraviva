<?php
namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function adminMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttributes(['class' => 'nav navbar-nav side-nav']);

        $menu->addChild('Dashboard', array('route' => 'admin'))
            ->setAttribute('icon', 'fa fa-fw fa-dashboard');

        $menu->addChild('Home Page', array('route' => 'admin_homepage'))
            ->setAttribute('icon', 'fa fa-fw fa-home');

        $menu->addChild('The Garden', array('uri' => '#', 'attributes' => array('icon' => 'fa fa-fw fa-arrows-v', 'collapse' => true, 'collapseData' => 'subTheGarden')))
            ->setAttribute('icon', 'fa fa-fw fa-folder');
        $menu['The Garden']->addChild('Plants', array('route' => 'admin_product_category', 'routeParameters' => array('id' => 4)));
        $menu['The Garden']->addChild('Garden Tools', array('route' => 'admin_product_category', 'routeParameters' => array('id' => 6)));
        $menu['The Garden']->addChild('Plant Care', array('route' => 'admin_product_category', 'routeParameters' => array('id' => 8)));
        $menu['The Garden']->addChild('Garden Info', array('route' => 'admin_content', 'routeParameters' => array('cat_id' => 1)));
        $menu['The Garden']->addChild('Garden Products', array('route' => 'admin_product_category', 'routeParameters' => array('id' => 36)));
        $menu['The Garden']->setChildrenAttributes(array('class' => 'collapse','id' => 'subTheGarden'));
        
        $menu->addChild('Home & Gift', array('uri' => '#', 'attributes' => array('icon' => 'fa fa-fw fa-arrows-v', 'collapse' => true, 'collapseData' => 'subHomeGift')))
        	->setAttribute('icon', 'fa fa-fw fa-folder');
        $menu['Home & Gift']->addChild('For The Table', array('route' => 'admin_product_category', 'routeParameters' => array('id' => 42)));
        $menu['Home & Gift']->addChild('For The Home', array('route' => 'admin_product_category', 'routeParameters' => array('id' => 43)));
        $menu['Home & Gift']->addChild('Furniture', array('route' => 'admin_product_category', 'routeParameters' => array('id' => 44)));
        $menu['Home & Gift']->addChild('Kids', array('route' => 'admin_product_category', 'routeParameters' => array('id' => 45)));
        $menu['Home & Gift']->addChild('Faux Flowers', array('route' => 'admin_product_category', 'routeParameters' => array('id' => 46)));
        $menu['Home & Gift']->addChild('Body Care', array('route' => 'admin_product_category', 'routeParameters' => array('id' => 47)));
        $menu['Home & Gift']->setChildrenAttributes(array('class' => 'collapse','id' => 'subHomeGift'));

        $menu->addChild('Design', array('route' => 'admin_content_edit', 'routeParameters' => array('id' => 1)))
        	->setAttribute('icon', 'fa fa-fw fa-file-text');

       	$menu->addChild('Contact', array('uri' => '#', 'attributes' => array('icon' => 'fa fa-fw fa-arrows-v', 'collapse' => true, 'collapseData' => 'subContact')))
        	->setAttribute('icon', 'fa fa-fw fa-folder');
       	$menu['Contact']->addChild('Edit Page', array('route' => 'admin_content_edit', 'routeParameters' => array('id' => 3)));
       	$menu['Contact']->addChild('View Messages', array('route' => 'admin_contact'));
       	$menu['Contact']->setChildrenAttributes(array('class' => 'collapse','id' => 'subContact'));
       		 
       	 $menu->addChild('Gift Vouchers', array('route' => 'admin_content_edit', 'routeParameters' => array('id' => 2)))
        	->setAttribute('icon', 'fa fa-fw fa-file-text');
        	 
        $menu->addChild('Garden Calendar', array('route' => 'admin_calendar'))
        	->setAttribute('icon', 'fa fa-fw fa-calendar');

        $menu->addChild('Subscribers', array('route' => 'admin_subscribers'))
        	->setAttribute('icon', 'fa fa-fw fa-envelope');
        	 
        $menu->addChild('Pages', array('uri' => '#', 'attributes' => array('icon' => 'fa fa-fw fa-arrows-v', 'collapse' => true, 'collapseData' => 'subContent')))
            ->setAttribute('icon', 'fa fa-fw fa-file-text');
        $menu['Pages']->addChild('Page Categories', array('route' => 'admin_content_category'));
        $menu['Pages']->addChild('All Pages', array('route' => 'admin_content'));
        $menu['Pages']->addChild('Add New Page', array('route' => 'admin_content_add'));
        $menu['Pages']->setChildrenAttributes(array('class' => 'collapse','id' => 'subContent'));

        $menu->addChild('Shop', array('uri' => '#', 'attributes' => array('icon' => 'fa fa-fw fa-arrows-v', 'collapse' => true, 'collapseData' => 'subShop')))
            ->setAttribute('icon', 'fa fa-fw fa-shopping-cart');
        $menu['Shop']->addChild('Main Categories', array('route' => 'admin_product_category', 'routeParameters' => array('main' => 1)));
        $menu['Shop']->addChild('All Products', array('route' => 'admin_product_item'));
        $menu['Shop']->setChildrenAttributes(array('class' => 'collapse','id' => 'subShop'));

        $menu->addChild('Users', array('route' => 'admin_users'))
            ->setAttribute('icon', 'fa fa-fw fa-user');
        return $menu;
    }

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttributes(['class' => 'nav navbar-nav navbar-custom','id' => 'main_menu']);

        $em = $this->container->get('doctrine');
        $repository = $em->getRepository('AppBundle:MenuItem');
        $result = $repository->findBy(array('menuId' => 1, 'parentId' => 0), array('sortOrder' => 'ASC'));
        foreach ($result as $item) {
            $title = $item->getTitle();

            $children = $repository->findBy(array('parentId' => $item->getId()), array('sortOrder' => 'ASC'));
            if (empty($children))
            {
                $pageType = $item->getPageType();
                switch ($pageType)
                {
                    case  'text-separator':
                        $array = array('uri' => '#');
                        break;
                    case 'content':
                        $array = array(
                            'route' => '_custom_route',
                            'routeParameters' => array('alias' => $item->getAlias()),
                        );
                        break;
                    case 'route':
                        $array = array('route' => $item->getPageTypeId());
                        break;
                    case 'url':
                    default:
                        $array = array('uri' => $item->getPageTypeId());
                        break;
                }
                $menu->addChild($title, $array)
                    ->setAttribute('id', $item->getAlias());
            }
            else {
                $menu->addChild($title, array(
                    'route' => '_custom_route',
                    'routeParameters' => array('alias' => $item->getAlias(),
                    )))
                    ->setAttribute('class', 'dropdown')
                    ->setLinkAttribute('class', 'dropdown-toggle')
                    ->setLinkAttribute('data-toggle', 'dropdown');

                foreach ($children as $child)
                {
                    $pageType = $child->getPageType();
                    switch ($pageType)
                    {
                        case  'text-separator':
                            $array = array('uri' => '#');
                            break;
                        case 'content':
                            $array = array(
                                'route' => '_custom_route',
                                'routeParameters' => array('alias' => $item->getAlias() .'/'. $child->getAlias()),
                            );
                            break;
                        case 'properties-category':
                            $array = array(
                                'route' => 'properties_category',
                                'routeParameters' => array(
                                    'category' => $child->getPageTypeId()
                                )
                            );
                            break;
                        case 'route':
                            $array = array('route' => $child->getPageTypeId());
                            break;
                        default:
                        case 'url':
                            $array = array('uri' => $child->getPageTypeId());
                            break;
                    }

                    $menu[$title]->addChild($child->getTitle(), $array);
                }
                $menu[$title]->setChildrenAttributes(array('class' => 'dropdown-menu'));
            }
        }
        return $menu;
    }
}