<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tv1_product_plant_finder")
 */
class ProductPlantFinder
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $cat_id;

    /**
     * @ORM\OneToOne(targetEntity="ProductCategory", inversedBy="plant_finder")
     * @ORM\JoinColumn(name="cat_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $soil_moisture;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $soil_type;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $light;

    /**
     * @ORM\Column(type="simple_array")
     */
    private $wind_exposure;

    /**
     * @ORM\Column(type="simple_array")
     */
    private $plant_height;
    
    /**
     * @ORM\Column(type="string", length=20)
     */
    private $custom_height = '';


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set catId
     *
     * @param integer $catId
     *
     * @return ProductPlantFinder
     */
    public function setCatId($catId)
    {
        $this->cat_id = $catId;

        return $this;
    }

    /**
     * Get catId
     *
     * @return integer
     */
    public function getCatId()
    {
        return $this->cat_id;
    }

    /**
     * Set soilMoisture
     *
     * @param string $soilMoisture
     *
     * @return ProductPlantFinder
     */
    public function setSoilMoisture($soilMoisture)
    {
        $this->soil_moisture = $soilMoisture;

        return $this;
    }

    /**
     * Get soilMoisture
     *
     * @return string
     */
    public function getSoilMoisture()
    {
        return $this->soil_moisture;
    }

    /**
     * Set soilType
     *
     * @param string $soilType
     *
     * @return ProductPlantFinder
     */
    public function setSoilType($soilType)
    {
        $this->soil_type = $soilType;

        return $this;
    }

    /**
     * Get soilType
     *
     * @return string
     */
    public function getSoilType()
    {
        return $this->soil_type;
    }

    /**
     * Set light
     *
     * @param string $light
     *
     * @return ProductPlantFinder
     */
    public function setLight($light)
    {
        $this->light = $light;

        return $this;
    }

    /**
     * Get light
     *
     * @return string
     */
    public function getLight()
    {
        return $this->light;
    }

    /**
     * Set windExposure
     *
     * @param array $windExposure
     *
     * @return ProductPlantFinder
     */
    public function setWindExposure($windExposure)
    {
        $this->wind_exposure = $windExposure;

        return $this;
    }

    /**
     * Get windExposure
     *
     * @return array
     */
    public function getWindExposure()
    {
        return $this->wind_exposure;
    }

    /**
     * Set plantHeight
     *
     * @param array $plantHeight
     *
     * @return ProductPlantFinder
     */
    public function setPlantHeight($plantHeight)
    {
        $this->plant_height = $plantHeight;

        return $this;
    }

    /**
     * Get plantHeight
     *
     * @return array
     */
    public function getPlantHeight()
    {
        return $this->plant_height;
    }

    /**
     * Set customHeight
     *
     * @param string $customHeight
     *
     * @return ProductPlantFinder
     */
    public function setCustomHeight($customHeight)
    {
        $this->custom_height = $customHeight;

        return $this;
    }

    /**
     * Get customHeight
     *
     * @return string
     */
    public function getCustomHeight()
    {
        return $this->custom_height;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\ProductCategory $category
     *
     * @return ProductPlantFinder
     */
    public function setCategory(\AppBundle\Entity\ProductCategory $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\ProductCategory
     */
    public function getCategory()
    {
        return $this->category;
    }
}
