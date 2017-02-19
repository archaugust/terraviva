<?php
namespace RouterBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RouterController extends Controller {
	/**
	 * @Route("/{alias}", name="_custom_route", requirements={"alias"=".+"})
	 */
	public function customRouteAction($alias)
	{
		$em = $this->getDoctrine()->getManager();
		$alias = explode('/', $alias);
		$slug = end($alias);

		$route = $em->getRepository('AppBundle:MenuItem')->findOneBy(array('alias' => $slug));
	
		if (count($route) == 0)
		{
			$content = $em->getRepository('AppBundle:Content')->findOneBy(array('alias' => $slug));
			
			if (count($content) == 0)
			{
				$this->addFlash(
					'danger',
					'Requested page does not exist or is no longer available.'. $slug
				);
	
				return $this->redirectToRoute('homepage');
			}
			else {
				$content->setHits($content->getHits()+1);
				$em->flush();
				
				$contentParts = array(
						'title' => $content->getTitle(),
						'content' => $content->getContent()
				);
				
				return $this->render('default/content.html.twig', $contentParts);
			}
		}
	
		$pageType = $route->getPageType();
		$pageTypeId = $route->getPageTypeId();
	
		switch ($pageType)
		{
			case 'content':
				{
					$content = $em->getRepository('AppBundle:Content')
						->findOneBy(array('alias' => $pageTypeId));
					$content->setHits($content->getHits()+1);
					$em->flush();
					$template = 'content';
					$contentParts = array(
							'title' => $content->getTitle(),
							'content' => $content->getContent()
					);
					break;
				}
			default:
				$this->addFlash(
					'danger',
					'Requested page does not exist or is no longer available.'
				);
	
				return $this->redirectToRoute('homepage');
		}
	
		return $this->render('default/'. $template .'.html.twig', $contentParts);
	}
}