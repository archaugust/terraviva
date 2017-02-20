<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JasonGrimes\Paginator;
use AppBundle\Entity\ProductCategory;
use AppBundle\Entity\ProductItem;
use AppBundle\Entity\Subscriber;

class ShopController extends Controller
{
	public function menusAction($mode) {
		$menus = $this->get('session')->get('menus');

		if (empty($menus))
		{
			$repository = $this->getDoctrine()->getRepository('AppBundle:ProductCategory');
	
			// build menu_garden
			$menu_garden = array();
	
			$cats = array(4, 6, 8, 36);
			 
			foreach ($cats as $cat) {
				$category = $repository->find($cat);
				// insert Garden Info
				if ($cat == 36) {
					$items = array();
					$items[] = array('alias' => '', 'route' => 'garden_calendar', 'title' => 'Garden Calendar');
					$info = $this->getDoctrine()->getRepository('AppBundle:Content')->findBy(array('cat_id' => 1), array('title' => 'ASC'));
	
					foreach ($info as $item)
						$items[] = array('alias' => $item->getAlias(), 'route' => 'garden_info', 'title' => $item->getTitle());
	
						$menu_garden[] = array('title' => 'Garden Info', 'alias' => '', 'route' => 'garden_info', 'items' => $items);
				}
	
				$subcats = $repository->findBy(array('parent_id' => $cat));
				 
				$items = array();
				$items[] = array('title' => 'All '. $category->getTitle(), 'alias' => $category->getAlias(), 'route' => 'shop_category');
				foreach ($subcats as $subcat)
					$items[] = array('alias' => $subcat->getAlias(), 'route' => 'shop_category', 'title' => $subcat->getTitle());
	
					$menu_garden[] = array('title' => $category->getTitle(), 'alias' => $category->getAlias(), 'route' => 'shop_category', 'items' => $items);
			}
			 
			// build home_gift
			$menu_home_gift = array();
			$cats = array(42, 43, 44, 45, 46, 47);
	
			foreach ($cats as $cat) {
				$category = $repository->find($cat);
				$subcats = $repository->findBy(array('parent_id' => $cat));
				 
				$items = array();
				$items[] = array('title' => 'All '. $category->getTitle(), 'alias' => $category->getAlias(), 'route' => 'shop_category');
				foreach ($subcats as $subcat)
					$items[] = array('alias' => $subcat->getAlias(), 'route' => 'shop_category', 'title' => $subcat->getTitle());
	
					$menu_home_gift[] = array('title' => $category->getTitle(), 'alias' => $category->getAlias(), 'route' => 'shop_category', 'items' => $items);
			}
			 
			$menus = array($menu_garden, $menu_home_gift);
			$this->get('session')->set('menus', $menus);
		}
		 
		return $this->render('default/menu_'. $mode .'.html.twig', array(
				'menu_garden' => $menus[0],
				'menu_home_gift' => $menus[1]
		));
	}
	
	/**
	 * @Route("subscribe", name="subscribe")
	 */
	public function subscribe(Request $request) {
		$em = $this->getDoctrine()->getManager();
		
		$data = $request->request->all();

		if (empty($data['subscribe_name']) || empty($data['subscribe_email'])) 
			$mode = 'form';
		else {
			// check if email already subscribed
			$check = $em->createQuery('
					SELECT s
					FROM AppBundle:Subscriber s
					WHERE s.email = :email')
					->setParameter('email', $data['subscribe_email'])
					->getResult();

			if (count($check) > 0)
				$mode = 'subscribed';
			else {
				// add subscriber
				$subscriber = new Subscriber();
				$subscriber
					->setFname($data['subscribe_name'])
					->setEmail($data['subscribe_email'])
					->setDateSubscribed(time());
				$em->persist($subscriber);
				$em->flush();
				
				$mode = 'success';
			}
		}
		
		return $this->render('shop/ajax_subscribe.html.twig', array(
			'mode' => $mode
		));
	}
	
    /**
     * @Route("shop/category/{alias}", name="shop_category")
     */
    public function shopCategory(Request $request, $alias) {
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('AppBundle:ProductCategory')->findOneBy(array('alias' => $alias));
        $category->setHits($category->getHits() + 1);
        $em->flush();

        // template select
        $subcategories = $em->getRepository('AppBundle:ProductCategory')->findBy(array('parent_id' => $category->getId(), 'publish' => 1));
        if (count($subcategories) > 0) {
        	$breadcrumbs = array();
            $parent = $category->getParentId();
            // subcategory
            if ($parent == 1) {
                $breadcrumbs[] = array(
                    'title' => 'All '. $category->getTitle(),
                    'alias' => $category->getAlias()
                );
                $items = $subcategories;
                $parent = $category->getTitle();
            }
            else {
                $parent = $category->getParent();
                $breadcrumbs = array(
                    array(
                        'title' => 'All '. $parent->getTitle(),
                        'alias' => $parent->getAlias(),
                    ),
                    array(
                        'title' => $category->getTitle(),
                        'alias' => $category->getAlias()
                    )
                );
                $parent = $category->getTitle();
            }

            return $this->render('shop/category_sub.html.twig', array(
                'breadcrumbs' => $breadcrumbs,
                'parent' => $parent,
                'category' => $category,
                'items' => $subcategories,
            ));
        }
        else {
        	$data = $request->query->all();
        	empty($data['page']) ? $page = 1 : $page = $data['page'];
        	
        	 // related items
        	$query = $em
	        	->createQuery("
		            SELECT r 
		              FROM AppBundle:ProductItem r 
		              WHERE r.related_tags LIKE :tag AND r.publish = 1 AND r.deleted = 0
	        	")
	        	->setParameter('tag',$category->getTitle())
        	;
        	$related_items = $query->getResult();
        	
        	if (count($related_items) == 0)
        		$related_items = $em->getRepository('AppBundle:ProductItem')->findBy(array('publish' => 1, 'deleted' => 0));
        	shuffle($related_items);
        	$related_items = array_slice($related_items, 0, 5);
        	 
        	// items
        	$parent = $category;
        	$breadcrumbs = array();
        	while ($parent->getId() != 1) {
        		$title = $parent->getTitle();
        		if ($parent->getParentId() == 1)
        			$title = 'All '. $title;
        			array_unshift($breadcrumbs, array(
        					'title' => $title,
        					'alias' => $parent->getAlias(),
        			));
        			$parent = $parent->getParent();
        	}
        	
        	if (!empty($data['filter'])) {
        		$filter_groups = $category->getFilters();
        		$filters = array();
        		foreach ($filter_groups as $filter_group) {
        			$tags = array();
        			if (in_array($data['filter'], explode(',',$filter_group->getTags())))
        				$tags[] = "i.filters LIKE '%". $data['filter'] ."%'";
        			if (count($tags) > 0)
        				$filters[] = '('. implode(' OR ', $tags) .')';
        		}
        		$filters = implode(' AND ', $filters);
        	}
        	else
        		$filters = '1 = 1';
        		 
            $items = $em->createQuery('
            	SELECT i
                    FROM AppBundle:ProductItem i
                    WHERE i.cat_id = '. $category->getId() .' AND i.publish = 1 AND i.deleted = 0 AND '. $filters .'
            		ORDER BY i.title ASC'
            		);
            $items = $items->getResult();
            
            $totalItems = count($items);
            
            $items = array_slice($items, ($page - 1) * 5, 5);

            $itemsPerPage = 5;
            $urlPattern = '(:num)';

            $paginator = new Paginator($totalItems, $itemsPerPage, $page, $urlPattern);

            return $this->render('shop/category_list.html.twig', array(
                'breadcrumbs' => $breadcrumbs,
                'category' => $category,
                'items' => $items,
            	'related_items' => $related_items,
                'paginator' => $paginator,
            ));
        }
    }

    /**
     * @Route("shop/item/{alias}", name="shop_item")
     */
    public function shopItem(Request $request, $alias) {
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('AppBundle:ProductItem')->findOneBy(array('alias' => $alias));

        if (is_null($item)) {
            $this->addFlash(
                'info',
                'The item requested does not exist or is no longer available.'
            );

            return $this->redirectToRoute('homepage');
        }

        $item->setHits($item->getHits() + 1);
        $category = $item->getCategory();
        $category->setHits($category->getHits() + 1);
        $em->flush();

        // related items
        $query = $em
	        ->createQuery("
		            SELECT r
		              FROM AppBundle:ProductItem r
		              WHERE r.related_tags LIKE :tag AND r.publish = 1 AND r.deleted = 0
	        	")
	        ->setParameter('tag',$category->getTitle())
        ;
        $related_items = $query->getResult();
        shuffle($related_items);
        $related_items = array_slice($related_items, 0, 5);
        
        $parent = $category;
        $breadcrumbs = array();
        while ($parent->getId() != 1) {
            $title = $parent->getTitle();
            if ($parent->getParentId() == 1)
                $title = 'All '. $title;
            array_unshift($breadcrumbs, array(
                'title' => $title,
                'alias' => $parent->getAlias(),
            ));
            $parent = $parent->getParent();
        }

        $breadcrumbs[] = array(
            'title' => $item->getTitle(),
            'alias' => $item->getAlias()
        );

        return $this->render('shop/shop_front_item.html.twig', array(
            'breadcrumbs' => $breadcrumbs,
            'category' => $category,
            'item' => $item,
        	'related_items' => $related_items,
        ));
    }


    public function itemCategoryAction($id) {
        $em = $this->getDoctrine();
        $category = $em->getRepository('AppBundle:ProductCategory')->find($id);

        $category_title = $category->getTitle();
        while ($category->getParentId() != 1) {
            $category = $em->getRepository('AppBundle:ProductCategory')->find($category->getParentId());
            $category_title = $category->getTitle() .' - '. $category_title;
        }

        return new Response($category_title);
    }

    /**
     * @Route("ajax-shop-front-category", name="ajax_shop_front_category")
     */
    public function ajaxShopFrontCategory(Request $request) {
        $data = $request->request->all();
        $cat_id = strip_tags($data['category']);
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:ProductCategory')->find($cat_id);
        $page = $data['page'];

        switch ($data['sort']) {
            case 'Name A-Z':
                $sortBy = 'title';
                $orderBy = 'ASC';
                break;
            case 'Price High to Low':
                $sortBy = 'price_sale';
                $orderBy = 'DESC';
                break;
            case 'Price Low to High':
                $sortBy = 'price_sale';
                $orderBy = 'ASC';
                break;
        }

        if (!empty($data['tag'])) {
            $filter_groups = $category->getFilters();
            $filters = array();
            foreach ($filter_groups as $filter_group) {
                $tags = array();
                foreach ($data['tag'] as $tag) {
                    if (in_array($tag, explode(',',$filter_group->getTags())))
                        $tags[] = "i.filters LIKE '%". $tag ."%'";
                }
                if (count($tags) > 0)
                    $filters[] = '('. implode(' OR ', $tags) .')';
            }
            $filters = implode(' AND ', $filters);
        }
        else
            $filters = '1 = 1';

        $items = $em->createQuery('
            SELECT i
                    FROM AppBundle:ProductItem i
                    WHERE i.cat_id = '. $cat_id .' AND i.publish = 1 AND i.deleted = 0 AND '. $filters .' 
                    ORDER BY i.'. $sortBy .' '. $orderBy
        );
        $items = $items->getResult();
        $totalItems = count($items);

        $items = array_slice($items, ($page - 1) * 5, 5);

        $itemsPerPage = 5;
        $urlPattern = '(:num)';

        $paginator = new Paginator($totalItems, $itemsPerPage, $page, $urlPattern);

        return $this->render('shop/ajax_shop_front_category.html.twig', array(
            'items' => $items,
            'paginator' => $paginator,
        ));
    }

    /**
     * @Route("ajax-shop-front-item", name="ajax_shop_front_item")
     */
    public function ajaxShopFrontItem(Request $request) {
        $alias = $request->request->get('item');
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('AppBundle:ProductItem')->findOneBy(array('alias' => $alias));

        if (is_null($item))
            return $this->render('show/ajax_item_not_found.html.twig');

        $item->setHits($item->getHits() + 1);
        $category = $item->getCategory();
        $category->setHits($category->getHits() + 1);
        $em->flush();

        return $this->render('shop/ajax_shop_front_item.html.twig', array(
            'category' => $category,
            'item' => $item,
        ));
    }
    
    /**
     * @Route("ajax-shop-front-item-related", name="ajax_shop_front_item_related")
     */
    public function ajaxShopFrontItemRelated(Request $request) {
    	$alias = $request->request->get('item');
    	$em = $this->getDoctrine()->getManager();
    	$item = $em->getRepository('AppBundle:ProductItem')->findOneBy(array('alias' => $alias));
    	$category = $item->getCategory();

    	// related items
    	$query = $em
    	->createQuery("
            SELECT r
		    FROM AppBundle:ProductItem r
			WHERE r.related_tags LIKE :tag AND r.publish = 1 AND r.deleted = 0
        ")
    	->setParameter('tag', $category->getTitle())
    	;
    	
    	$related_items = $query->getResult();
    	shuffle($related_items);
    	$related_items = array_slice($related_items, 0, 5);
    	         
    	return $this->render('shop/ajax_shop_front_item_related.html.twig', array(
    		'related_items' => $related_items,
    		'category' => $category,
    	));
    }

    public function categoriesFilterAction($selected) {
        $categories = $this->getDoctrine()->getRepository('AppBundle:ProductCategory')->findBy(array('parent_id' => 1, 'publish' => 1),array('ordering' => 'ASC'));

        return $this->render('shop/categories.html.twig', [
            'items' => $categories,
            'selected' => $selected,
        ]);
    }

    public function subcategoriesFilterAction($id, $selected) {
        $subcategories = $this->getDoctrine()->getRepository('AppBundle:ProductCategory')->findBy(array('parent_id' => $id, 'publish' => 1));

        return $this->render('shop/subcategories.html.twig', [
            'items' => $subcategories,
            'selected' => $selected,
        ]);
    }

    /**
     * @Route("garden-calendar", name="garden_calendar")
     */
    public function gardenCalendar() {
        $calendar = $this->getDoctrine()->getRepository('AppBundle:ProductCalendar')->find(1);

        return $this->render('shop/calendar.html.twig', array(
            'calendar' => $calendar,
        ));
    }

    /**
     * @Route("cart", name="cart")
     */
    public function cart(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');
        $input = $request->request->all();
        $confirm = $request->query->get('confirm');

        empty($input['update']) ? $update = 0 : $update = 1;
        $cart = $session->get('cart');

        if ($update == 1)
        {
            // update cart
            foreach ($cart as $index => $data)
            {
                empty($input['item_'. $index]) ? $qty = 1 : $qty = $input['item_'. $index];

                if ($qty > 0)
                {
                    $item = $em->getRepository('AppBundle:ProductItem')->find($cart[$index]['id']);

                    /*
                    $in_stock = $item->getInStock();

                    // check if sold out
                    if ($in_stock == 0)
                        unset($cart[$index]);

                    // check if over available stock
                    if ($qty >= $in_stock)
                        $cart[$index]['qty'] = $in_stock;
                    else
                    */
                    $cart[$index]['qty'] = $qty;
                }
                else
                    unset($cart[$index]);

            }

            // remove item, +1'd id at form since 0 is considered empty
            empty($input['id']) ? $id = '' : $id = $input['id'];
            if ($id != '')
                unset($cart[$id - 1]);

            $session->set('cart', $cart);
        }

        // render cart
        $cart = $this->getSessionItems('cart');
        $cart_items = $cart['cart_items'];

        if (is_null($this->getUser()) && count($cart_items))
            $this->addFlash(
                'info',
                'You need to login as a customer to complete your order.'
            );

        return $this->render('shop/cart.html.twig', array(
            'cart_items' => $cart_items,
            'price_total' => $cart['price_total'],
            'confirm' => $confirm,
        ));
    }

    /**
     * @Route("cart/add", name="cart_add")
     */
    public function cartAdd(Request $request) {
    	$session = $this->get('session');
        $data = $request->request->all();
        empty($data['qty']) ? $qty = 1 : $qty = $data['qty'];
        $item = $request->get('item');
        
        $item = $this->getDoctrine()->getRepository('AppBundle:ProductItem')->findOneBy(array('alias' => $item));

        if (is_null($item)) {
            $this->addFlash(
                'danger',
                'Invalid item.'
            );

            return $this->redirectToRoute('shop_category', array('alias'=>'plants'));
        }

        $category = $item->getCategory();

        // save link for 'Continue Shopping' button
        $session->set('continue', $category->getAlias());

        // check if sold out
        /*
        $in_stock = $item->getInStock();

        if ($in_stock == 0) {
            $this->addFlash(
                'info',
                'Selected product is Sold Out. Please select a different product.'
            );

            return $this->redirectToRoute('shop_item', array('alias' => $item->getAlias()));
        }

        // check if over available stock
        if ($qty >= $in_stock)
        {
            $qty = $in_stock;
            $msg .= '<br />Quantity changed to remaining available stock.';
        }
        */

        // add to cart
        $id = $item->getId();

        $cart = $session->get('cart');
        if (is_null($cart)) {
            $session->set('cart',array());
            $cart = $session->get('cart');
        }

        // check if item is already in cart, if so +1
        if ($this->checkItem($cart, 'id', $id) == true)
        {
            foreach($cart as $index => $data)
            {
                if ($data['id'] == $id)
                {
                    $tmp = ($qty + 1);
                    $cart[] = array('id' => $id, 'qty' => $tmp);
                    unset($cart[$index]);
                }
            }
        }
        else
            $cart[] = array('id' => $id, 'qty' => $qty);

        $session->set('cart', $cart);

        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("cart/checkout", name="cart_checkout")
     */
    public function cartCheckout() {
    	$session = $this->get('session');
        $em = $this->getDoctrine()->getManager();
        $cart = $session->get('cart');
        $user = $this->getUser();

//      if (is_null($user)) 
		{
            $this->addFlash(
                'danger',
//                'Please login to be able to place orders.'
				'Under construction.'
            );

            return $this->redirectToRoute('homepage');
        }

        if (count($cart)>0)
        {
            $price_total = 0;
            $order_items = array();
            foreach ($cart as $index => $data)
            {
                $item = $em->getRepository('AppBundle:ProductItem')->find($data['id']);

                // check if ad.in_stock changed
/*                if ($item->getInStock() < $data['qty']) {
                    $this->addFlash(
                        'danger',
                        "A product's available inventory has just been depleted. Please review your cart's new quantity values."
                    );
                    return $this->redirectToRoute('cart');
                }
*/

                $price_sale = $ad->getPriceSale();

                $price_total += ($price_sale * $data['qty']);

                // order_items
                $order_item = array(
                    'id' => $data['id'],
                    'price' => $price_sale,
                    'qty' => $data['qty'],
                );

                $order_items[] = $order_item;
            }

            // insert order record
            $now = time();
            $order = new ProductOrder();
            $order
                ->setMerchant($merchant)
                ->setDateAdded($now)
                ->setPriceTotal($price_total)
                ->setOrderno('-')
                ->setStatus('Pending')
                ->setDateCompleted(0)
                ->setDateDue(0)
                ->setShipping(0)
                ->setDiscount(0)
            ;
            $em->persist($order);
            $em->flush();

            // update merc balance
            $merchant->setBalance($merchant->getBalance() + ($price_total * 1.15));
            $em->flush();

            foreach ($order_items as $item)
            {
                $ad = $em->getRepository('AppBundle:DealAd')->find($item['ad_id']);

                $order_item = new DealOrderItem();
                $order_item
                    ->setOrder($order)
                    ->setAd($ad)
                    ->setTypeId($item['type_id'])
                    ->setPrice($item['price'])
                    ->setQty($item['qty'])
                ;

                $em->persist($order_item);
                $em->flush();
            }

            $cart = $this->getCartItems();

            $session->set('cart', array());

            $message = \Swift_Message::newInstance()
                ->setSubject('An order has been placed')
                ->setFrom(array('info@terraviva.nz' => 'Terra Viva Home & Garden'))
                ->setTo('info@terraviva.nz')
                ->setBody(
                    $this->renderView('email/order.html.twig', array(
                        'merchant' => $merchant,
                        'cart_items' => $cart['cart_items'],
                        'price_total' => $cart['price_total']
                    )),
                    'text/html'
                )
            ;
            $this->get('mailer')->send($message);

        }
        else {
            $this->addFlash(
                'info',
                'Your cart is empty.'
            );

            return $this->redirectToRoute('shop_category', array('alias' => 'plants'));
        }

        return $this->render('shop/checkout.html.twig');
    }

    public function checkItem($array, $key, $val) {
        foreach ($array as $item)
            if (isset($item[$key]) && $item[$key] == $val)
                return true;
        return false;
    }

    public function getSessionItems($mode) {
        $session = $this->get('session')->get($mode);
        $session_items = array();
        $repository = $this->getDoctrine()->getRepository('AppBundle:ProductItem');
        if ($mode == 'cart')
        	$price_total = 0;
        
        if (count($session) > 0) {
	        foreach ($session as $index => $data) {
	            $item = $repository->find($data['id']);
	            $image = $item->getImages();
	            (count($image) == 0) ? $image = 'no-image.jpg' : $image = $image[0]->getFilename();
	
	            $price_sale = $item->getPriceSale();

	            if ($mode == 'cart') {
		            $price_total += ($price_sale * $data['qty']);

		            $session_item = array(
		            		'item' => $item,
		            		'image' => $image,
		            		'price_sale' => $price_sale,
		            		'index' => $index,
		            		'qty' => $data['qty'],
		            );
		        }
		        else {
		        	$session_item = array(
		        			'item' => $item,
		        			'image' => $image,
		        			'price_sale' => $price_sale,
		        			'index' => $index,
		        	);
		        }
	
	            $session_items[] = $session_item;
	        }
        }
        
        if ($mode == 'cart') {
        	$session = array(
        			'cart_items' => $session_items,
        			'price_total' => $price_total
        	);
        }
        else 
        	$session = $session_items;

        return $session;
    }

    /**
     * @Route("wishlist", name="wishlist")
     */
    public function wishlist(Request $request) {
    	$session = $this->get('session');
    	
    	$em = $this->getDoctrine()->getManager();
    	$input = $request->request->all();
    
    	empty($input['update']) ? $update = 0 : $update = 1;
    	$wishlist = $session->get('wishlist');
    
    	if ($update == 1)
    	{
    		// update cart
    		foreach ($wishlist as $index => $data)
    		{
    			empty($input['item_'. $index]) ? $qty = 1 : $qty = $input['item_'. $index];
    
    			if ($qty > 0)
    			{
    				$item = $em->getRepository('AppBundle:ProductItem')->find($wishlist[$index]['id']);
    
			 		$wishlist[$index]['qty'] = $qty;
    			}
    			else
    				unset($wishlist[$index]);
    
    		}
    
    		// remove item, +1'd id at form since 0 is considered empty
    		empty($input['id']) ? $id = '' : $id = $input['id'];
    		if ($id != '')
    			unset($wishlist[$id - 1]);
    
    			$session->set('wishlist', $wishlist);
    	}
    
    	// render wishlist
    	$wishlist_items = $this->getSessionItems('wishlist');
    
   		return $this->render('shop/wishlist.html.twig', array(
   				'wishlist_items' => $wishlist_items,
   		));
    }
    
    /**
     * @Route("wishlist/add", name="wishlist_add")
     */
    public function wishlistAdd(Request $request) {
    	$session = $this->get('session');
    	$data = $request->request->all();
    	empty($data['qty']) ? $qty = 1 : $qty = $data['qty'];
    	$item = $request->get('item');
    
    	$item = $this->getDoctrine()->getRepository('AppBundle:ProductItem')->findOneBy(array('alias' => $item));
    
    	if (is_null($item)) {
    		$this->addFlash(
    				'danger',
    				'Invalid item.'
    		);
    
    		return $this->redirectToRoute('shop_category', array('alias'=>'plants'));
    	}
    
    	$category = $item->getCategory();
    
    	// save link for 'Continue Shopping' button
    	$session->set('continue', $category->getAlias());
    
    
    	// add to wishlist
    	$id = $item->getId();
    
    	$wishlist = $this->get('session')->get('wishlist');
    	if (is_null($wishlist)) {
    		$session->set('wishlist', array());
    		$wishlist = $session->get('wishlist');
    	}
    
    	// check if item is already in cart, if so ignore
    	if ($this->checkItem($wishlist, 'id', $id) == false)
    		$wishlist[] = array('id' => $id);
    
    		$session->set('wishlist', $wishlist);
    
   		return $this->redirectToRoute('wishlist');
    }
    
    /**
     * @Route("wishlist-convert", name="wishlist_convert")
     */
    public function wishlistConvert() {
    	$repository = $this->getDoctrine()->getRepository('AppBundle:ProductItem');
    	$session = $this->get('session');
    	$wishlist = $session->get('wishlist');
    	$cart = $session->get('cart');
    	if (is_null($cart)) {
    		$session->set('cart',array());
    		$cart = $session->get('cart');
    	}
    	 
    	foreach ($wishlist as $item) {
    		$id = $item['id'];
    		// check if item is already in cart, if so ignore
    		if ($this->checkItem($cart, 'id', $id) == false) {
    			// check if item has stock
    			$product = $repository->find($id);
    			if ($product->getInStock() > 0)
    				$cart[] = array('id' => $id, 'qty' => 1);
    			else
    				$this->addFlash('warning', $product->getTitle() .' is out of stock.');
    		}
    	}
    	
   		$session->set('cart', $cart);
   		
   		$this->addFlash('notice', 'Items in your Wishlist have been added to your Cart.');
   		$session->remove('wishlist');
    	
   		return $this->redirectToRoute('cart');
    }
    
    /**
     * @Route("garden-info/{alias}", name="garden_info")
     */
    public function gardenInfo($alias = '') {
    	$em = $this->getDoctrine()->getManager();
    
    	if ($alias != '')
    		$page = $em->getRepository('AppBundle:Content')->findOneBy(array('alias' => $alias, 'cat_id' => 1));
    		else
    			$page = $em->getRepository('AppBundle:Content')->findOneBy(array('cat_id' => 1));
    
    			if (count($page) == 0) {
    				$this->addFlash(
    						'danger',
    						'Requested page does not exist or is no longer available.'
    						);
    
    				return $this->redirectToRoute('homepage');
    			}
    
    			$page->setHits($page->getHits() + 1);
    			$em->flush();
    
    			return $this->render('default/content.html.twig', array(
    					'title' => $page->getTitle(),
    					'content' => $page->getContent()
    			));
    }
    
    /**
     * @Route("shop-category-info/{alias}", name="shop_category_info")
     */
    public function shopCategoryInfo($alias) {
    	$em = $this->getDoctrine()->getManager();
    	$category = $em->getRepository('AppBundle:ProductCategory')->findOneBy(array('alias' => $alias));
    	$category->setHits($category->getHits()+1);
    	$em->flush();
    
    	if (count($category) == 0) {
    		$this->addFlash('warning', 'Unknown category.');
    			
    		$this->redirectToRoute('homepage');
    	}
    
    	return $this->render('default/content.html.twig', array(
    			'title' => $category->getTitle(),
    			'content' => $category->getCategoryInfo()->getContent()
    	));
    }
}