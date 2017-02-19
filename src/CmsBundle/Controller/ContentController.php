<?php
namespace CmsBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Filesystem;
use AppBundle\Entity\Content;
use AppBundle\Entity\ContentCategory;
use AppBundle\Form\ContentType;
use AppBundle\Form\ContentCategoryType;
use AppBundle\Entity\Menu;
use AppBundle\Entity\MenuItem;
use AppBundle\Form\MenuItemType;
use AppBundle\Form\MenuType;
use JasonGrimes\Paginator;

class ContentController extends Controller
{
	
	/**
     * @Route("/admin/menu/", name="admin_menu")
     */
    public function adminMenu(Request $request)
    {
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Menu');

        if ($request->request->get('submit')) {
            $em = $this->getDoctrine()->getManager();
            $deleteList = $request->request->get('delete');
            if (count($deleteList) > 0) {
                foreach ($deleteList as $item)
                {
                    $menu = $repository->find($item);
                    $em->remove($menu);
                    $em->flush();
                }

                $this->addFlash(
                    'info',
                    'Menu(s) deleted.'
                );
            }
        }

        $sortBy = $request->query->get('sort');
        if ($sortBy == '')
            $sortBy = 'id';
        $order = $request->query->get('order');
        if ($order == '')
            $order = 'ASC';
        $content = $repository->findBy([],array($sortBy => $order));

        return $this->render('admin/menu.html.twig', array(
            'contents' => $content,
            'header' => 'Menu',
        ));
    }

    /**
     * @Route("admin/menu/add/", name="admin_menu_add")
     */
    public function adminAddMenu(Request $request)
    {
        $menu = new Menu();
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $menu
                ->setAlias($this->get('app.misc_functions')->slug($menu->getTitle()))
            ;

            $em = $this->getDoctrine()->getManager();
            $em->persist($menu);
            $em->flush();

            $this->addFlash(
                'info',
                'Menu added.'
            );

            $nextAction = $form->get('saveAndAdd')->isClicked()
                ? 'admin_menu_add'
                : 'admin_menu';

            return $this->redirectToRoute($nextAction);
        }

        return $this->render('admin/menu_form.html.twig', array(
            'form' => $form->createView(),
            'header' => 'Add New Menu',
        ));
    }

    /**
     * @Route("/admin/menu/edit/{id}/", name="admin_menu_edit", defaults={"id": 1})
     */
    public function adminEditMenu(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $menu = $em->getRepository('AppBundle:Menu')->find($id);
        if (!$menu) {
            throw $this->createNotFoundException(
                'No menu found for id '.$id
            );
        }

        $form = $this->createForm(MenuType::class, $menu);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $menu
                ->setAlias($this->get('app.misc_functions')->slug($menu->getTitle()))
            ;

            $em->persist($menu);
            $em->flush();

            $this->addFlash(
                'info',
                'Menu modified.'
            );
        }

        return $this->render('admin/menu_form.html.twig', array(
            'form' => $form->createView(),
            'header' => 'Edit Menu',
        ));
    }

    /**
     * @Route("/admin/content-category/", name="admin_content_category")
     */
    public function adminContentCategory(Request $request)
    {
    	$repository = $this->getDoctrine()
    		->getRepository('AppBundle:ContentCategory');
    
    	if ($request->request->get('submit')) {
    		$em = $this->getDoctrine()->getManager();
    		$deleteList = $request->request->get('delete');
    		if (count($deleteList) > 0) {
    			foreach ($deleteList as $item)
    			{
    				$content = $repository->find($item);
    				$em->remove($content);
    				$em->flush();
    				
    				// unset any articles under category
    				$em->createQuery('
    					UPDATE c 
    						AppBundle:Content c
    						SET cat_id = 0
    						WHERE cat_id = :category')
    					->setParameter('category', $item)
    					->getResult();
    			}
    
    			$this->addFlash(
    					'info',
    					'Page Category page(s) deleted.'
				);
    		}
    	}
    
    	$sortBy = $request->query->get('sort');
    	if ($sortBy == '')
    		$sortBy = 'id';
    		$order = $request->query->get('order');
   		if ($order == '')
   			$order = 'ASC';
   			$content = $repository->findBy([],array($sortBy => $order));
    
		return $this->render('admin/content_category.html.twig', array(
			'contents' => $content,
			'header' => 'Page Categories',
		));
    }
    
    /**
     * @Route("admin/content-category/add/", name="admin_content_category_add")
     */
    public function adminAddContentCategory(Request $request)
    {
    	$content_category = new ContentCategory();
    	$form = $this->createForm(ContentCategoryType::class, $content_category);
    	$form->handleRequest($request);
    
    	if ($form->isSubmitted() && $form->isValid()) {
    		$alias = $this->get('app.misc_functions')->slug($content_category->getTitle());
    
    		$content_category
	    		->setAlias($alias);
    
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($content_category);
    		$em->flush();
    
    		$this->addFlash(
    				'info',
    				'Page Category added.'
    		);
    
    		$nextAction = $form->get('saveAndAdd')->isClicked()
	    		? 'admin_content_category_add'
    			: 'admin_content_category';
    
    		return $this->redirectToRoute($nextAction);
    	}
    
    	return $this->render('admin/content_category_form.html.twig', array(
    			'form' => $form->createView(),
    			'header' => 'Add New Page Category',
    	));
    }
    
    /**
     * @Route("/admin/content-category/edit/{id}/", name="admin_content_category_edit", defaults={"id": 1})
     */
    public function adminEditContentCategory(Request $request, $id)
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$content_category = $em->getRepository('AppBundle:ContentCategory')->find($id);
    	if (!$content_category) {
    		throw $this->createNotFoundException(
    				'No Page Category found for id '.$id
    				);
    	}
    
    	$form = $this->createForm(ContentCategoryType::class, $content_category);
    	$form->handleRequest($request);
    
    	if ($form->isSubmitted() && $form->isValid()) {
    		$content_category
	    		->setAlias($this->get('app.misc_functions')->slug($content_category->getTitle()));
    
    		$em->persist($content_category);
    		$em->flush();
    
    		$this->addFlash(
    				'info',
    				'Page Category modified.'
    				);
    
    		$nextAction = $form->get('saveAndAdd')->isClicked()
	    		? 'admin_content_category_add'
    			: 'admin_content_category';
    
    		return $this->redirectToRoute($nextAction);
    	}
    
    	return $this->render('admin/content_category_form.html.twig', array(
    			'form' => $form->createView(),
    			'header' => 'Edit Page Category',
    	));
    }
    
    /**
     * @Route("/admin/content/list/{cat_id}", name="admin_content")
     */
    public function adminContent(Request $request, $cat_id = '')
    {
    	$em = $this->getDoctrine()->getManager();

    	$header = '';
    	$conditions = array();
    	if (!empty($cat_id)) {
    		$header = $em->getRepository('AppBundle:ContentCategory')->find($cat_id)->getTitle();
    		$conditions['cat_id'] = $cat_id; 
    	}
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Content');

        if ($request->request->get('submit')) {
            $deleteList = $request->request->get('delete');
            if (count($deleteList) > 0) {
                foreach ($deleteList as $item)
                {
                	if ($item > 3) { // 1 to 3 are required pages
                		$menu = $repository->find($item);
                		$em->remove($menu);
                		$em->flush();
                	}
                }

                $this->addFlash(
                    'info',
                    'Content page(s) deleted.'
                );
            }
        }

        $sortBy = $request->query->get('sort');
        if ($sortBy == '')
            $sortBy = 'id';
        $order = $request->query->get('order');
        if ($order == '')
            $order = 'ASC';
        $content = $repository->findBy($conditions,array($sortBy => $order));

        return $this->render('admin/content.html.twig', array(
            'contents' => $content,
            'header' => $header .' Pages',
        	'cat_id' => $cat_id
        ));
    }

    /**
     * @Route("admin/content/add/{cat_id}", name="admin_content_add")
     */
    public function newContentAction(Request $request, $cat_id = "")
    {
    	$em = $this->getDoctrine()->getManager();
    	if ($cat_id == '') 
    		$header = '';
    	else 
    		$header = $em->getRepository('AppBundle:ContentCategory')->find($cat_id)->getTitle();
    	
    	$content = new Content();
        $form = $this->createForm(ContentType::class, $content);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->request->get('content');
            $alias = $this->get('app.misc_functions')->slug($data['title']);

            $tmp = new \DateTime('now');

            $content
                ->setAlias($alias)
                ->setDateAdded($tmp)
                ->setDateModified($tmp)
                ->setHits(0);

            $em->persist($content);
            $em->flush();

            $this->addFlash(
                'info',
                'Content page added.'
            );

            $nextAction = $form->get('saveAndAdd')->isClicked()
                ? 'admin_content_add'
                : 'admin_content';

            return $this->redirectToRoute($nextAction, array('cat_id' => $cat_id));
        }

        return $this->render('admin/content_form.html.twig', array(
            'form' => $form->createView(),
            'header' => 'Add New '. $header .' Page',
        	'cat_id' => $cat_id
        ));
    }

    /**
     * @Route("/admin/content/edit/{id}", name="admin_content_edit", defaults={"id": 1})
     */
    public function adminEditContent(Request $request, $id)
    {
    	$em = $this->getDoctrine()->getManager();

        $content = $em->getRepository('AppBundle:Content')->find($id);
        if (!$content) {
            throw $this->createNotFoundException(
                'No content page found for id '.$id
            );
        }

        if (is_null($content->getCatId())) {
        	$cat_id = null;
        	$header = '';
        }
        else {
        	$cat_id = $content->getCatId();
        	$header = $content->getCategory()->getTitle();
        }
        
        
        $form = $this->createForm(ContentType::class, $content);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $request->request->get('content');

            $tmp = new \DateTime('now');

            if ($content->getId() > 10) // 1 to 10 are reserved for fixed pages 
            	$content->setAlias($this->get('app.misc_functions')->slug($data['title']));
            
            $content
                ->setDateModified($tmp);

            $em->persist($content);
            $em->flush();

            $this->addFlash(
                'info',
                'Content page modified.'
            );

            $nextAction = $form->get('saveAndAdd')->isClicked()
                ? 'admin_content_add'
                : 'admin_content';

            return $this->redirectToRoute($nextAction, array('cat_id' => $cat_id));
        }

        return $this->render('admin/content_form.html.twig', array(
            'form' => $form->createView(),
            'header' => 'Edit '. $header .' Page',
        	'cat_id' => $cat_id
        ));
    }

    /**
     * @Route("/admin/menu/page/{id}/", name="admin_menu_page")
     */
    public function adminMenuPageAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $menu = $em->getRepository('AppBundle:Menu')->find($id);
        if (!$menu) {
            $this->addFlash(
                'info',
                'Invalid menu.'
            );

            return $this->redirectToRoute('admin_menu');
        }

        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:MenuItem');

        if ($request->request->get('submit')) {
            $em = $this->getDoctrine()->getManager();
            $deleteList = $request->request->get('delete');
            if (count($deleteList) > 0) {
                foreach ($deleteList as $item)
                {
                    if (strpos($item, '-') !== false) {
                        $item = explode('-',$item);
                        $menuItem = $repository->findOneBy(array('menuId' => $id, 'id' => $item[1]));
                    }
                    else
                        $menuItem = $repository->find($item);

                    $em->remove($menuItem);
                    $em->flush();
                }

                $this->addFlash(
                    'info',
                    'Menu page(s) deleted.'
                );
            }
        }

        $sortBy = $request->query->get('sort');
        if ($sortBy == '')
            $sortBy = 'sortOrder';
        $order = $request->query->get('order');
        if ($order == '')
            $order = 'ASC';
        $content = $repository->findBy(array('menuId' => $menu->getId(), 'parentId' => '0'),array($sortBy => $order));

        return $this->render('admin/menu_item.html.twig', array(
            'contents' => $content,
            'menu_id' => $id,
            'header' => $menu->getTitle() .' - Pages',
        ));
    }

    /**
     * @Route("admin/menu/page/add/{id}", name="admin_menu_page_add")
     */
    public function newMenuPageAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:MenuItem');

        $menuItem = new MenuItem();
        $menuItem->setMenuId($id);
        $menuItem->setParentId(0); // can't use primary key 'id'
        $form = $this->createForm(MenuItemType::class, $menuItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->request->get('menu_item');
            empty($data['parent_id']) ? $parent_id = 0 : $parent_id = $data['parent_id'];
            $sort_order = $repository->findBy(array('menuId' => $id, 'parentId' => $parent_id));
            $sort_order = count($sort_order);

            $menuItem
                ->setAlias($this->get('app.misc_functions')->slug($data['title']))
                ->setSortOrder($sort_order)
                ->setParentId($parent_id)
            ;

            $em = $this->getDoctrine()->getManager();
            $em->persist($menuItem);
            $em->flush();

            $this->addFlash(
                'info',
                'Menu Item added.'
            );

            $nextAction = $form->get('saveAndAdd')->isClicked()
                ? 'admin_menu_page_add'
                : 'admin_menu_page';

            return $this->redirectToRoute($nextAction, array('id' => $id));
        }

        return $this->render('admin/menu_item_form.html.twig', array(
            'form' => $form->createView(),
            'menu_id' => $id,
            'header' => 'Add New Menu Item',
        ));
    }

    /**
     * @Route("admin/menu/page/edit/{menu_id}/{id}/", name="admin_menu_page_edit")
     */
    public function editMenuPageAction(Request $request, $menu_id, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $menuItem = $em->getRepository('AppBundle:MenuItem')->find($id);

        $sortOrder = $menuItem->getSortOrder();
        $form = $this->createForm(MenuItemType::class, $menuItem);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->request->get('menu_item');
            is_null($data['parent_id']) ? $parent_id = 0 : $parent_id = $data['parent_id'];

            if ($sortOrder != $data['sort_order'])
            {
                // update all other menu items' sort order +1
                $query = $em->createQuery(
                    'SELECT p
                        FROM AppBundle:MenuItem p
                        WHERE p.parentId = '. $parent_id .' AND p.id <> '. $id .' AND p.sortOrder >= '. $data['sort_order']);
                $menuItems = $query->getResult();

                foreach ($menuItems as $item) {
                    $sortOrder = $item->getSortOrder();
                    $item->setSortOrder(($sortOrder+1));
                }
            }

            $menuItem
                ->setAlias($this->get('app.misc_functions')->slug($data['title']))
                ->setParentId($parent_id)
                ;

            $em = $this->getDoctrine()->getManager();
            $em->persist($menuItem);
            $em->flush();

            $this->addFlash(
                'info',
                'Menu Item modified.'
            );

            $nextAction = $form->get('saveAndAdd')->isClicked()
                ? 'admin_menu_page_add'
                : 'admin_menu_page';

            return $this->redirectToRoute($nextAction, array('id' => $menu_id));
        }

        return $this->render('admin/menu_item_form_edit.html.twig', array(
            'form' => $form->createView(),
            'menu_item' => $menuItem,
            'header' => 'Edit New Menu Item',
        ));
    }

    /**
     * @Route("/admin/contact", name="admin_contact")
     */
    public function adminContact(Request $request) {
    	$data = $request->query->all();
    
    	$items = $this->getDoctrine()->getRepository('AppBundle:Contact')->findBy([],array('id'=>'DESC'));
    	$totalItems = count($items);
    
    	!empty($data['page']) ? $pagenum = $data['page'] : $pagenum = 1;
    
    	$items = array_slice($items,($pagenum - 1) * 10, 10);
    
    	$itemsPerPage = 10;
    	$urlPattern = $request->getPathInfo() .'?page=(:num)';
    
    	$paginator = new Paginator($totalItems, $itemsPerPage, $pagenum, $urlPattern);
    
    	return $this->render('admin/contact.html.twig', array(
    			'header' => 'Contact Messages',
    			'items' => $items,
    			'paginator' => $paginator
    	));
    }
    
    /**
     * @Route("admin/menu/list_page_type", name="ajax_page_type_items")
     */
    public function ajaxListPageTypeItems(Request $request)
    {
        $pageType = $request->request->get('id');
        $pageTypeId = $request->request->get('id2');

        switch ($pageType)
        {
            case 'text-separator':
                $template = 'text_separator_list';
                $id = 'menu_item_page_type_id';
                $name = 'menu_item[page_type_id]';
                break;
            case 'content':
                $entity = 'Content';
                $template = 'pagetype_list';
                break;
            case 'product-category':
                $entity = 'ProductCategory';
                $template = 'product_categories';
                break;
            default:
            case 'url':
            case 'route':
                $template = 'text_input';
                $id = 'menu_item_page_type_id';
                $name = 'menu_item[page_type_id]';
                break;
        }

        if (!isset($entity))
            return $this->render('admin/ajax_'. $template .'.html.twig', array(
                'id' => $id,
                'name' => $name,
                'id2' => $pageTypeId,
            ));
        else {
            $repository = $this->getDoctrine()
                ->getRepository('AppBundle:'. $entity);

            if ($entity != 'ProductCategory')
                $items = $repository->findBy([]);
            else {
                $condition = array(
                    'publish' => 1,
                    'parent_id' => 1
                );
                $parents = $repository->findBy($condition, array('ordering' => 'ASC'));
                $items = array();
                foreach ($parents as $parent) {
                    // 1st level
                    $item = array(
                        'id' => $parent->getId(),
                        'title' => $parent->getTitle(),
                    );
                    $items[] = $item;

                    // 2nd level
                    $level_2s = $parent->getChildren();
                    foreach ($level_2s as $level_2) {
                        $item = array(
                            'id' => $level_2->getId(),
                            'title' => '-- '. $level_2->getTitle(),
                        );
                        $items[] = $item;

                        // 3rd level
                        $level_3s = $level_2->getChildren();
                        foreach ($level_3s as $level_3) {
                            $item = array(
                                'id' => $level_3->getId(),
                                'title' => '---- '. $level_3->getTitle(),
                            );
                            $items[] = $item;
                        }
                    }
                }

            }

            return $this->render('admin/ajax_'. $template .'.html.twig', array(
                'items' => $items,
                'id2' => $pageTypeId
            ));
        }
    }

    /**
     * @Route("admin/menu/list_parent_items", name="ajax_parent_items")
     */
    public function ajaxListParentItems(Request $request)
    {
        $data = $request->request->all();
        $sort_order = $request->request->get('id2');

        empty($data['id']) ? $parentId = 0 : $parentId = $data['id'];

        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:MenuItem');
        if (empty($data['menu_id']))
            $items = $repository->findBy(array('parentId' => $parentId), array('sortOrder' => 'ASC'));
        else
            $items = $repository->findBy(array('parentId' => $parentId, 'menuId' => $data['menu_id']), array('sortOrder' => 'ASC'));

        return $this->render('admin/ajax_parent_items.html.twig', array(
            'items' => $items,
            'sort_order' => $sort_order
        ));
    }

    public  function menuItemChildrenAction(Request $request, $menu_id, $parent){
        $sortBy = $request->request->get('sort');
        if ($sortBy == '')
            $sortBy = 'sortOrder';
        $order = $request->request->get('order');
        if ($order == '')
            $order = 'ASC';

        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:MenuItem');
        $items = $repository->findBy(array('menuId' => $menu_id, 'parentId' => $parent),array($sortBy => $order));

        return $this->render('admin/menu_item_parent_items.html.twig', array(
            'items' => $items,
            'menu_id' => $menu_id
        ));
    }

    /**
     * @Route("admin/users", name="admin_users")
     */
    public function adminUsers(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:User');

        $data = $request->query->all();
        if ($request->request->get('submit')) {
            $itemList = $request->request->get('item');
            if (count($itemList) > 0 && !empty($request->request->get('action'))) {
                if ($request->request->get('action') == 'Block') {
                    $info = 'blocked';
                    $enabled = 0;
                }
                else {
                    $info = 'enabled';
                    $enabled = 1;
                }

                foreach ($itemList as $item)
                {
                    $user = $repository->find($item);
                    $user->setEnabled($enabled);
                }

                $em->flush();

                $this->addFlash(
                    'info',
                    "User(s) $info."
                );
            }
        }
        else {
            if (!empty($data['toggle'])) {
                $user = $repository->find($data['toggle']);
                if ($user->isEnabled()) {
                    $enabled = 0;
                    $info = 'blocked';
                }
                else {
                    $enabled = 1;
                    $info = 'enabled';
                }

                $user->setEnabled($enabled);

                $this->addFlash(
                    'info',
                    "User, ". $user->getUsername() .", $info."
                );

                $em->flush();
            }
        }

        !empty($data['sort']) ? $sortBy = $data['sort'] : $sortBy = 'id';
        !empty($data['order']) ? $order = $data['order'] : $order = 'ASC';
        !empty($data['page']) ? $pagenum = $data['page'] : $pagenum = 1;

        $items = $repository->findBy([], array($sortBy => $order));
        $totalItems = count($items);

        $itemsPerPage = 10;
        $urlPattern = $request->getPathInfo() .'?page=(:num)';

        $paginator = new Paginator($totalItems, $itemsPerPage, $pagenum, $urlPattern);

        return $this->render('admin/users.html.twig', array(
            'items' => array_slice($items, ($pagenum - 1) * 10, 10),
            'paginator' => $paginator,
            'header' => 'Users',
        ));
    }

    public function homepageImageUpload($imagesDir, $image, $alias, $old_image, $width, $height) {
    	$fs = new Filesystem();
    
    	$uploader = $this->get('app.file_uploader');
    	$uploader->setTargetDir($imagesDir);
    
    	if (!empty($image))
    	{
    		$image = $uploader->uploadImage($image, $alias);
    
    		$source = $imagesDir .'/'. $image;
    		$resized = $imagesDir .'/home/'. $image;
    
    		$this->get('app.image_resizer')->resize($source, null, $width, $height, false, $resized, true, false, 80);
    
    		if (!empty($old_image))
    			$fs->remove($imagesDir .'/home/'. $old_image);
    	}
    	else {
    		if (!empty($old_image))
    			$image = $old_image;
    			else
    				$image = '';
    	}
    
    	return $image;
    }
}