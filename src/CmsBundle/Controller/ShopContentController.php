<?php
namespace CmsBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Form\ProductCategoryType;
use AppBundle\Form\ProductItemType;
use AppBundle\Form\ProductCalendarType;
use AppBundle\Form\HomePageType;
use AppBundle\Entity\ProductCategory;
use AppBundle\Entity\ProductItem;
use JasonGrimes\Paginator;

class ShopContentController extends Controller {
	
	/**
	 * @Route("/admin/homepage", name="admin_homepage")
	 */
	public function adminHomePage(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$homepage = $em->getRepository('AppBundle:HomePage')->find(1);
		$oldMainImg = $homepage->getMainImg();
		$oldThumb1Img = $homepage->getThumb1Img();
		$oldThumb2Img = $homepage->getThumb2Img();
		$oldThumb3Img = $homepage->getThumb3Img();
	
		$form = $this->createForm(HomePageType::class, $homepage);
	
		$form->handleRequest($request);
	
		if ($form->isValid()) {
	
			// images
			$rootDir = $this->container->getParameter('kernel.root_dir');
	
			$fs = new Filesystem();
			if ($fs->exists($rootDir .'/../web/images'))
				$imagesDir = $rootDir .'/../web/images';
				else
					$imagesDir = $rootDir .'/../public/images';
	
					$slugger = $this->get('app.misc_functions');
	
					$mainImg = $this->homepageImageUpload($imagesDir, $homepage->getMainImg(), $slugger->slug($homepage->getMainTitle()) .'-'. rand(0,1000), $oldMainImg, 1900, 664);
					$thumb1Img = $this->homepageImageUpload($imagesDir, $homepage->getThumb1Img(), $slugger->slug($homepage->getThumb1Title()) .'-'. rand(0,1000), $oldThumb1Img, 800, 470);
					$thumb2Img = $this->homepageImageUpload($imagesDir, $homepage->getThumb2Img(), $slugger->slug($homepage->getThumb2Title()) .'-'. rand(0,1000), $oldThumb2Img, 800, 470);
					$thumb3Img = $this->homepageImageUpload($imagesDir, $homepage->getThumb3Img(), $slugger->slug($homepage->getThumb3Title()) .'-'. rand(0,1000), $oldThumb3Img, 800, 470);
	
					$homepage
					->setMainImg($mainImg)
					->setThumb1Img($thumb1Img)
					->setThumb2Img($thumb2Img)
					->setThumb3Img($thumb3Img)
					;
	
					$em->flush();
	
					$this->addFlash(
							'info',
							'Home Page updated.'
							);
		}
	
		return $this->render('admin/homepage_form.html.twig', array(
				'form' => $form->createView(),
				'header' => 'Update Home Page',
		));
	}

	/**
	 * @Route("admin/subscribers", name="admin_subscribers")
	 */
	public function adminSubscribers(Request $request){
		$em = $this->getDoctrine()->getManager();
		$repository = $this->getDoctrine()
			->getRepository('AppBundle:Subscriber');
	
		if ($request->request->get('submit')) {
			$deleteList = $request->request->get('delete');
			foreach ($deleteList as $item)
			{
				$subscriber = $repository->find($item);
				$em->remove($subscriber);
				$em->flush();
			}
	
			$this->addFlash(
				'info',
				'Marked subscribers deleted.'
			);
		}
	
		$data = $request->query->all();
		!empty($data['sort']) ? $sortBy = $data['sort'] : $sortBy = 'id';
		!empty($data['order']) ? $order = $data['order'] : $order = 'ASC';
		!empty($data['page']) ? $pagenum = $data['page'] : $pagenum = 1;
	
	
		$items = $repository->findBy([],array($sortBy => $order));
		$totalItems = count($items);
	
		$itemsPerPage = 10;
		$urlPattern = $request->getPathInfo() .'?page=(:num)&sort='. $sortBy .'&order='. $order;
	
		$paginator = new Paginator($totalItems, $itemsPerPage, $pagenum, $urlPattern);
	
		return $this->render('admin/subscribers.html.twig', array(
				'items' => array_slice($items, ($pagenum - 1) * 10, 10),
				'paginator' => $paginator,
				'header' => 'Newsletter Subscribers',
		));
	}
	
	/**
	 * @Route("admin/garden-calendar", name="admin_calendar")
	 */
	public function adminGardenCalendar(Request $request) {
		$em = $this->getDoctrine()->getManager();
		$calendar = $em->getRepository('AppBundle:ProductCalendar')->find(1);
	
		$originalItems = array();
	
		foreach ($calendar->getItems() as $item) {
			$originalItems[] = array(
					'id' => $item->getId(),
					'filename' => $item->getFilename(),
			);
		}
		// image count
		$ctr_item = count($originalItems);
	
		$form = $this->createForm(ProductCalendarType::class, $calendar);
	
		$form->handleRequest($request);
		if ($form->isValid()) {
			$data = $request->request->get('product_calendar');
	
			// images
			$fs = new Filesystem();
			$rootDir = $this->container->getParameter('kernel.root_dir');
			if ($fs->exists($rootDir . '/../web/images/calendars'))
				$imagesDir = $rootDir . '/../web/images/calendars';
				else
					$imagesDir = $rootDir . '/../public/images/calendars';
	
					$items = $calendar->getItems();
	
					$imageList = $request->request->get('image');
					if (is_null($imageList))
						$imageList = array();
						foreach ($originalItems as $originalItem) {
							if (!in_array($originalItem['id'], $imageList)) {
								if ($originalItem['filename'] != '')
									$fs->remove($imagesDir .'/'. $originalItem['filename']);
	
									$originalItem = $em->getRepository('AppBundle:ProductCalendarItem')->find($originalItem['id']);
									$em->remove($originalItem);
	
									$ctr_item--;
							}
						}
	
						if (count($items) > 0) {
							$uploader = $this->get('app.file_uploader');
							$uploader->setTargetDir($imagesDir);
	
							foreach ($items as $item) {
								$alias = $this->get('app.misc_functions')->slug($item->getTitle());
								if (!is_null($item->getFilename())) {
									// process image
									if (!is_null($item->getId()))
										foreach ($originalItems as $originalItem)
											if ($originalItem['id'] == $item->getId())
												if (!empty($originalItem['filename']))
													$fs->remove($imagesDir . '/' . $originalItem['filename']);
	
													$ctr_item ++;
													$fileName = $uploader->uploadImage($item->getFilename(), $alias .'-'. $ctr_item .'-'. rand(0,1000));
	
													$source = $imagesDir . '/' . $fileName;
													$resized = $source;
	
													$this->get('app.image_resizer')->resize($source, null, 1900, 545, false, $resized, false, false, 80);
	
													$item
													->setCalendar($calendar)
													->setFileName($fileName);
													$em->persist($item);
								}
								else {
									if (is_null($item->getCalendar()))
										$calendar->getItems()->removeElement($item);
										else {
											foreach ($originalItems as $originalItem) {
												if ($item->getId() == $originalItem['id']) {
													$filename = $originalItem['filename'];
	
													$item->setFilename($filename);
													$em->persist($item);
													break;
												}
											}
										}
								}
							}
						}
	
						empty($data['description']) ? $description = '' : $description = $data['description'];
	
						$calendar
						->setDescription($description)
						;
	
						$em->persist($calendar);
						$em->flush();
	
						$this->addFlash(
								'info',
								'Garden Calendar updated.'
								);
		}
	
		return $this->render('admin/garden_calendar_form.html.twig', array(
				'form' => $form->createView(),
				'header' => 'Update Garden Calendar',
		));
	}
	
	/**
	 * @Route("admin/product/category/{id}", name="admin_product_category")
	 */
	public function adminProductCategory(Request $request, $id = 1){
		$repository = $this->getDoctrine()
			->getRepository('AppBundle:ProductCategory');
		
		$main = $request->query->get('main');
		if ($id <= 1) 
			$header = 'Shop';
		else 
			$header = $repository->find($id)->getTitle();

		
		if ($request->request->get('submit')) {
			$em = $this->getDoctrine()->getManager();
			$deleteList = $request->request->get('delete');
			foreach ($deleteList as $item)
			{
				$category = $repository->find($item);
				$category->setPublish(0);
				$em->flush();
			}
	
			$this->addFlash(
					'info',
					'Marked Categories deleted.'
					);
		}
	
		$data = $request->query->all();
		!empty($data['sort']) ? $sortBy = $data['sort'] : $sortBy = 'ordering';
		!empty($data['order']) ? $order = $data['order'] : $order = 'ASC';
		!empty($data['page']) ? $pagenum = $data['page'] : $pagenum = 1;
	
		$condition = array();
		
		$condition['publish'] = 1;
		
		if (!empty($id) && $id != 1) 
			$condition['parent_id'] = $id;

		$items = $repository->findBy($condition, array($sortBy => $order));
		$totalItems = count($items);
		
		$condition['parent_id'] = $id;
		$parents = $repository->findBy($condition, array($sortBy => $order));
		$items = array();
		foreach ($parents as $parent) {
			// 1st level
			$filters = $parent->getFilters();
			$filter_groups = array();
			foreach ($filters as $filter)
				$filter_groups[] = $filter->getTitle();
				count($filter_groups) > 0 ? $filter_groups = implode(', ', $filter_groups) : $filter_groups = '-';
				$item = array(
						'id' => $parent->getId(),
						'title' => $parent->getTitle(),
						'description' => strip_tags($parent->getDescription()),
						'filters' => $filter_groups,
						'image' => $parent->getImage(),
						'ordering' => $parent->getOrdering(),
						'level' => 0,
				);
				$items[] = $item;
	
				if (empty($main) && $main != 1) {
					// 2nd level
					$condition['parent_id'] = $parent->getId();
					$level_2s = $repository->findBy($condition);
					foreach ($level_2s as $level_2) {
						$filters = $level_2->getFilters();
						$filter_groups = array();
						foreach ($filters as $filter)
							$filter_groups[] = $filter->getTitle();
							count($filter_groups) > 0 ? $filter_groups = implode(', ', $filter_groups) : $filter_groups = '-';
							$item = array(
									'id' => $level_2->getId(),
									'title' => $level_2->getTitle(),
									'description' => strip_tags($level_2->getDescription()),
									'filters' => $filter_groups,
									'image' => $level_2->getImage(),
									'ordering' => $level_2->getOrdering(),
									'level' => 1,
							);
							$items[] = $item;
		
							// 3rd level
							$condition['parent_id'] = $level_2->getId();
							$level_3s = $repository->findBy($condition);
							foreach ($level_3s as $level_3) {
								$filters = $level_3->getFilters();
								$filter_groups = array();
								foreach ($filters as $filter)
									$filter_groups[] = $filter->getTitle();
									count($filter_groups) > 0 ? $filter_groups = implode(', ', $filter_groups) : $filter_groups = '-';
									$item = array(
											'id' => $level_3->getId(),
											'title' => $level_3->getTitle(),
											'description' => strip_tags($level_3->getDescription()),
											'filters' => $filter_groups,
											'image' => $level_3->getImage(),
											'ordering' => $level_3->getOrdering(),
											'level' => 2,
									);
									$items[] = $item;
							}
					}
				}
		}
	
		$itemsPerPage = 20;
		$urlPattern = $request->getPathInfo() .'?id='. $id .'&page=(:num)';
	
		$paginator = new Paginator($totalItems, $itemsPerPage, $pagenum, $urlPattern);
	
		return $this->render('admin/product_category.html.twig', array(
				'parent' => $id,
				'items' => array_slice($items, ($pagenum - 1) * 20, 20),
				'paginator' => $paginator,
				'header' => $header .' Categories',
		));
	}
	
	/**
	 * @Route("admin/product/category/add/{id}", name="admin_product_category_add")
	 */
	public function adminAddProductCategory(Request $request, $id = 1)
	{
		$em = $this->getDoctrine()->getManager();

		if ($id == 1 ) 
			$header = 'Shop';
		else
			if ($id == 'add') {
				$header = 'Shop';
				$id = 0;
			}
			else
				$header = $em->getRepository('AppBundle:ProductCategory')->find($id)->getTitle();
		
		$category = new ProductCategory();
		$category->setDescription('');
		$category->setParentId($id);
	
		$form = $this->createForm(ProductCategoryType::class, $category);
	
		$form->handleRequest($request);
	
		if ($form->isValid()) {
			$data = $request->request->get('product_category');
	
			// alias
			$alias = $this->get('app.misc_functions')->slug($data['title']);
			$duplicateCheck = $em->getRepository('AppBundle:ProductCategory')->findOneBy(array('alias' => $alias));
			while (count($duplicateCheck) > 0) {
				$alias .= '-';
				$duplicateCheck = $em->getRepository('AppBundle:ProductCategory')->findOneBy(array('alias' => $alias));
			}
	
			// filters
			$filters = $category->getFilters();
			foreach ($filters as $filter) {
				$filter->setCategory($category);
				$em->persist($filter);
			}
	
			// plant finder and additional info, check if category is under plants
			$parent = $category->getId();
			$tmp = $category;
			while ($tmp->getParentId() != 1) {
				$tmp = $tmp->getParent();
				$parent = $tmp->getId();
			}
			if ($parent == 4) {
				$plant_finder = $category->getPlantFinder();
				$plant_finder->setCategory($category);
				$em->persist($plant_finder);
				$category_info = $category->getCategoryInfo();
				$category_info->setCategory($category);
				$em->persist($category_info);
			}
			else {
				$category->setPlantFinder(null);
				$category->setCategoryInfo(null);
			}
					
			// image
			$image = $category->getImage();
			if (!empty($image))
			{
				$rootDir = $this->container->getParameter('kernel.root_dir');
	
				$fs = new Filesystem();
				if ($fs->exists($rootDir .'/../web/images/categories'))
					$imagesDir = $rootDir .'/../web/images/categories';
					else
						$imagesDir = $rootDir .'/../public/images/categories';
	
						$uploader = $this->get('app.file_uploader');
						$uploader->setTargetDir($imagesDir);
	
						$image = $uploader->uploadImage($image, $alias);
	
						$source = $imagesDir .'/'. $image;
						
						$resized = $imagesDir .'/thumb/'. $image;
						$this->get('app.image_resizer')->resize($source, null, 600, 600, false, $resized, false, false, 80);

						// plant finder thumbs
						if ($parent == 4) {
							$resized = $imagesDir .'/thumb-finder/'. $image;
							$this->get('app.image_resizer')->resize($source, null, 330, 240, false, $resized, false, false, 80);
						}

						$resized = $imagesDir .'/banner/'. $image;
						$this->get('app.image_resizer')->resize($source, null, 850, 350, false, $resized, true, false, 80);
			}
			else
				$image = '';
	
				$ordering = $em->getRepository('AppBundle:ProductCategory')->findBy(array('parent_id' => $data['parent']));
				$ordering = count($ordering);
				if ($ordering == 0)
					$ordering++;
	
					$category
					->setAlias($alias)
					->setImage($image)
					->setPublish(1)
					->setHits(0)
					->setOrdering($ordering)
					;
	
					$em->persist($category);
					$em->flush();
	
					$this->addFlash(
							'info',
							'Product Category added.'
							);
	
					$nextAction = $form->get('saveAndAdd')->isClicked()
						? 'admin_product_category_add'
						: 'admin_product_category';
	
					return $this->redirectToRoute($nextAction, array('id' => $id));
		}
	
		return $this->render('admin/product_category_form.html.twig', array(
				'form' => $form->createView(),
				'header' => 'Add '. $header .' Category',
				'parent' => $id
		));
	}
	
	/**
	 * @Route("admin/product/category/edit/{id}", name="admin_product_category_edit")
	 */
	public function adminEditProductCategory(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();

		$parent = $em->getRepository('AppBundle:ProductCategory')->find($id)->getParent();
		$header = $parent->getTitle();
		$cat_id = $parent->getId();
		
		$category = $em->getRepository('AppBundle:ProductCategory')->find($id);
		$old_image = $category->getImage();
	
		if (!$category) {
			$this->addFlash(
					'danger',
					'Invalid category.'
					);
	
			return $this->redirectToRoute('admin_product_category');
		}
	
		$originalFilters = new ArrayCollection();
	
		// Create an ArrayCollection of the current Tag objects in the database
		foreach ($category->getFilters() as $filter) {
			$originalFilters->add($filter);
		}
	
		$form = $this->createForm(ProductCategoryType::class, $category);
	
		$form->handleRequest($request);
	
		if ($form->isValid()) {
			// filters
			// remove the relationship between the tag and the Task
			foreach ($originalFilters as $filter) {
				if (false === $category->getFilters()->contains($filter)) {
					$em->remove($filter);
				}
			}
	
			// plant finder, check if under plants
			$parent = $category->getId();
			$tmp = $category;
			while ($tmp->getParentId() != 1) {
				$tmp = $tmp->getParent();
				$parent = $tmp->getId();
			}
			if ($parent == 4) {
				$plant_finder = $category->getPlantFinder();
				if (is_null($plant_finder->getCategory()))
					$plant_finder->setCategory($category);
				$em->persist($plant_finder);
				$category_info = $category->getCategoryInfo();
				if (is_null($category_info->getCategory()))
					$category_info->setCategory($category);
				$em->persist($category_info);
			}
			else {
				$category->setPlantFinder(null);
				$category->setCategoryInfo(null);
			}
	
				// image
				$image = $request->files->get('product_category');
				if (!is_null($image['image']))
				{
					$image = $image['image'];
					$rootDir = $this->container->getParameter('kernel.root_dir');
	
					$fs = new Filesystem();
					if ($fs->exists($rootDir .'/../web/images/categories'))
						$imagesDir = $rootDir .'/../web/images/categories';
						else
							$imagesDir = $rootDir .'/../public/images/categories';
	
							if (!empty($old_image)) {
								$fs->remove($imagesDir .'/thumb/'. $old_image);
								$fs->remove($imagesDir .'/thumb-finder/'. $old_image);
								$fs->remove($imagesDir .'/banner/'. $old_image);
							}
	
							$uploader = $this->get('app.file_uploader');
							$uploader->setTargetDir($imagesDir);
	
							$image = $uploader->uploadImage($image, ($category->getAlias() .'-'. rand(0,1000)));
	
							if (!is_null($image)) {
								$source = $imagesDir .'/'. $image;
								$resized = $imagesDir .'/thumb/'. $image;
	
								$this->get('app.image_resizer')->resize($source, null, 600, 600, false, $resized, false, false, 80);
	
								// plant finder thumbs
								if ($parent == 4) {
									$resized = $imagesDir .'/thumb-finder/'. $image;
									$this->get('app.image_resizer')->resize($source, null, 330, 240, false, $resized, false, false, 80);
								}

								$resized = $imagesDir .'/banner/'. $image;
								$this->get('app.image_resizer')->resize($source, null, 850, 350, false, $resized, true, false, 80);
								
							}
				}
				else
					$image = $old_image;
	
					$category
					->setImage($image)
					;
	
					// filters
					$filters = $category->getFilters();
					foreach ($filters as $filter) {
						$filter->setCategory($category);
						$em->persist($filter);
					}
	
					$em->persist($category);
					$em->flush();
	
					$this->addFlash(
							'info',
							'Product Category updated.'
							);
	
					$nextAction = $form->get('saveAndAdd')->isClicked()
					? 'admin_product_category_add'
							: 'admin_product_category';
	
							return $this->redirectToRoute($nextAction, array('id' => $parent));
		}
	
		return $this->render('admin/product_category_form.html.twig', array(
				'parent' => $cat_id,
				'form' => $form->createView(),
				'header' => 'Update '. $header .' Category',
		));
	}
	
	/**
	 * @Route("admin/product/item/{cat_id}", name="admin_product_item")
	 */
	public function adminProductItem(Request $request, $cat_id = 1){
		$em = $this->getDoctrine()->getManager();
		$repository = $this->getDoctrine()
			->getRepository('AppBundle:ProductItem');
		
		if ($cat_id != 1)
			$header = $em->getRepository('AppBundle:ProductCategory')->find($cat_id)->getTitle();
		else 
			$header = 'Products';
	
		if ($request->request->get('submit')) {
			$deleteList = $request->request->get('delete');
			foreach ($deleteList as $item)
			{
				$product = $repository->find($item);
				$product->setDeleted(1);
				$em->flush();
			}
	
			$this->addFlash(
					'info',
					'Marked Items deleted.'
					);
		}
	
		$data = $request->query->all();
		!empty($data['sort']) ? $sortBy = $data['sort'] : $sortBy = 'title';
		!empty($data['order']) ? $order = $data['order'] : $order = 'ASC';
		!empty($data['page']) ? $pagenum = $data['page'] : $pagenum = 1;
	
		$condition = array();
		$condition['deleted'] = 0;
	
		if ($cat_id != 1) {
			$category = $em->getRepository('AppBundle:ProductCategory')->find($cat_id);
			// check if parent
			$cat_ids = array();
			$cat_ids[] = $cat_id;
			$children = $category->getChildren();
			foreach ($children as $child) {
				$cat_ids[] = $child->getId();
				if (count($child->getChildren()) > 0)
					foreach ($child->getChildren() as $child_sub)
						$cat_ids[] = $child_sub->getId();
			}
			$condition['cat_id'] = $cat_ids;
		}
	
		$items = $repository->findBy($condition,array($sortBy => $order));
		$totalItems = count($items);

		$itemsPerPage = 10;
		$urlPattern = $request->getPathInfo() .'?page=(:num)&sort='. $sortBy .'&order='. $order;

		$paginator = new Paginator($totalItems, $itemsPerPage, $pagenum, $urlPattern);
	
		return $this->render('admin/product_item.html.twig', array(
				'items' => array_slice($items, ($pagenum - 1) * 10, 10),
				'paginator' => $paginator,
				'header' => $header,
				'cat_id' => $cat_id,
		));
	}
	
	/**
	 * @Route("admin/product/item/add/{cat_id}", name="admin_product_item_add")
	 */
	public function adminAddProductItem(Request $request, $cat_id = 1)
	{
		$em = $this->getDoctrine()->getManager();
		
		if ($cat_id == 1) 
			$header = 'Product';
		else 
			$header = $em->getRepository('AppBundle:ProductCategory')->find($cat_id)->getTitle();
		
		$item = new ProductItem();
	
		$form = $this->createForm(ProductItemType::class, $item);
	
		$form->handleRequest($request);
	
		if ($form->isValid()) {
			$data = $request->request->get('product_item');
	
			// filters
			!empty($data['filters']) ? $filters = $data['filters'] : $filters = array('');
	
			// alias
			$alias = $this->get('app.misc_functions')->slug($data['title']);
			$duplicateCheck = $em->getRepository('AppBundle:ProductItem')->findOneBy(array('alias' => $alias));
			while (count($duplicateCheck) > 0) {
				$alias .= '-';
				$duplicateCheck = $em->getRepository('AppBundle:ProductItem')->findOneBy(array('alias' => $alias));
			}
	
			// images
			$images = $item->getImages();
			if (count($images) > 0) {
				$rootDir = $this->container->getParameter('kernel.root_dir');
	
				$fs = new Filesystem();
				if ($fs->exists($rootDir . '/../web/images/listings'))
					$imagesDir = $rootDir . '/../web/images/listings';
					else
						$imagesDir = $rootDir . '/../public/images/listings';
	
						$uploader = $this->get('app.file_uploader');
						$uploader->setTargetDir($imagesDir);
	
						$ctr = 0;
						foreach ($images as $image) {
							// process image
							$ctr ++;
							$fileName = $uploader->uploadImage($image->getFilename(), $alias .'-'. $ctr);
	
							$source = $imagesDir . '/' . $fileName;
							$resized = $imagesDir . '/s/' . $fileName;
	
							$this->get('app.image_resizer')->resize($source, null, 600, 600, false, $resized, false, false, 80);
	
							$resized = $imagesDir . '/m/' . $fileName;
							$this->get('app.image_resizer')->resize($source, null, 850, 850, false, $resized, true, false, 80);
	
							$image
							->setItem($item)
							->setFileName($fileName);
							$em->persist($image);
						}
			}
	
			// query price details from POS
			$item
			->setAlias($alias)
			->setPriceOriginal(0)
			->setPriceSale(0)
			->setInStock(0)
			->setPending(0)
			->setFilters($filters)
			->setDateModified(0)
			->setDateAdded(time())
			->setHits(0)
			->setFeatured(0)
			->setPublish(1)
			;
	
			$em->persist($item);
			$em->flush();
	
			$this->addFlash(
					'info',
					'Product Item added.'
					);
	
			$nextAction = $form->get('saveAndAdd')->isClicked()
			? 'admin_product_item_add'
					: 'admin_product_item';
	
					return $this->redirectToRoute($nextAction);
		}
	
		return $this->render('admin/product_item_form.html.twig', array(
				'form' => $form->createView(),
				'header' => 'Add to '. $header,
				'cat_id' => $cat_id
		));
	}
	
	/**
	 * @Route("admin/product/item/edit/{id}", name="admin_product_item_edit")
	 */
	public function adminEditProductItem(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();
		$item = $em->getRepository('AppBundle:ProductItem')->find($id);
		
		$originalImages = array();
	
		foreach ($item->getImages() as $image) {
			$originalImages[] = array(
					'id' => $image->getId(),
					'filename' => $image->getFilename(),
			);
		}
		// image count
		$ctr_image = count($originalImages);
	
		$form = $this->createForm(ProductItemType::class, $item);
	
		$form->handleRequest($request);
		if ($form->isValid()) {
			$data = $request->request->get('product_item');
	
			// filters
			!empty($data['filters']) ? $filters = $data['filters'] : $filters = array('');
	
			// alias
			$alias = $this->get('app.misc_functions')->slug($data['title']);
			$duplicateCheck = $em->getRepository('AppBundle:ProductItem')->findOneBy(array('alias' => $alias));
			while (count($duplicateCheck) > 0) {
				$alias .= '-';
				$duplicateCheck = $em->getRepository('AppBundle:ProductItem')->findOneBy(array('alias' => $alias));
			}
	
			// images
			$fs = new Filesystem();
			$rootDir = $this->container->getParameter('kernel.root_dir');
			if ($fs->exists($rootDir . '/../web/images/listings'))
				$imagesDir = $rootDir . '/../web/images/listings';
				else
					$imagesDir = $rootDir . '/../public/images/listings';
	
					$images = $item->getImages();
	
					$imageList = $request->request->get('image');
					if (is_null($imageList))
						$imageList = array();
						foreach ($originalImages as $image) {
							if (!in_array($image['id'], $imageList)) {
								if ($image['filename'] != '') {
									$fs->remove($imagesDir .'/s/'. $image['filename']);
									$fs->remove($imagesDir .'/m/'. $image['filename']);
								}
	
								$image = $em->getRepository('AppBundle:ProductItemImage')->find($image['id']);
								$em->remove($image);
	
								$ctr_image--;
							}
						}
	
						if (count($images) > 0) {
							$uploader = $this->get('app.file_uploader');
							$uploader->setTargetDir($imagesDir);
	
							foreach ($images as $image) {
								if (!is_null($image->getFilename())) {
									// process image
									$ctr_image ++;
									$fileName = $uploader->uploadImage($image->getFilename(), $alias .'-'. $ctr_image .'-'. rand(0,1000));
	
									$source = $imagesDir . '/' . $fileName;
									$resized = $imagesDir . '/s/' . $fileName;
	
									$this->get('app.image_resizer')->resize($source, null, 600, 600, false, $resized, false, false, 80);
	
									$resized = $imagesDir . '/m/' . $fileName;
									$this->get('app.image_resizer')->resize($source, null, 850, 850, false, $resized, true, false, 80);
	
									$image
									->setItem($item)
									->setFileName($fileName);
									$em->persist($image);
								}
								else {
									if (is_null($image->getItem()))
										$item->getImages()->removeElement($image);
										else {
											foreach ($originalImages as $originalImage) {
												echo $originalImage['filename'];
												if ($image->getId() == $originalImage['id']) {
													$filename = $originalImage['filename'];
	
													$image->setFilename($filename);
													$em->persist($image);
													break;
												}
											}
										}
								}
							}
						}
	
						// query price details from POS
						$item
						->setAlias($alias)
						->setFilters($filters)
						->setDateModified(time())
						;
	
						$em->persist($item);
						$em->flush();
	
						$this->addFlash(
								'info',
								'Product Item updated.'
								);
	
						$nextAction = $form->get('saveAndAdd')->isClicked()
							? 'admin_product_item_add'
							: 'admin_product_item';
	
						return $this->redirectToRoute($nextAction, array('cat_id' => $item->getCatId()));
		}
	
		return $this->render('admin/product_item_form.html.twig', array(
				'form' => $form->createView(),
				'header' => 'Update '. $item->getTitle(),
				'cat_id' => $item->getCatId()
		));
	}

	/**
	 * @Route("admin/ajax-item-category-tags", name="ajax_item_category_tags")
	 */
	public function ajaxAdminItemCategoryTags(Request $request) {
		$data = $request->request->all();
		$em = $this->getDoctrine();
	
		$category = $em->getRepository('AppBundle:ProductCategory')->find($data['cat_id']);
		$filters = $category->getFilters();
	
		if ($data['id'] != 0) {
			$item = $em->getRepository('AppBundle:ProductItem')->find($data['id']);
			$item_filters = $item->getFilters();
		}
		else
			$item_filters = array();
	
			return $this->render('admin/ajax_item_category_tags.html.twig', array(
					'filters' => $filters,
					'item_filters' => $item_filters,
			));
	}
	
	
	/**
	 * @Route("admin/ajax-category-ordering", name="ajax_category_ordering")
	 */
	public function ajaxAdminCategoryOrdering(Request $request) {
		$data = $request->request->all();
	
		$categories = $this->getDoctrine()->getRepository('AppBundle:ProductCategory')->findBy(array('parent_id' => $data['id'], 'publish' => 1));
	
		return $this->render('admin/ajax_category_ordering.html.twig', array(
				'categories' => $categories,
				'selected' => $data['selected']
		));
	}
	
	/**
	 * @Route("admin/ajax-plant-finder", name="ajax_plant_finder")
	 */
	public function ajaxAdminPlantFinder(Request $request) {
		$data = $request->request->all();
	
		$category = $this->getDoctrine()->getRepository('AppBundle:ProductCategory')->find($data['cat_id']);
	
		// show plant finder tags for items under Plants category only
		$parent = $category->getId();
		while ($category->getParentId() != 1) {
			$category = $category->getParent();
			$parent = $category->getId();
		}
		if ($parent == 4)
			return $this->render('admin/ajax_plant_finder_show.html.twig');
			else
				return $this->render('admin/ajax_plant_finder_hide.html.twig');
	}
	
	/**
	 * @Route("admin/ajax-product-toggle", name="ajax_product_toggle")
	 */
	public function ajaxAdminProductToggle(Request $request) {
		$data = $request->request->all();
		$toggle = substr($data['toggle'],0,1);
		$mode = substr($data['mode'],0,9);
		$em = $this->getDoctrine()->getManager();
	
		$item = $em->getRepository('AppBundle:ProductItem')->find(substr($data['item'],0,7));
	
		if ($mode == 'published')
			$item->setPublish($toggle);
			else
				$item->setFeatured($toggle);
	
				$em->flush();
	
				($toggle == 0) ? $prefix = 'un' : $prefix = '';
					
		return new Response($item->getTitle() .' has been '. $prefix . $mode .'.');
	}
}
