<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use JasonGrimes\Paginator;

class PlantFinderController extends Controller {

	/**
	 * @Route("plant-finder", name="plant_finder")
	 */
	public function plantFinder(Request $request) {
		$data = $request->request->all();
		
		$search = true;
		foreach ($data as $tmp) {
			if (empty($tmp))
				$search = false;
		}

		if ($search == true && count($data) == 5) {
			$em = $this->getDoctrine()->getManager();
			
			$parameters = array(
				'moisture' => $data['moisture'],
				'type' => $data['type'],
				'light' => $data['light'],
			);
			
			$wind_options = explode(', ', $data['wind']);

			if (count($wind_options) > 1) {
				$ctr = 0;
				$wind = array();
				foreach ($wind_options as $option) {
					$wind[] = "r.wind_exposure LIKE :wind_exposure_". $ctr;
					$parameters['wind_exposure_'. $ctr] = '%'. $option .'%';
					$ctr++;
				}
				$wind = "(". implode(' OR ', $wind) .")";
			}
			else {
				$wind = "r.wind_exposure LIKE :wind";
				$parameters['wind'] = '%'. $data['wind'] .'%';
			}
			

			$height_options = explode(', ', $data['height']);
			
			if (count($height_options) > 1) {
				$ctr = 0;
				$height = array();
				foreach ($height_options as $option) {
					$height[] = "r.plant_height LIKE :plant_height_". $ctr;
					$parameters['plant_height_'. $ctr] = '%'. $option .'%';
					$ctr++;
				}
				$height = "(". implode(' OR ', $height) .")";
			}
			else {
				$height = "r.plant_height LIKE :height";
				$parameters['height'] = '%'. $data['height'] .'%';
			}
				
			$query = $em->createQuery("
				SELECT r 
	              FROM AppBundle:ProductPlantFinder r 
	              WHERE 
					r.soil_moisture = :moisture AND 
					r.soil_type = :type AND 
					r.light = :light AND
					". $wind ." AND
					". $height ."
    	    	")
	        	->setParameters($parameters);
	        $results = $query->getResult();

	        $items = $em->getRepository('AppBundle:ProductCategory')->findBy(array('parent_id' => 4, 'publish' => 1)); // cats under plants
	        
	        if ($data['moisture'] == 'Normal')
	        	$data['moisture'] .= ' moisture';
	        
	        if ($data['type'] == 'Normal')
	        	$data['type'] .= ' type';
	        
	        foreach ($data as $item) 
	        	$item = strtolower($item);
	         
	        return $this->render('shop/plant_finder_results.html.twig', array(
	        		'results' => $results,
	        		'parent' => 'All Plants',
	        		'items' => $items,
	        		'data' => $data,
	         ));
		}
		else 
			return $this->render('shop/plant_finder.html.twig');
	}
}